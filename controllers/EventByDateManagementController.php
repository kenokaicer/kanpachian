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

    public function index($alert = array())
    {
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        require VIEWS_PATH.$this->folder."EventByDateManagement.php";
    }

    public function viewAddEventByDate()
    {   
        try{
            $theaterList = $this->theaterDao->getAll();
            $artistList = $this->artistDao->getAll();
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            $alert["title"] = "Error al cargar datos necesarios";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
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

            $alert["title"] = "Calendario agregado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar el calendario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        $this->index($alert);
    }

    public function eventByDateList($alert = array())
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

                $alert["title"] = "Calendario eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Calendario no puede ser borrado";
                $alert["text"] = "Ya hay entradas vendidas para el mismo";
                $alert["icon"] = "warning";
            }  
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el calendario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->eventByDateList($alert);
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
            echo "<script>swal({
                title:'Error al cargar el calendario!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
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

            $alert["title"] = "Calendario modificado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el calendario";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->eventByDateList($alert);
    }

}
