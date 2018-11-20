<?php
namespace Controllers;

use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;
use Dao\BD\LoadType as LoadType;
use Models\EventByDate as EventByDate;
use Models\Artist as Artist;
use Exception as Exception;
use Cross\Session as Session;

class EventByDateManagementController
{
    private $eventByDateDao;
    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $folder = "Management/EventByDate/";

    public function __construct()
    {
        Session::adminLogged();
        $this->eventByDateDao = new EventByDateDao();
        $this->theaterDao = new TheaterDao();
        $this->artistDao = new ArtistDao();
        $this->eventDao = new EventDao();
    }

    public function index()
    {
        require VIEWS_PATH.$this->folder."EventByDateManagement.php";
    }

    public function viewAddEventByDate()
    {   
        try{
            $theaterList = $this->theaterDao->getAll();
            $artistList = $this->artistDao->getAll();
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('No se pude cargar datos necesarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        require VIEWS_PATH.$this->folder."EventByDateManagementAdd.php";
    }

    /**
     * Not complete, waiting for ajax of artists in add
     */
    public function addEventByDate($idEvent, $date, $idTheater, $idArtistList)
    {
        $eventByDate = new EventByDate();
        
        try{
        $eventByDate->setDate($date);
        
        $theater = $this->theaterDao->getById($idTheater);
        $event = $this->eventDao->getById($idEvent);

        $eventByDate->setTheater($theater);
        $eventByDate->setEvent($event);

        $idArtistList = json_decode($idArtistList);

        foreach ($idArtistList as $idArtist) {
            try{
                $artist = $this->artistDao->getById($idArtist);
            }catch (Exception $ex){
                echo "<script> alert('No se pudo agregar el calendario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
                $this->index();
            }

            $eventByDate->addArtist($artist);
        }
            $this->eventByDateDao->Add($eventByDate);
            echo "<script> alert('Calendario agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el calendario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function eventByDateList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."EventByDateManagementList.php";
    }

    public function eventByDateList2($idEvent)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getByEventId($idEvent, LoadType::Lazy1);//get by Event, lazy load, omit seatTypes for theater, Event and Category
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Calendarios: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."EventByDateManagementList2.php";
    }

    public function deleteEventByDate($id)
    {
        $eventByDate = $this->eventByDateDao->getById($idEventByDate);

        try{
            $this->eventByDateDao->Delete($eventByDate);
            echo "<script> alert('Calendario eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el calendario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->eventByDateList();
    }

    /**
     * Recieve id of EventByDate to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editEventByDate
     */
    public function viewEditEventByDate($idEventByDate)
    {   
        $oldEventByDate = $this->eventByDateDao->getById($idEventByDate);

        require VIEWS_PATH.$this->folder."EventByDateManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object EventByDate
     * and old object by id, call dao update
     */
    public function editEventByDate($oldIdEventByDate, $eventByDate)
    {
        $oldEventByDate = $this->eventByDateDao->getById($oldIdEventByDate);
        $newEventByDate = new EventByDate();

        $args = func_get_args();
        $eventByDateAttributeList = array_combine(array_keys($newEventByDate->getAll()),array_values($args)); 

        foreach ($eventByDateAttributeList as $attribute => $value) {
            $newEventByDate->__set($attribute,$value);
        }

        try{
            $this->eventByDateDao->Update($oldEventByDate, $newEventByDate);
            echo "<script> alert('Calendario modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el calendario " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->eventByDateList();
    }

}
