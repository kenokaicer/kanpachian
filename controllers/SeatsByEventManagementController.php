<?php
namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\SeatTypeDao as SeatTypeDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\EventDao as EventDao;

use Models\SeatsByEvent as SeatsByEvent;

use Exception as Exception;

class SeatsByEventManagementController
{
    protected $message;
    private $seatsByEventDao;
    private $seatTypeDao;

    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $folder = "Management/SeatsByEvent/";

    public function __construct()
    {
        $this->seatsByEventDao = SeatsByEventDao::getInstance();
        $this->eventDao = EventDao::getInstance();
        $this->seatTypeDao = SeatTypeDao::getInstance();
        $this->eventByDateDao = EventByDateDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH.$this->folder."SeatsByEventManagement.php";
    }

    public function viewAddSeatsByEvent()
    {   
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('No se pude cargar datos necesarios. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementAdd.php";
    }

    /**
     * almost complete, waiting for ajax of artists in add
     */
    public function addSeatsByEvent($eventByDateId, $seatTypeIdList, $quantityList, $priceList)
    {
        $seatsByEvent = new SeatsByEvent();
        
        //-----------------------
        //deserialize $seatTypeIdList
        //quantityList
        //priceList
        //-----------------------

        try{
            $eventByDate = $this->eventByDateDao->getByID($eventByDateId);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        foreach ($seatTypeIdList as $seatTypeIdItem) {
            try{
                $seatType = $this->seatTypeDao->getByID($seatTypeIdItem);
            }catch (Exception $ex){
                echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
                $this->index();
            }
            
            array_push($seatTypeList, $seatType);
        }

        for ($i=0; $i < count($seatTypeList); $i++) { //count how many SeatByEvent were recieved, and add that many SeatsByEvent
            $seatsByEvent = new SeatsByEvent();

            $seatsByEvent->setEventByDate($eventByDate);
            $seatsByEvent->setQuantity($quantityList[$i]);
            $seatsByEvent->setRemnants($quantityList[$i]);
            $seatsByEvent->setPrice($priceList[$i]);

            try{
                $this->seatsByEventDao->Add($seatsByEvent);
            }catch (Exception $ex){
                echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
                $this->index();
            }
        }

        echo "<script> alert('Plaza/s Evento agregada/s exitosamente');</script>";

        $this->index();
    }

    public function seatsByEventList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementList.php";
    }

    public function seatsByEventList2($idEvent)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getByEventID($idEvent);//get by Event
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Calendarios: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."SeatsByEventManagementList2.php";
    }

    public function seatsByEventList3($idEventByDate)
    {
        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateID($idEventByDate);
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Calendarios: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."SeatsByEventManagementList3.php";
    }

    public function deleteSeatsByEvent($id)
    {
        $seatsByEvent = $this->seatsByEventDao->getByID($idSeatsByEvent);

        try{
            $this->seatsByEventDao->Delete($seatsByEvent);
            echo "<script> alert('Asiento por Evento eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el Asiento por Evento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->seatsByEventList();
    }

    /**
     * Recieve id of SeatsByEvent to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editSeatsByEvent
     */
    public function viewEditSeatsByEvent($idSeatsByEvent)
    {   
        $oldSeatsByEvent = $this->seatsByEventDao->getByID($idSeatsByEvent);

        require VIEWS_PATH.$this->folder."SeatsByEventManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object SeatsByEvent
     * and old object by id, call dao update
     */
    public function editSeatsByEvent($oldIdSeatsByEvent, $seatsByEvent)
    {
        $oldSeatsByEvent = $this->seatsByEventDao->getByID($oldIdSeatsByEvent);
        $newSeatsByEvent = new SeatsByEvent();

        $args = func_get_args();
        $seatsByEventAtributeList = array_combine(array_keys($newSeatsByEvent->getAll()),array_values($args)); 

        foreach ($seatsByEventAtributeList as $atribute => $value) {
            $newSeatsByEvent->__set($atribute,$value);
        }

        try{
            $this->seatsByEventDao->Update($oldSeatsByEvent, $newSeatsByEvent);
            echo "<script> alert('Calendario modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el calendario " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->seatsByEventList();
    }

}
