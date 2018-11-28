<?php
namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
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
    private $purchaseLineDao;
    private $folder = "Management/SeatsByEvent/";

    public function __construct()
    {
        Session::adminLogged();
        $this->seatsByEventDao = new SeatsByEventDao();
        $this->eventDao = new eventDao();
        $this->seatTypeDao = new SeatTypeDao();
        $this->eventByDateDao = new EventByDateDao();
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

        require VIEWS_PATH.$this->folder."SeatsByEventManagement.php";
    }

    public function viewAddSeatsByEvent()
    {   
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            $alert["title"] = "No se pude cargar datos necesarios";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementAdd.php";
    }

    /**
     * Now only adding one seat at a time
     */
    public function addSeatsByEvent($eventByDateId, $idSeatTypeList, $quantityList, $priceList)
    {
        /**
         * get already inserted SeatsByEvent
         * this isn't necessary if loading all at the same time.
         * Still used after list adaptation for checking purposes
         */
        try{ 
            $seatTypesAlreadyAdded = $this->seatsByEventDao->getIdSeatTypesByEventByDate($eventByDateId);
        }catch (Exception $ex){
            $alert["title"] = "Error al cargar plazas evento ya insertadas";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }

        try{
            $eventByDate = $this->eventByDateDao->getById($eventByDateId);
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar la plaza evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
        }
        
        try{
            //Deserialize recieved lists
            $idSeatTypeList = json_decode($idSeatTypeList);
            $quantityList = json_decode($quantityList);
            $priceList = json_decode($priceList);
            $i = 0;

            foreach ($idSeatTypeList as $idSeatType) {
                try{
                    $seatType = $this->seatTypeDao->getById($idSeatType);
                }catch (Exception $ex){
                    throw new Exception("Error al buscar tipo de asiento");
                }

                $exists = false;

                foreach ($seatTypesAlreadyAdded as $value) {
                    if ($value == $seatType->getIdSeatType()){
                        $exists = true;

                        echo "<script>swal({
                            title:'Advertencia!', 
                            text:'Al menos una de las plazas ya estaba insertada y no se insertó de vuelta', 
                            icon:'warning'
                            });</script>";
                    }
                }
                
                if(!$exists){
                    $seatsByEvent = new SeatsByEvent();
    
                    $seatsByEvent->setEventByDate($eventByDate);
                    $seatsByEvent->setSeatType($seatType);
                    $seatsByEvent->setQuantity($quantityList[$i]);
                    $seatsByEvent->setRemnants($quantityList[$i]);
                    $seatsByEvent->setPrice($priceList[$i]);
        
                    $this->seatsByEventDao->Add($seatsByEvent);
                }

                $i++;
            }

            $alert["title"] = "Plaza/s Evento agregada/s exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex){
            $alert["title"] = "Error al agregar la plaza evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->index($alert);
    }

    public function seatsByEventList($alert = array())
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
        
        require VIEWS_PATH.$this->folder."SeatsByEventManagementList.php";
    }

    public function deleteSeatsByEvent($idSeatsByEvent)
    {
        try{
            if(empty($this->purchaseLineDao->getAllPastNowBySeatsByEvent($idSeatsByEvent))){
                $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent);

                $this->seatsByEventDao->Delete($seatsByEvent);

                $alert["title"] = "Asiento por Evento eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "No se permite el borrado";
                $alert["text"] = "Existen tickets vendidos futuros para este asiento";
                $alert["icon"] = "warning";
            }
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el Asiento por Evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->seatsByEventList($alert);
    }

    /**
     * Recieve id of SeatsByEvent to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editSeatsByEvent
     */
    public function viewEditSeatsByEvent($eventName, $theaterData, $idSeatsByEvent)
    {   
        try{
            $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent);
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el asiento por evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->seatsByEventList($alert);
        }

        require VIEWS_PATH.$this->folder."SeatsByEventManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object SeatsByEvent
     * and old object by id, call dao update
     */
    public function editSeatsByEvent($oldIdSeatsByEvent, $quantity, $price, $remnants)
    {
        try{
            $oldSeatsByEvent = $this->seatsByEventDao->getById($oldIdSeatsByEvent);
            $newSeatsByEvent = clone $oldSeatsByEvent;

            $newSeatsByEvent->setQuantity($quantity);
            $newSeatsByEvent->setPrice($price);
            $newSeatsByEvent->setRemnants($remnants);

            $this->seatsByEventDao->Update($oldSeatsByEvent, $newSeatsByEvent);

            $alert["title"] = "Asiento por evento modificado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el asiento por evento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->seatsByEventList($alert);
    }

}
