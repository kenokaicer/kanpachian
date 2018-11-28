<?php
namespace Controllers;

use Dao\BD\UserDao as UserDao;
use Dao\BD\ClientDao as ClientDao;
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
        $this->userDao = new UserDao();
        $this->clientDao = new ClientDao();
    }

    public function index($alert = array())
    { 
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

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
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // hash and salt password
            
            $user = new User();
            
            $user->setUsername($userName);
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            $user->setRole($role);
        
            $this->userDao->Add($user);

            $alert["title"] = "Usuario agregado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar el usuario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        $this->index($alert);
    }

    public function userList($alert = array())
    {
        try{
            $userList = $this->userDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar Usuarios";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        require VIEWS_PATH.$this->folder."UserManagementList.php";
    }

    public function deleteUser($idUser)
    {
        try{
            $client = $this->clientDao->getByUserId($idUser);
            $user = $client->getUser();

            $this->userDao->Delete($user);
            $this->clientDao->Delete($client);

            $alert["title"] = "Usuario y Cliente eliminados agregado exitosamente";
            $alert["icon"] = "success";
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el usuario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->userList($alert);
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
            $alert["title"] = "Error al cargar el usuario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->userList($alert);
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

            $alert["title"] = "Usuario modificado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el usuario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->userList($alert);
    }

}
