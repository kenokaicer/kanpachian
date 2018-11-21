<?php
namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\SeatTypeDao as SeatTypeDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\EventDao as EventDao;
use Models\SeatsByEvent as SeatsByEvent;
use Cross\Session as Session;

use Exception as Exception;

class SeatsByEventManagementController
{
    private $seatsByEventDao;
    private $seatTypeDao;
    private $theaterDao;
    private $artistDao;
    private $eventDao;
    private $folder = "Management/SeatsByEvent/";

    public function __construct()
    {
        Session::adminLogged();
        $this->seatsByEventDao = new SeatsByEventDao();
        $this->eventDao = new eventDao();
        $this->seatTypeDao = new SeatTypeDao();
        $this->eventByDateDao = new EventByDateDao();
    }

    public function index()
    {
        require VIEWS_PATH.$this->folder."SeatsByEventManagement.php";
    }

    public function viewAddSeatsByEvent()
    {   
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('No se pude cargar datos necesarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementAdd.php";
    }

    /**
     * Now only adding one seat at a time
     */
    public function addSeatsByEvent($eventByDateId, $seatTypeId, $quantity, $price)
    {
        $seatsByEvent = new SeatsByEvent();

        //get already inserted SeatsByEvent
        //this isn't necessary if loading all at the same time
        try{ 
            $seatTypesAlreadyAdded = $this->seatsByEventDao->getIdSeatTypesByEventByDate($eventByDateId);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar plazas evento ya insertadas. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        //-----------------------
        //deserialize: This is done only if recieving all seatTypes at the same time, currently not done this way.
        //-seatTypeIdList
        //-quantityList
        //-priceList
        //-----------------------

        try{
            $eventByDate = $this->eventByDateDao->getById($eventByDateId);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            $this->index();
        }
        
        try{
            //foreach ($seatTypeIdList as $seatTypeIdItem) { //disabled until method for adding changes to list
                try{
                    $seatType = $this->seatTypeDao->getById($seatTypeId);
                }catch (Exception $ex){
                    echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
                }
    
                foreach ($seatTypesAlreadyAdded as $value) {
                    if ($value->getIdSeatType() == $seatType->getIdSeatType()){
                        throw new Exception ("Plaza ya insertada");
                    }
                }
                
                $seatsByEvent = new SeatsByEvent();
    
                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);
                $seatsByEvent->setQuantity($quantity);
                $seatsByEvent->setRemnants($quantity);
                $seatsByEvent->setPrice($price);
    
                try{
                    $this->seatsByEventDao->Add($seatsByEvent);
                }catch (Exception $ex){
                    echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
                    $this->index();
                }
            //}

            echo "<script> alert('Plaza/s Evento agregada/s exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar la plaza evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->index();
    }

    public function seatsByEventList()
    {
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementList.php";
    }

    public function deleteSeatsByEvent($id)
    {
        $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent);

        try{
            $this->seatsByEventDao->Delete($seatsByEvent);
            echo "<script> alert('Asiento por Evento eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el Asiento por Evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->seatsByEventList();
    }

    /**
     * Recieve id of SeatsByEvent to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editSeatsByEvent
     */
    public function viewEditSeatsByEvent($idSeatsByEvent)
    {   
        $oldSeatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent);

        require VIEWS_PATH.$this->folder."SeatsByEventManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object SeatsByEvent
     * and old object by id, call dao update
     */
    public function editSeatsByEvent($oldIdSeatsByEvent, $seatsByEvent)
    {
        $oldSeatsByEvent = $this->seatsByEventDao->getById($oldIdSeatsByEvent);
        $newSeatsByEvent = new SeatsByEvent();

        $args = func_get_args();
        $seatsByEventAttributeList = array_combine(array_keys($newSeatsByEvent->getAll()),array_values($args)); 

        foreach ($seatsByEventAttributeList as $attribute => $value) {
            $newSeatsByEvent->__set($attribute,$value);
        }

        try{
            $this->seatsByEventDao->Update($oldSeatsByEvent, $newSeatsByEvent);
            echo "<script> alert('Calendario modificada exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el calendario " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->seatsByEventList();
    }

}
