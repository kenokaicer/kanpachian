<?php
namespace Controllers;

use Dao\BD\SeatTypesDao as SeatTypesDao;
use Models\SeatType as SeatType;
use Exception as Exception;

class SeatTypeManagementController
{
    protected $message;
    private $seatTypesDao;
    private $folder = "SeatTypeManagement/";

    public function __construct()
    {
        $this->seatTypesDao = SeatTypesDao::getInstance();
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

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
        
        $seatTypeAtributeList = array_combine(array_keys($seatType->getAll()),array_values($args));  //get an array with atribues from object and another with function parameters, then combine it
        
        foreach ($seatTypeAtributeList as $atribute => $value) {
            $seatType->__set($atribute,$value);
        }

        try{
            $this->seatTypesDao->Add($seatType);
            echo "<script> alert('Tipo de asiento agregado exitosamente');</script>";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo agregar el tipo de asiento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function seatTypeList()
    {
        try{
            $seatTypeList = $this->seatTypesDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Tipo de asientos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."SeatTypeManagementList.php";
    }

    public function deleteSeatType($id)
    {
        $seatType = $this->seatTypesDao->getByID($idSeatType);

        try{
            $this->seatTypesDao->Delete($seatType);
            echo "<script> alert('Tipo de asiento eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el tipo de asiento. " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } 

        $this->seatTypeList();
    }

    /**
     * Recieve id of SeatType to edit, retrieve by DAO for diplaying in the forms,
     * then after the modifications sends them to this->editSeatType
     */
    public function viewEditSeatType($idSeatType)
    {   
        $oldSeatType = $this->seatTypesDao->getByID($idSeatType);

        require VIEWS_PATH.$this->folder."SeatTypeManagementEdit.php";
    }

    /**
     * Recieve modified atributes for object SeatType
     * and old object by id, call dao update
     */
    public function editSeatType($oldIdSeatType, $name, $description)
    {
        $oldSeatType = $this->seatTypesDao->getByID($oldIdSeatType);
        $newSeatType = new SeatType();

        $args = func_get_args();
        $seatTypeAtributeList = array_combine(array_keys($newSeatType->getAll()),array_values($args)); 

        foreach ($seatTypeAtributeList as $atribute => $value) {
            $newSeatType->__set($atribute,$value);
        }

        try{
            $this->seatTypesDao->Update($oldSeatType, $newSeatType);
            echo "<script> alert('Tipo de asiento modificado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el tipo de asiento " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }

        $this->seatTypeList();
    }

}
