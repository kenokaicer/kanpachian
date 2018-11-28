<?php
namespace Controllers;

use Dao\BD\ClientDao as ClientDao;
use Dao\BD\UserDao as UserDao;
use Models\Client as Client;
use Exception as Exception;
use Cross\Session as Session;

class ClientManagementController
{
    private $clientDao;
    private $categoryDao;
    private $folder = "Management/Client/";

    public function __construct()
    {
        Session::adminLogged();
        $this->clientDao = new ClientDao(); //BD
        $this->userDao = new UserDao();
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

        require VIEWS_PATH.$this->folder."ClientManagement.php";
    }

    /**
     * Not used
     */
    /*public function viewAddClient()
    {
        $categoryList = $this->categoryDao->getAll();
        require VIEWS_PATH.$this->folder."ClientManagementAdd.php";
    }*/

    /**
     * Not used
     */
    /*public function addClient($name, $lastname, $dni, $idUser)
    {
        try{

            $client = new Client();

            $clientAttributes = $client->getAll();
            
            $args = func_get_args();
            array_unshift($args, null); //put null at first of array for id
            array_pop($args);
            
            $clientAttributeList = array_combine(array_keys($clientAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

            foreach ($clientAttributeList as $attribute => $value) {
                $client->__set($attribute,$value);
            }

            $user = $this->userDao->getById($idUser);

            $client->setUser($user);

            $this->clientDao->Add($client);
            echo "<script> alert('Cliente agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el cliente. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
        
        $this->index();
    }*/

    public function clientList($alert = array())
    {
        try{
            $clientList = $this->clientDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar Clientes";
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

        require VIEWS_PATH.$this->folder."ClientManagementList.php";
    }

    public function deleteClient($id)
    {   
        try{
            $client = $this->clientDao->getById($id);

            $this->userDao->Delete($client->getUser());
            $this->clientDao->Delete($client);

            $alert["title"] = "Cliente y usuario eliminados exitosamente";
            $alert["icon"] = "success";
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el cliente";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->clientList($alert);
    }

    /**
     * Not Used
     */
    /*public function viewEditClient($idClient)
    {   
        $oldClient = $this->clientDao->getById($idClient);

        require VIEWS_PATH.$this->folder."ClientManagementEdit.php";
    }*/

    /**
     * Not Used
     */
    /*public function editClient($oldIdClient, $client)
    {
        $oldClient = $this->clientDao->getById($oldIdClient);
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
            echo "<script> alert('No se pudo modificar el categoría " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->clientList();
    }*/

}
