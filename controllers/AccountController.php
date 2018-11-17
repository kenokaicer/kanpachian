<?php namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Dao\BD\ClientDao as ClientDao;
use Dao\BD\CreditCardDao as CreditCardDao;
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
    }

    public function index()
    {
        try{
            
            if(!isset($_SESSION["userLogged"])){ //Check if there is a user logged
                require VIEWS_PATH."Login.php"; 
            }else if($_SESSION["userLogged"]->getRole()=="Admin"){ //Check if user is admin
                header("location:".FRONT_ROOT."Admin/index");
                exit; 
            }
            else if(isset($_SESSION["lastLocation"])){ // return to logged event start view
                header("location:".FRONT_ROOT."Cart/addPurchaseLine"); 
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
            else
            {

            if(password_verify($password, $user->getPassword())) //check if password provided coincides with hashed and salted one in BD
            {
                Session::add("userLogged", $user);
                Session::add("virtualCart", array());
            }else{
                echo "<script> alert('Datos ingresados no correctos');</script>";
            }
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

    public function viewRegisterCreditCard()
    {
        Session::userLogged();

        require VIEWS_PATH."CreditCard.php"; 
    }

    public function registerCreditCard($creditCardNumber,$expirationDate,$cardHolder)
    {
        Session::userLogged();

        try{
            $args = func_get_args();
            array_unshift($args, null);

            $creditCardAttributes = CreditCard::getAttributes();

            $artistAttributeList = array_combine(array_keys($creditCardAttributes), $args);

            $artistAttributeList["expirationDate"] = date("Y-m-d", strtotime($artistAttributeList["expirationDate"])); //transform YYYY-MM into YYYY-MM-DD for database format

            $creditCard = new CreditCard();
            foreach ($artistAttributeList as $attribute => $value) {
                $creditCard->__set($attribute,$value);
            }
            
            $idUser = $_SESSION["userLogged"]->getIdUser();

            $idCreditCard = $this->creditCardDao->addReturningId($creditCard);
            $client = $this->clientDao->getByUserId($idUser);
            
            $idClient = $client->getIdClient();

            $this->clientDao->addCreditCardByClientId($idClient, $idCreditCard);

            echo "<script> alert('Tarjeta de Credito registrada exitosamente, redirigiendo');</script>";
            //if statement, if coming from account management
            echo "<script>window.location.replace('".FRONT_ROOT."Purchase/confirmPurchase');</script>";
            exit;
        }catch(Exception $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
    }
}