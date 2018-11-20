<?php
namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Models\User as User;
use Models\Role as Role;
use Exception as Exception;
use Cross\Session as Session;

class UserManagementController
{
    private $userDao;
    private $folder = "Management/User/";

    public function __construct()
    {
        Session::adminLogged();
        $this->userDao = new UserDao(); //BD
    }

    public function index()
    { 
        require VIEWS_PATH.$this->folder."UserManagement.php";
    }

    public function viewAddUser()
    {   
        $roles = Role::getConstants();
        require VIEWS_PATH.$this->folder."UserManagementAdd.php";
    }

    public function addUser($userName,$password,$email="",$role)
    {
        try{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $user = new User();
            
            $user->setUsername($userName);
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            $user->setRole($role);
        
            $this->userDao->Add($user);
            echo "<script> alert('Usuario agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la usuario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function userList()
    {
        try{
            $userList = $this->userDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Usuarios: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."UserManagementList.php";
    }

    public function deleteUser($idUser)
    {
        try{
            $user = $this->userDao->getById($idUser);
            $this->userDao->Delete($user);
            echo "<script> alert('Usuario eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la usuario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->userList();
    }

    /**
     * Recieve id of User to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editUser
     */
    public function viewEditUser($idUser)
    {   
        try{
            $oldUser = $this->userDao->getById($idUser);
            $roles = Role::getConstants();
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo cargar el usuario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        } 

        require VIEWS_PATH.$this->folder."UserManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object User
     * and old object by id, call dao update
     */
    public function editUser($oldIdUser, $user, $email="", $role)
    {
        try{
            $oldUser = $this->userDao->getById($oldIdUser);
            $newUser = new User();

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $newUser = new User();

            $newUser->setIdUser($oldIdUser);
            $newUser->setUsername($userName);
            $newUser->setPassword($hashedPassword);
            $newUser->setEmail($email);
            $newUser->setRole($role);

            $this->userDao->Update($oldUser, $newUser);
            echo "<script> alert('Usuario modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el usuario " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->userList();
    }

}
