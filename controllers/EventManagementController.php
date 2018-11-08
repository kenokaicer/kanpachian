<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\CategoryDao as CategoryDao;
use Models\Event as Event;
use Exception as Exception;
use Cross\FileUpload as FileUpload;
use Models\File as File;

class EventManagementController
{
    protected $message;
    private $eventDao;
    private $categoryDao;
    private $folder = "Management/Event/";

    public function __construct()
    {
        $this->eventDao = EventDao::getInstance(); //BD
        $this->categoryDao = CategoryDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."EventManagement.php";
    }

    public function viewAddEvent()
    {
        $categoryList = $this->categoryDao->getAll();
        require VIEWS_PATH.$this->folder."EventManagementAdd.php";
    }

    public function addEvent($eventName, $description, $idCategory)
    {
        try{
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
                $filePath = File::upload($file);
            } else {
            $filePath = null;
            echo "<script> alert('Advertencia, imagen no ingresada');</script>";
            }
        }catch (Exception $ex) {
            echo "<script> alert('Error al subir imágen: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        try{
            $args = array();

            $eventAttributes = Event::getAttributes();
            
            array_push($args, null, $eventName, $filePath, $description);

            $eventAttributeList = array_combine(array_keys($eventAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

            $event = new Event();
            foreach ($eventAttributeList as $attribute => $value) {
                $event->__set($attribute,$value);
            }
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $category = $this->categoryDao->getById($idCategory);

            $event->setCategory($category);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar la categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        try{
            $this->eventDao->Add($event);
            echo "<script> alert('Evento agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        $this->index();
    }

    public function eventList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        require VIEWS_PATH.$this->folder."EventManagementList.php";
    }

    public function deleteEvent($id)
    {
        $event = $this->eventDao->getById($id);

        try{
            $this->eventDao->Delete($event);
            echo "<script> alert('Eventa eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->eventList();
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
            echo "<script> alert('Error getting oldEvent. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        try{
            $categoryList = $this->categoryDao->getAll();
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo listar las categorías. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 
    
        require VIEWS_PATH.$this->folder."EventManagementEdit.php";
    }

    public function getEventById($idEvent)
    {
         echo $idEvent;
        $ev = '';
        try{
            $ev = $this->eventDao->getById($idEvent);


        } catch (Exception $ex) {
            echo "<script> alert('Error getting oldEvent. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        echo "{ respuesta }";

      
        

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
            echo "<script> alert('Error al subir imágen: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('No se pudo agregar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $category = $this->categoryDao->getById($idCategory);
            
            $newEvent->setCategory($category);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar la categoría. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        try{
            $this->eventDao->Update($oldEvent, $newEvent);
            echo "<script> alert('Evento modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el evento " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->eventList();
    }

}
