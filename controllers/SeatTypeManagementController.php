<?php
namespace Controllers;

use Dao\BD\SeatTypeDao as SeatTypeDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Models\SeatType as SeatType;
use Exception as Exception;
use Cross\Session as Session;

class SeatTypeManagementController
{
    private $seatTypeDao;
    private $seatsByEventDao;
    private $folder = "Management/SeatType/";

    public function __construct()
    {
        Session::adminLogged();
        $this->seatTypeDao = new SeatTypeDao();
        $this->seatsByEventDao = new SeatsByEventDao();
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

        require VIEWS_PATH.$this->folder."SeatTypeManagement.php";
    }

    public function viewAddSeatType()
    {
        require VIEWS_PATH.$this->folder."SeatTypeManagementAdd.php";
    }

    public function addSeatType($name, $description)
    {
        try{
            if(is_null($this->seatTypeDao->getBySeatTypeName($name)))
            {
                $seatType = new SeatType();
                
                $args = func_get_args();
                array_unshift($args, null); //put null at first of array for id
                
                $seatTypeAttributeList = array_combine(array_keys($seatType->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
                
                foreach ($seatTypeAttributeList as $attribute => $value) {
                    $seatType->__set($attribute,$value);
                }

                $this->seatTypeDao->Add($seatType);

                $alert["title"] = "Tipo de asiento agregado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Tipo de asiento ya existente en el sistema";
                $alert["icon"] = "warning";
            }
            }catch (Exception $ex){
                $alert["title"] = "Error al agregar Artista";
                $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
                $alert["icon"] = "error";
        }
        
        $this->index($index);
    }

    public function seatTypeList($alert = array())
    {
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar Tipo de asientos";
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
        
        require VIEWS_PATH.$this->folder."SeatTypeManagementList.php";
    }

    public function deleteSeatType($idSeatType)
    {   
        try{
            if(empty($this->seatsByEventDao->getAllPastNowBySeatType($idSeatType)))
            {
                $seatType = $this->seatTypeDao->getById($idSeatType);

                $this->seatTypeDao->Delete($seatType);

                $alert["title"] = "Tipo de asiento eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "No se puede eliminar el tipo de asiento";
                $alert["text"] = "Ya es usado en Calendarios aún no expirados";
                $alert["icon"] = "warning";
            }
            
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el tipo de asiento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->seatTypeList($alert);
    }

    /**
     * Recieve id of SeatType to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editSeatType
     */
    public function viewEditSeatType($idSeatType)
    {   
        try{
            $oldSeatType = $this->seatTypeDao->getById($idSeatType);
        }catch (Exception $ex) {
            $alert["title"] = "Error al cargar Tipo de asiento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->seatTypeList($alert);
        }

        require VIEWS_PATH.$this->folder."SeatTypeManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object SeatType
     * and old object by id, call dao update
     */
    public function editSeatType($oldIdSeatType, $name, $description)
    {
        try{
            if(is_null($this->seatTypeDao->getBySeatTypeName($name)))
            {
                $oldSeatType = $this->seatTypeDao->getById($oldIdSeatType);
                $newSeatType = new SeatType();

                $args = func_get_args();
                $seatTypeAttributeList = array_combine(array_keys($newSeatType->getAll()),array_values($args)); 

                foreach ($seatTypeAttributeList as $attribute => $value) {
                    $newSeatType->__set($attribute,$value);
                }

                $this->seatTypeDao->Update($oldSeatType, $newSeatType);

                $alert["title"] = "Tipo de asiento modificado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "Tipo de asiento ya existente en el sistema";
                $alert["icon"] = "warning";
            }
        }catch (Exception $ex) {
            $alert["title"] = "Error al modificar el tipo de asiento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->seatTypeList($alert);
    }

}
