<?php
namespace Controllers;

//use Dao\BD\EventsByDateDao as EventsByDateDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;
use Models\EventsByDate as EventsByDate;
use Models\Artist as Artist;
use Exception as Exception;

class EventsByDateManagementController
{
    protected $message;
    private $eventsByDateDao;
    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $folder = "EventsByDateManagement/";

    public function __construct()
    {
        //$this->eventsByDateDao = EventsByDateDao::getInstance(); //BD
        $this->theaterDao = TheaterDao::getInstance();
        $this->artistDao = ArtistDao::getInstance();
        $this->eventDao = EventDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."EventsByDateManagement.php";
    }

    public function viewAddEventsByDate()
    {   
        try{
            $theaterList = $this->theaterDao->getAll();
            $artistList = $this->artistDao->getAll();
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('No se pude cargar datos necesarios. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        require VIEWS_PATH.$this->folder."EventsByDateManagementAdd.php";
    }

    /**
     * Not complete, waiting for ajax of artists in add
     */
    public function addEventsByDate($idEvent, $date, $idTheater, $idArtistList)
    {
        $eventsByDate = new EventsByDate();
        
        $eventsByDate->setDate($date);

        try{
            $theater = $this->theaterDao->getByID($idTheater);
            $event = $this->eventDao->getByID($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }

        $eventsByDate->setTheater($theater);
        $eventsByDate->setEvent($event);

        //-----------------------
        //deserialize $idArtistList
        //-----------------------

        foreach ($idArtistList as $idArtist) {
            try{
                $artist = $this->artistDao->getByID($idArtist);
            }catch (Exception $ex){
                echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
                $this->index();
            }

            $eventsByDate->addArtist($artist);
        }

        try{
            $this->eventsByDateDao->Add($eventsByDate);
            echo "<script> alert('Calendario agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function eventsByDateList()
    {
        try{
            $eventsByDateList = $this->eventsByDateDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Calendarios: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."EventsByDateManagementList.php";
    }

    public function deleteEventsByDate($id)
    {
        $eventsByDate = $this->eventsByDateDao->getByID($idEventsByDate);

        try{
            $this->eventsByDateDao->Delete($eventsByDate);
            echo "<script> alert('Calendario eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el calendario. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->eventsByDateList();
    }

    /**
     * Recieve id of EventsByDate to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editEventsByDate
     */
    public function viewEditEventsByDate($idEventsByDate)
    {   
        $oldEventsByDate = $this->eventsByDateDao->getByID($idEventsByDate);

        require VIEWS_PATH.$this->folder."EventsByDateManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object EventsByDate
     * and old object by id, call dao update
     */
    public function editEventsByDate($oldIdEventsByDate, $eventsByDate)
    {
        $oldEventsByDate = $this->eventsByDateDao->getByID($oldIdEventsByDate);
        $newEventsByDate = new EventsByDate();

        $args = func_get_args();
        $eventsByDateAtributeList = array_combine(array_keys($newEventsByDate->getAll()),array_values($args)); 

        foreach ($eventsByDateAtributeList as $atribute => $value) {
            $newEventsByDate->__set($atribute,$value);
        }

        try{
            $this->eventsByDateDao->Update($oldEventsByDate, $newEventsByDate);
            echo "<script> alert('Calendario modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el calendario " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->eventsByDateList();
    }

}
