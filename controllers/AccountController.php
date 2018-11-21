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

    public function index()
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
            echo "<script> alert('".$ex->getMessage()."'); 
            window.location.replace('".FRONT_ROOT."Home/index');
        </script>";
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
                echo "<script> alert('Datos ingresados no correctos');</script>";
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
                echo "<script> alert('Datos ingresados no correctos');</script>";
            }
        }
        catch(Exception $ex){
            echo "<script> alert('No se pudo realizar el loggeo. Error: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        
        $this->index();
    }

    public function registerUser()
    {
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
                echo "<script> alert('El usuario ya existe');</script>";
                $this->registerUser();
            }
        }catch(Exception $ex){
            echo "<script> alert('Error interno al registrar nuevo usuario. Error: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->registerUser();
        }
    }

    public function sessionClose(){
        try{
            Session::close();
            //use script to redirect otherwise doesn't show alert
            echo "<script> alert('Sesión Cerrada'); 
                window.location.replace('".FRONT_ROOT."Home/index');
                </script>";
            exit;
        }catch(Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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
            $args = func_get_args();
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
                $creditCardAttributes = CreditCard::getAttributes();

                $artistAttributeList = array_combine(array_keys($creditCardAttributes), $args);

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
            $client = $this->clientDao->getByUserId($idUser);
            $idClient = $client->getIdClient();

            $this->clientDao->addCreditCardByClientId($idClient, $idCreditCard);

            echo "<script> alert('Tarjeta de Credito registrada exitosamente, redirigiendo');</script>";
            if($redirect == "yes"){
                echo "<script>window.location.replace('".FRONT_ROOT."Purchase/confirmPurchase');</script>";
                exit;
            }else{
                $this->accountView();
            }    
        }catch(Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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

                echo "<script> alert('Contraseña cambiada con éxito');</script>";
                $this->userDao->update($user,$newUser);

                $_SESSION["userLogged"] = $newUser;
            }
            else{
                echo "<script> alert('Contraseña ingresada no es válida');</script>";
            }   
        }catch(Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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

            echo "<script> alert('Email cambiado con éxito');</script>";
            $this->userDao->update($user,$newUser);

            $_SESSION["userLogged"] = $newUser;
        }catch(Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->accountView();
    }

    /**
     * This should have some kind of obscuration, as giving anyone the chance to input any id in the address is not secure
     */
    public function viewTicket($idTicket)
    {
        try{
            $ticket = $this->ticketDao->getById($idTicket);
            $ticketList = array();
            $ticketList[] = $ticket;
            setlocale(LC_TIME, array("ES","esl","spa")); //set locale of time to spanish, array tries each code until it gets a success
        }catch (Exception $ex){
            echo "<script> alert('Error getting tickets. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }
        
        require VIEWS_PATH."ticket.php";
    }
}