<?php
namespace Controllers;

use Dao\BD\ClientDao as ClientDao;
use Dao\BD\UserDao as UserDao;
use Models\Client as Client;
use Exception as Exception;

class ClientManagementController
{
    protected $message;
    private $clientDao;
    private $categoryDao;
    private $folder = "Management/Client/";

    public function __construct()
    {
        $this->clientDao = ClientDao::getInstance(); //BD
        $this->userDao = UserDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."ClientManagement.php";
    }

    public function viewAddClient()
    {
        $categoryList = $this->categoryDao->getAll();
        require VIEWS_PATH.$this->folder."ClientManagementAdd.php";
    }

    public function addClient($name, $lastname, $dni, $idUser)
    {
        $client = new Client();

        $clientAttributes = $client->getAll();
        array_pop($clientAttributes);
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        array_pop($args);
        
        $clientAttributeList = array_combine(array_keys($clientAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

        foreach ($clientAttributeList as $attribute => $value) {
            $client->__set($attribute,$value);
        }

        try{
            $user = $this->userDao->getByID($idUser);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el usuario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        $client->setUser($user);

        try{
            $this->clientDao->Add($client);
            echo "<script> alert('Cliente agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el cliente. " . str_replace("'", "", $ex->getMessage()) . "');</script>";        
        }
        
        $this->index();
    }

    public function clientList()
    {
        try{
            $clientList = $this->clientDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Clientes: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        require VIEWS_PATH.$this->folder."ClientManagementList.php";
    }

    public function deleteClient($id)
    {
        $client = $this->clientDao->getByID($id);

        try{
            $this->clientDao->Delete($client);
            echo "<script> alert('Clienta eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la categoría. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->clientList();
    }

    /**
     * Recieve id of Client to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editClient
     */
    public function viewEditClient($idClient)
    {   
        $oldClient = $this->clientDao->getByID($idClient);

        require VIEWS_PATH.$this->folder."ClientManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object Client
     * and old object by id, call dao update
     */
    public function editClient($oldIdClient, $client)
    {
        $oldClient = $this->clientDao->getByID($oldIdClient);
        $newClient = new Client();

        $args = func_get_args();
        $clientAttributeList = array_combine(array_keys($newClient->getAll()),array_values($args)); 

        foreach ($clientAttributeList as $attribute => $value) {
            $newClient->__set($attribute,$value);
        }

        try{
            $this->clientDao->Update($oldClient, $newClient);
            echo "<script> alert('Categoría modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el categoría " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->clientList();
    }

}
