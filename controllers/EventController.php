<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Exception as Exception;

class EventController
{
    private $eventDao;
    private $categoryDao;

    public function __construct()
    {
        $this->eventDao = new EventDao();
        $this->eventByDate = new EventByDateDao();
    }

    public function index($idEvent)
    { //agregar validaciones aca (ej userLogged)
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $eventByDateList = $this->eventByDate->getByEventIdLazy($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
        var_dump($eventByDateList);
        var_dump($idEvent);
        require VIEWS_PATH."Event.php";
    }
}