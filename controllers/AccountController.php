<?php namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Dao\BD\ClientDao as ClientDao;
use Dao\BD\CreditCardDao as CreditCardDao;
use Dao\BD\PurchaseDao as PurchaseDao;
use Dao\BD\TicketDao as TicketDao;
use Dao\BD\LoadType as LoadType;
use Models\User as User;
use Models\Client as Client;
use Models\Role as Role;
use Models\CreditCard as CreditCard;
use Exception as Exception;
use Cross\Session as Session;

class AccountController
{
    private $userDao;
    private $clientDao;

    public function __construct()
    {
        $this->userDao = new UserDao();
        $this->clientDao = new ClientDao();
        $this->creditCardDao = new CreditCardDao();
        $this->purchaseDao = new PurchaseDao();
        $this->ticketDao = new TicketDao();
    }

    public function index($alert = array())
    {
        try{    
            if(!isset($_SESSION["userLogged"])){ //Check if there is a user logged
                require VIEWS_PATH."Login.php"; 
            }else if($_SESSION["userLogged"]->getRole()=="Admin"){ //Check if user is admin
                echo "<script>window.location.replace('".FRONT_ROOT."Admin/index');</script>";
                exit; 
            }
            else if(isset($_SESSION["lastLocation"])){ // return to logged event start view
                echo "<script>window.location.replace('".FRONT_ROOT."Purchase/addPurchaseLine');</script>";
                exit;
            }else{
                echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
                exit;
            }
        }catch(Exception $ex){
            require VIEWS_PATH."redirect.php";
            echo "<script>swal({title:'Error!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
            exit;
        }   
    }   

    public function sessionStart($username, $password)
    {   
        try
        {
            $user = $this->userDao->getByUsername($username);
            if($user == null) // if null the db didnt find any matches.
            {
                echo "<script>swal({
                    title:'Datos ingresados no correctos!', 
                    text:'', 
                    icon:'warning'
                    });</script>";
            }
            else if(password_verify($password, $user->getPassword())){ //check if password provided coincides with hashed and salted one in BD
                Session::add("userLogged", $user);
                
                if($user->getRole() == "Common"){
                    Session::add("virtualCart", array());
                
                    $client = $this->clientDao->getByUserId($user->getIdUser());
                    $clientName = $client->getName()." ".$client->getLastName();
                    
                    Session::add("clientName", $clientName);
                }else if($user->getRole() == "Admin"){
                    echo "<script>window.location.replace('".FRONT_ROOT."Admin/index');</script>";
                    exit; 
                }else{
                    Session::remove();
                    throw new Exception ("Role not defined");
                }
            }else{
                echo "<script>swal({
                    title:'Datos ingresados no correctos!', 
                    text:'', 
                    icon:'warning'
                    });</script>";
            }
        }
        catch(Exception $ex){
            echo "<script>swal({
                title:'Error, no se pudo realizar el logueo', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }

        $this->index();
    }

    public function registerUser($alert = array())
    {
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }
        
        require VIEWS_PATH."Register.php"; 
    }

    public function addUser($username,$password,$email,$name,$lastname,$dni)
    {
        $args = get_defined_vars(); //returns defined vars at the moment, funtion vars in this case
        $usernameExists = $this->userDao->getByUsername($username);

        try{
            if(is_null($usernameExists))
            {
                $user = new User();
                $client = new Client();

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //hash and salt password

                //more security can be added by encrypting the result with AES before sending it to the database
                //password for the encriptation would be in the php code, this is preferable to pepper
                //Standard php-encryption library: https://github.com/defuse/php-encryption
                //PHP default encryptation function: http://php.net/manual/en/function.openssl-encrypt.php

                $userAttributes = $user->getAll();
                $clientAttributes = $client->getAll();

                foreach ($userAttributes as $attribute => $value) { 
                    foreach ($args as $key => $value) { //check if there's a value to set
                        if($attribute==$key)
                            $user->__set($attribute,$args[$attribute]);
                    }
                }

                foreach ($clientAttributes as $attribute => $value) {
                    foreach ($args as $key => $value) {
                        if($attribute==$key)
                            $client->__set($attribute,$args[$attribute]);
                    }
                }
                
                $user->setPassword($hashedPassword);
                $user->setRole("Common");
                $client->setUser($user);

                $this->clientDao->add($client);

                $this->sessionStart($username, $password); //this needs to be done, to get userId in the object
            }else {
                $alert["title"] = "El usuario ya existe";
                $alert["icon"] = "warning";
                $this->registerUser($alert);
            }
        }catch(Exception $ex){
            $alert["title"] = "Error interno al registrar nuevo usuario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->registerUser($alert);
        }
    }

    public function sessionClose(){
        try{
            require VIEWS_PATH."redirect.php";

            Session::close();
             echo "<script>swal({title:'Sesión cerrada!', 
                text:'', 
                icon:'success'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
            exit;
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al cerrar sesión!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
            $this->index();
        }
        
    }

    public function viewRegisterCreditCard($redirect="")
    {
        Session::userLogged();

        require VIEWS_PATH."CreditCard.php"; 
    }

    /**
     * This in the future should have an api that validates credit cards
     */
    public function registerCreditCard($creditCardNumber,$expirationDate,$cardHolder,$redirect="yes")
    {
        Session::userLogged();

        try{
            $args = get_defined_vars(); //do not use fun_get_args(), because the $redirect var, not always used, if not used (and is defualt) the function will not pick it, unlike defined_vars
            array_pop($args);
            array_unshift($args, null);

            $creditCard = $this->creditCardDao->getByCreditCardNumber($creditCardNumber);

            /**
             * check if creditCard is already in the database
             * if it's not, add it
             * if it is link that one to the client
             */
            if(is_null($creditCard)) 
            {
                $creditCardAttributes = array_keys(CreditCard::getAttributes());

                $artistAttributeList = array_combine($creditCardAttributes, $args);

                $artistAttributeList["expirationDate"] = date("Y-m-d", strtotime($artistAttributeList["expirationDate"])); //transform YYYY-MM into YYYY-MM-DD for database format

                $creditCard = new CreditCard();
                foreach ($artistAttributeList as $attribute => $value) {
                    $creditCard->__set($attribute,$value);
                }

                $idCreditCard = $this->creditCardDao->Add($creditCard);
            }else{
                $idCreditCard = $creditCard->getIdCreditCard();
            }

            $idUser = $_SESSION["userLogged"]->getIdUser();
            $client = $this->clientDao->getByUserId($idUser, LoadType::Lazy1);
            $idClient = $client->getIdClient();

            $this->clientDao->addCreditCardByClientId($idClient, $idCreditCard);

            echo "<script>swal({
                title:'Tarjeta de Credito registrada exitosamente!', 
                text:'Redirigiendo...', 
                icon:'success'
                });</script>";
            if($redirect == "yes"){
                echo "<script>swal({
                    title:'Tarjeta de Credito registrada exitosamente!', 
                    text:'Redirigiendo...', 
                    timer: 3000,
                    icon:'success'}).then(
                    function(){window.location.href = '".FRONT_ROOT."Purchase/confirmPurchase';});</script>";
                exit;
            }else{
                echo "<script>swal({
                    title:'Tarjeta de Credito registrada exitosamente!', 
                    text:'', 
                    icon:'success'
                    });</script>";
                $this->accountView();
            }    
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al registrar tarjeta de crédito!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
            $this->index();
        }
    }

    public function viewPurchases()
    {
        Session::userLogged();

        try{
            $idUser = $_SESSION["userLogged"]->getIdUser();

            $client = $this->clientDao->getByUserId($idUser);
            $idClient = $client->getIdClient();

            $purchaseList = $this->purchaseDao->getAllByIdClient($idClient, LoadType::Lazy1);
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al cargar datos de usuario!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
            $this->index();
        }

        require VIEWS_PATH."viewPurchases.php"; 
    }

    public function accountView()
    {
        Session::userLogged();

        try{
            $user = $_SESSION["userLogged"];
            $client = $this->clientDao->getByUserId($user->getIdUser());
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al cargar datos de usuario!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
            $this->index();
        }

        require VIEWS_PATH."accountView.php"; 
    }

    public function changePassword($password, $newPassword)
    {
        Session::userLogged();

        try{
            $user = $_SESSION["userLogged"];

            if(password_verify($password, $user->getPassword())){
                $newUser = clone $user;

                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $newUser->setPassword($hashedPassword);
                
                $this->userDao->update($user,$newUser);

                $_SESSION["userLogged"] = $newUser;

                echo "<script>swal({
                    title:'Contraseña cambiada con éxito!', 
                    text:'', 
                    icon:'success'
                    });</script>";
            }
            else{
                echo "<script>swal({
                    title:'Contraseña ingresada no es válida!', 
                    text:'', 
                    icon:'warning'
                    });</script>";
            }   
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al cambiar contraseña!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }

        $this->accountView();
    }

    public function changeEmail($newEmail)
    {
        Session::userLogged();

        try{
            $user = $_SESSION["userLogged"];
            $newUser = clone $user;

            $newUser->setEmail($newEmail);

            $this->userDao->update($user,$newUser);

            $_SESSION["userLogged"] = $newUser;

            echo "<script>swal({
                title:'Email cambiado con éxito!', 
                text:'', 
                icon:'success'
                });</script>";
        }catch(Exception $ex){
            echo "<script>swal({
                title:'Error al cambiar email!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }

        $this->accountView();
    }

    public function viewTicket($ticketCode)
    {
        try{
            $ticket = $this->ticketDao->getByTicketCode($ticketCode);
            
            if(!is_null($ticket)){
                $ticketList = array();
                $ticketList[] = $ticket;
                setlocale(LC_TIME, array("ES","esl","spa")); //set locale of time to spanish, array tries each code until it gets a success
            }else{
                echo "<script>swal({
                    title:'Código de ticket inexistente!', 
                    text:'', 
                    icon:'warning'}).then(
                    function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
            }
        }catch (Exception $ex){
            echo "<script>swal({
                title:'Error al cargar tickets!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }
        
        require VIEWS_PATH."ticket.php";
    }
}