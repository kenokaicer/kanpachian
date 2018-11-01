<?php namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Dao\BD\ClientDao as ClientDao;
use Models\User as User;
use Models\Client as Client;
use Models\Role as Role;
use Exception as Exception;

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
                require VIEWS_PATH."Login.php"; 
            }else if($_SESSION["userLogged"]->getRole=="Admin"){ //Check if user is admin
                header("location:".FRONT_ROOT."Admin/Index"); 
            }
            else if(isset($_SESSION["lastLocation"])){ // return to logged event start view
                //header("location:) //see how to return to place where loggin event ocurred
            }else{
                header("location:".FRONT_ROOT."Main/Index");
            }
        }catch(Exception $ex){
            echo "<script> alert('".$ex->getMessage()."'<script>;";
            header("location:".FRONT_ROOT."Main/Index");
        }   
    }   

    public function sessionStart($username, $password)
    {   
        try{
            $user = $this->userDao->getByUsername($username);

            if(password_verify($password, $user->getPassword())) //check if password provided coincides with hashed and salted one in BD
            {
                Session::add("userLogged", $user);
            }
            else
            {
                echo "<script> alert('Datos ingresados no correctos');</script>";
            }
            $this->index();
        }catch(Exception $ex){
            echo "<script> alert('No se pudo realizar el loggeo. Error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
    }

    public function registerUser()
    {
        require VIEWS_PATH."Register.php"; 
    }

    public function addUser($username,$password,$email,$name,$lastname,$dni)
    {
        try{
            if(empty($this->userDao->getByUsername($username)))
            {
                $user = new User();

                //set user, create Client, and set it
            }
        }catch(Excpetion $ex){

        }
        //require VIEWS_PATH."Register.php";
        //check if username exist

    }
}