<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\CategoryDao as CategoryDao;
use Models\Event as Event;
use Exception as Exception;

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

    public function addEvent($eventName, $image, $description, $idCategory)
    {
        $event = new Event();

        $eventAttributes = $event->getAll();
        array_pop($eventAttributes);
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        array_pop($args);
        
        $eventAtributeList = array_combine(array_keys($eventAttributes),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it

        foreach ($eventAtributeList as $atribute => $value) {
            $event->__set($atribute,$value);
        }

        try{
            $category = $this->categoryDao->getByID($idCategory);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar la categoría. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        $event->setCategory($category);

        try{
            $this->eventDao->Add($event);
            echo "<script> alert('Evento agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el evento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";        
        }
        
        $this->index();
    }

    public function eventList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        require VIEWS_PATH.$this->folder."EventManagementList.php";
    }

    public function deleteEvent($id)
    {
        $event = $this->eventDao->getByID($id);

        try{
            $this->eventDao->Delete($event);
            echo "<script> alert('Eventa eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar la categoría. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->eventList();
    }

    /**
     * Recieve id of Event to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editEvent
     */
    public function viewEditEvent($idEvent)
    {   
        $oldEvent = $this->eventDao->getByID($idEvent);

        require VIEWS_PATH.$this->folder."EventManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object Event
     * and old object by id, call dao update
     */
    public function editEvent($oldIdEvent, $event)
    {
        $oldEvent = $this->eventDao->getByID($oldIdEvent);
        $newEvent = new Event();

        $args = func_get_args();
        $eventAtributeList = array_combine(array_keys($newEvent->getAll()),array_values($args)); 

        foreach ($eventAtributeList as $atribute => $value) {
            $newEvent->__set($atribute,$value);
        }

        try{
            $this->eventDao->Update($oldEvent, $newEvent);
            echo "<script> alert('Categoría modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el categoría " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->eventList();
    }

}
