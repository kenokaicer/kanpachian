<?php
namespace Controllers;

use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;
use Models\EventByDate as EventByDate;
use Models\Artist as Artist;
use Exception as Exception;

class EventByDateManagementController
{
    protected $message;
    private $eventByDateDao;
    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $folder = "Management/EventByDate/";

    public function __construct()
    {
        $this->eventByDateDao = EventByDateDao::getInstance();
        $this->theaterDao = TheaterDao::getInstance();
        $this->artistDao = ArtistDao::getInstance();
        $this->eventDao = EventDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."EventByDateManagement.php";
    }

    public function viewAddEventByDate()
    {   
        try{
            $theaterList = $this->theaterDao->getAll();
            $artistList = $this->artistDao->getAll();
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('No se pude cargar datos necesarios. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        require VIEWS_PATH.$this->folder."EventByDateManagementAdd.php";
    }

    public  function test($data)
    {
        require "Views/admin.php";

        var_dump($data);

        print(" SUCCCCESSSSSSSSSSSSSSSSSSSSSSSSXXX");
    }

    /**
     * Not complete, waiting for ajax of artists in add
     */
    public function addEventByDate($idEvent, $date, $idTheater, $idArtistList)
    {
        $eventByDate = new EventByDate();
        
        $eventByDate->setDate($date);

        try{
            $theater = $this->theaterDao->getById($idTheater);
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        $eventByDate->setTheater($theater);
        $eventByDate->setEvent($event);

        //-----------------------
        //deserialize $idArtistList
        //-----------------------

        foreach ($idArtistList as $idArtist) {
            try{
                $artist = $this->artistDao->getById($idArtist);
            }catch (Exception $ex){
                echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
                $this->index();
            }

            $eventByDate->addArtist($artist);
        }

        try{
            $this->eventByDateDao->Add($eventByDate);
            echo "<script> alert('Calendario agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function eventByDateList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."EventByDateManagementList.php";
    }

    public function eventByDateList2($idEvent)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getByEventId($idEvent);//get by Event
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Calendarios: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('No se pudo eliminar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('No se pudo modificar el calendario " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->eventByDateList();
    }

}
