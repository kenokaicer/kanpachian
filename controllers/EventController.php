<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Exception as Exception;

class EventController
{
    private $eventDao;
    private $categoryDao;

    public function __construct()
    {
        $this->eventDao = new EventDao();
        $this->eventByDate = new EventByDateDao();
        $this->seatsByEventDao = new SeatsByEventDao();
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

        require VIEWS_PATH."Event.php";
    }

    public function showSeatsByEvent($idEvent, $idEventByDate)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento por fecha. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
        require VIEWS_PATH."EventByDate.php";
    }

    public function test($var)
    {
        print('<p id="demo">Click the button to change the text in this paragraph.</p>');
        
    }
}