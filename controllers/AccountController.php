<?php namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Dao\BD\ClientDao as ClientDao;
use Models\User as User;
use Models\Client as Client;
use Models\Role as Role;
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
    }

    public function index()
    {
        try{
            
            if(!isset($_SESSION["userLogged"])){ //Check if there is a user logged
                Session::printAll();
                require VIEWS_PATH."Login.php"; 
            }else if($_SESSION["userLogged"]->getRole()=="Admin"){ //Check if user is admin
                header("location:".FRONT_ROOT."Admin/index"); 
            }
            else if(isset($_SESSION["lastLocation"])){ // return to logged event start view
                header("location:".FRONT_ROOT."Cart/addPurchaseLine"); 
            }else{
                header("location:".FRONT_ROOT."Home/index");
            }
        }catch(Exception $ex){
            echo "<script> alert('".$ex->getMessage()."'); 
            window.location.replace('".FRONT_ROOT."Home/index');
        </script>";
        }   
    }   

    public function sessionStart($username, $password)
    {   
        try{
            Session::printAll();
            die();
            $user = $this->userDao->getByUsername($username);

            if(password_verify($password, $user->getPassword())) //check if password provided coincides with hashed and salted one in BD
            {
                Session::add("userLogged", $user);
                Session::add("virtualCart", array());
            }
            else
            {
                echo "<script> alert('Datos ingresados no correctos');</script>";
            }
            $this->index();
        }catch(Exception $ex){
            echo "<script> alert('No se pudo realizar el loggeo. Error: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
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
            if(empty($usernameExists) || $usernameExists == null)
            {
                $user = new User();
                $client = new Client();

                $password = password_hash($password, PASSWORD_DEFAULT); //hash and salt password

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
                
                $user->setPassword($password);
                $user->setRole("Common");
                $client->setUser($user);

                $this->clientDao->add($client);

                $this->sessionStart($user->getUsername, $password); //this needs to be done, to get userId in the object
            }else {
                echo "<script> alert('El usuario ya existe');</script>";
                $this->index();
            }
        }catch(Excpetion $ex){
            echo "<script> alert('Error interno al registrar nuevo usuario. Error: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
    }

    public function sessionClose(){
        try{
            Session::close();
            //use script to redirect otherwise doesn't show alert
            echo "<script> alert('Sesión Cerrada'); 
                window.location.replace('".FRONT_ROOT."Home/index');
            </script>";
        }catch(Excpetion $ex){
            echo "<script> alert('" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
    }
}