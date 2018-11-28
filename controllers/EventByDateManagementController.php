<?php
namespace Controllers;

use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;
use Dao\BD\LoadType as LoadType;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Models\EventByDate as EventByDate;
use Models\Artist as Artist;
use Exception as Exception;
use Cross\Session as Session;

class EventByDateManagementController
{
    private $eventByDateDao;
    private $seatsByEventDao;
    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $purchaseLineDao;
    private $folder = "Management/EventByDate/";

    public function __construct()
    {
        Session::adminLogged();
        $this->eventByDateDao = new EventByDateDao();
        $this->seatsByEventDao = new SeatsByEventDao();
        $this->theaterDao = new TheaterDao();
        $this->artistDao = new ArtistDao();
        $this->eventDao = new EventDao();
        $this->purchaseLineDao = new PurchaseLineDao();
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

    public function addEventByDate($idEvent, $isSale, $date, $date2, $idTheater, $idArtistList)
    {
        $eventByDate = new EventByDate();
        
        try{
            $eventByDate->setDate($date);
            $eventByDate->setEndPromoDate($date2);
            $eventByDate->setIsSale($isSale);
            
            $theater = $this->theaterDao->getById($idTheater);
            $event = $this->eventDao->getById($idEvent);

            $eventByDate->setTheater($theater);
            $eventByDate->setEvent($event);
           
            
            $idArtistList = json_decode($idArtistList);

            foreach ($idArtistList as $idArtist) {
                $artist = $this->artistDao->getById($idArtist);
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


    public function deleteEventByDate($idEventByDate)
    {
        try{
            $eventByDate = $this->eventByDateDao->getById($idEventByDate);
            $empty = true;

            if(date("Y-m-d") < $eventByDate->getDate()){ //check if eventByDate has expired, if not, do the rest of the check
                $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate,LoadType::Lazy3);

                foreach ($seatsByEventList as $seatsByEvent) {
                    if(!empty($this->purchaseLineDao->getAllPastNowBySeatsByEvent($seatsByEvent->getIdSeatsByEvent()))){ //check if there are tickes sold for each seatsByEvent
                        $empty = false;
                    }
                }
            }
            
            if($empty){ //if empty wasn't modified by past checks, delete
                $this->eventByDateDao->Delete($eventByDate);
                echo "<script> alert('Calendario eliminado exitosamente');</script>";
            }else{
                echo "<script> alert('Calendario no puede ser borrado, ya que hay entradas vendidas para el mismo');</script>";
            }  
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
        try{
            $eventByDate = $this->eventByDateDao->getById($idEventByDate);
            $theaterList = $this->theaterDao->getAll();
            $artistList = $this->artistDao->getAll();
            $eventList = $this->eventDao->getAll();
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo cargar calendario. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        require VIEWS_PATH.$this->folder."EventByDateManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object EventByDate
     * and old object by id, call dao update
     */
    public function editEventByDate($oldIdEventByDate, $idEvent, $date, $idTheater, $idArtistList)
    {
        try{
            $oldEventByDate = $this->eventByDateDao->getById($oldIdEventByDate);
            
            $eventByDate = new EventByDate();
 
            $eventByDate->setDate($date);
            
            $theater = $this->theaterDao->getById($idTheater);
            $event = $this->eventDao->getById($idEvent);

            $eventByDate->setTheater($theater);
            $eventByDate->setEvent($event);

            $idArtistList = json_decode($idArtistList);

            foreach ($idArtistList as $idArtist) {
                $artist = $this->artistDao->getById($idArtist);
                $eventByDate->addArtist($artist);
            }

            $this->eventByDateDao->Update($oldEventByDate, $eventByDate);
            echo "<script> alert('Calendario modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el calendario " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->eventByDateList();
    }

}
