<?php
namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Models\User as User;
use Models\Role as Role;
use Exception as Exception;

class UserManagementController
{
    protected $message;
    private $userDao;
    private $folder = "Management/User/";

    public function __construct()
    {
        $this->userDao = UserDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."UserManagement.php";
    }

    public function viewAddUser()
    {   
        $roles = Role::getConstants();
        require VIEWS_PATH.$this->folder."UserManagementAdd.php";
    }

    public function addUser($userName)
    {
        $user = new User();
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        
        $userAtributeList = array_combine(array_keys($user->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
        
        foreach ($userAtributeList as $atribute => $value) {
            $user->__set($atribute,$value);
        }

        try{
            $this->userDao->Add($user);
            echo "<script> alert('Usuario agregada exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la usuario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function userList()
    {
        try{
            $userList = $this->userDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Usuarios: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."UserManagementList.php";
    }

    public function deleteUser($id)
    {
        $user = $this->userDao->getByID($idUser);

        try{
            $this->userDao->Delete($user);
            echo "<script> alert('Usera eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la usuario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->userList();
    }

    /**
     * Recieve id of User to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editUser
     */
    public function viewEditUser($idUser)
    {   
        $oldUser = $this->userDao->getByID($idUser);
        $roles = Role::getConstants();

        require VIEWS_PATH.$this->folder."UserManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object User
     * and old object by id, call dao update
     */
    public function editUser($oldIdUser, $user)
    {
        $oldUser = $this->userDao->getByID($oldIdUser);
        $newUser = new User();

        $args = func_get_args();
        $userAtributeList = array_combine(array_keys($newUser->getAll()),array_values($args)); 

        foreach ($userAtributeList as $atribute => $value) {
            $newUser->__set($atribute,$value);
        }

        try{
            $this->userDao->Update($oldUser, $newUser);
            echo "<script> alert('Usuario modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el usuario " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->userList();
    }

}
