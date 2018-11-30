<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\CategoryDao as CategoryDao;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Models\Event as Event;
use Exception as Exception;
use Models\File as File;
use Cross\Session as Session;

class EventManagementController
{
    private $eventDao;
    private $categoryDao;
    private $purchaseLineDao;
    private $folder = "Management/Event/";

    public function __construct()
    {
        Session::adminLogged();
        $this->eventDao = new EventDao(); //BD
        $this->categoryDao = new CategoryDao(); //BD
        $this->purchaseLineDao = new PurchaseLineDao();
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

        require VIEWS_PATH.$this->folder."EventManagement.php";
    }

    public function viewAddEvent()
    {
        try{
            $categoryList = $this->categoryDao->getAll();
        }catch (Exception $ex){
            echo "<script>swal({
                title:'Error al cargar categorías!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }

        require VIEWS_PATH.$this->folder."EventManagementAdd.php";
    }

    public function addEvent($eventName, $description, $idCategory)
    {
        $exist = false;
        try{
            if(!is_null($this->eventDao->getByEventName($eventName)))
            {
                $alert["title"] = "Evento con mismo nombre ya existente";
                $alert["icon"] = "warning";
                $exist = true;
            }
        }
        catch (Exception $ex){
            $alert["title"] = "Error al confirmar Evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        try{
            if(!$exist){
                if (!empty($_FILES['file']['name'])) {
                    $file = $_FILES['file'];
                    $filePath = File::upload($file);
                } else {
                $filePath = null;
                $alert["title"] = "Advertencia, imagen no ingresada";
                $alert["text"] = "El evento se cargará en el sistema, puede agregar una imágen en el futuro modificando el mismo";
                $alert["icon"] = "warning";
                }
            }
        }catch (Exception $ex) {
            $alert["title"] = "Error al subir imágen";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        try{
            if(!$exist){
                $args = func_get_args();
                array_unshift($args, null); //put null at first of array for id
                array_pop($args);

                $eventAttributes = Event::getAttributes();
                
                array_push($args, $filePath);

                $eventAttributeList = array_combine(array_keys($eventAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

                $event = new Event();
                foreach ($eventAttributeList as $attribute => $value) {
                    $event->__set($attribute,$value);
                }
            
                $category = $this->categoryDao->getById($idCategory);

                $event->setCategory($category);
            
                $this->eventDao->Add($event);

                $alert["title"] = "Evento agregado exitosamente";
                $alert["icon"] = "success";
            }
        }catch (Exception $ex){
            if (strpos($ex->getMessage(), 'Duplicate') !== false) { //check on unique contraint in DB
                $alert["title"] = "Evento con mismo nombre ya existente";
                $alert["icon"] = "warning";
            }else{
                $alert["title"] = "Error al agregar el evento";
                $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
                $alert["icon"] = "error";
            }
        }

        $this->index($alert);
    }

    public function eventList($alert = array())
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar Eventos";
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

        require VIEWS_PATH.$this->folder."EventManagementList.php";
    }

    public function deleteEvent($idEvent)
    {
        try{
            $event = $this->eventDao->getById($idEvent);

            if(empty($this->purchaseLineDao->getAllPastNowByEvent($idEvent)))
            {
                $this->eventDao->Delete($event);

                $alert["title"] = "Evento eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "No se puede borrar el evento";
                $alert["text"] = "ya tiene entradas vendidas a futuro";
                $alert["icon"] = "warning";
            }

        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->eventList($alert);
    }

    /**
     * Recieve id of Event to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editEvent
     */
    public function viewEditEvent($idEvent)
    {   
        try{
            $oldEvent = $this->eventDao->getById($idEvent);
        } catch (Exception $ex) {
            echo "<script>swal({
                title:'Error al cargar evento!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        } 

        try{
            $categoryList = $this->categoryDao->getAll();
        } catch (Exception $ex) {
            echo "<script>swal({
                title:'Error al listar las categorías!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        } 
    
        require VIEWS_PATH.$this->folder."EventManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object Event
     * and old object by id, call dao update
     */
    public function editEvent($oldIdEvent, $eventName, $description, $idCategory)
    {
        $oldEvent = $this->eventDao->getById($oldIdEvent);

        try{
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
                $filePath = File::upload($file);
            } else {
                $filePath = null;
            }
        }catch (Exception $ex) {
            $alert["title"] = "Error al subir imágen";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        try{
            $args = array();

            $eventAttributes = Event::getAttributes();
            
            array_push($args, $oldIdEvent, $eventName, $filePath, $description);

            $eventAttributeList = array_combine(array_keys($eventAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

            $newEvent = new Event();
            foreach ($eventAttributeList as $attribute => $value) {
                $newEvent->__set($attribute,$value);
            }
        }catch (Exception $ex){
            $alert["title"] = "Error al modificar el evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        try{
            $category = $this->categoryDao->getById($idCategory);
            
            $newEvent->setCategory($category);
        }catch (Exception $ex){
            $alert["title"] = "Error al cargar la categoría";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }

        try{
            $this->eventDao->Update($oldEvent, $newEvent);

            $alert["title"] = "Evento modificado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->eventList($alert);
    }

}
