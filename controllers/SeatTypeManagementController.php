<?php
namespace Controllers;

use Dao\BD\SeatTypeDao as SeatTypeDao;
use Models\SeatType as SeatType;
use Exception as Exception;
use Cross\Session as Session;

class SeatTypeManagementController
{
    private $seatTypeDao;
    private $folder = "Management/SeatType/";

    public function __construct()
    {
        Session::adminLogged();
        $this->seatTypeDao = new SeatTypeDao();
    }

    public function index()
    { 
        require VIEWS_PATH.$this->folder."SeatTypeManagement.php";
    }

    public function viewAddSeatType()
    {
        require VIEWS_PATH.$this->folder."SeatTypeManagementAdd.php";
    }

    public function addSeatType($name, $description)
    {
        $seatType = new SeatType();
        
        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        
        $seatTypeAttributeList = array_combine(array_keys($seatType->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
        
        foreach ($seatTypeAttributeList as $attribute => $value) {
            $seatType->__set($attribute,$value);
        }

        try{
            $this->seatTypeDao->Add($seatType);
            echo "<script> alert('Tipo de asiento agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el tipo de asiento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function seatTypeList()
    {
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Tipo de asientos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."SeatTypeManagementList.php";
    }

    public function deleteSeatType($idSeatType)
    {
        $seatType = $this->seatTypeDao->getById($idSeatType);

        try{
            $this->seatTypeDao->Delete($seatType);
            echo "<script> alert('Tipo de asiento eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el tipo de asiento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->seatTypeList();
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
            echo "<script> alert('No se pudo modificar el tipo de asiento " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."SeatTypeManagementEdit.php";
    }

    /**
     * Recieve modified attributes for object SeatType
     * and old object by id, call dao update
     */
    public function editSeatType($oldIdSeatType, $name, $description)
    {
        $oldSeatType = $this->seatTypeDao->getById($oldIdSeatType);
        $newSeatType = new SeatType();

        $args = func_get_args();
        $seatTypeAttributeList = array_combine(array_keys($newSeatType->getAll()),array_values($args)); 

        foreach ($seatTypeAttributeList as $attribute => $value) {
            $newSeatType->__set($attribute,$value);
        }

        try{
            $this->seatTypeDao->Update($oldSeatType, $newSeatType);
            echo "<script> alert('Tipo de asiento modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el tipo de asiento " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->seatTypeList();
    }

}
