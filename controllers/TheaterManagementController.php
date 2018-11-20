<?php

namespace Controllers;

use Models\Theater as Theater;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\SeatTypeDao as SeatTypeDao;
use Models\File as File;
use Cross\Session as Session;
use Exception as Exception;

class TheaterManagementController
{
    private $theaterDao;
    private $seatTypeDao; 
    private $folder = "Management/Theater/";

    public function __construct()
    {
        Session::adminLogged();
        $this->theaterDao = new TheaterDao();
        $this->seatTypeDao = new SeatTypeDao();
    }

    public function index()
    { 
        require VIEWS_PATH.$this->folder."TheaterManagement.php";
    }

    public function viewAddTheater()
    {
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('Error al listar tipos de asiento: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        require VIEWS_PATH.$this->folder."TheaterManagementAdd.php";
    }

    public function addTheater($theaterName, $location, $address, $maxCapacity, $idSeatTypeList)
    {    
        try{
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
                $filePath = File::upload($file);
            } else {
            $filePath = null;
            echo "<script> alert('Advertencia, imagen no ingresada');</script>";
            }
        }catch (Exception $ex) {
            echo "<script> alert('Error al subir imágen: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        try{
            $theater = new Theater();

            $args = func_get_args();
            array_unshift($args, null); //put null at first of array for id
            array_pop($args); //take out serialized list

            $idSeatTypeList = json_decode($idSeatTypeList);

            foreach ($idSeatTypeList as $idSeatType) {
                $seatType = $this->seatTypeDao->getById($idSeatType);
                $theater->addSeatType($seatType);
            }

            array_push($args, $filePath); //push image

            $theaterAttributeList = array_combine(array_keys($theater->getAll()),array_values($args));

            foreach ($theaterAttributeList as $attribute => $value) {
                $theater->__set($attribute,$value);
            }

            $this->theaterDao->Add($theater);
            echo "<script> alert('Teatro agregado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el teatro. " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function theaterList()
    {
        try{
            $theaterList = $this->theaterDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo listar los teatros. " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."TheaterManagementList.php";
    }

    public function deleteTheater($id)
    {
        $theater = $this->theaterDao->getById($id);

        try{
            $this->theaterDao->Delete($theater);
            echo "<script> alert('Teatro eliminado exitosamente');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        } 

        $this->theaterList();
    }

    public function viewEditTheater($idTheater)
    {   
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
            $theater = $this->theaterDao->getById($idTheater);
        }catch (Exception $ex) {
            echo "<script> alert('" . str_replace("'","",$ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH.$this->folder."TheaterManagementEdit.php";
    }

    public function editTheater($oldIdTheater, $theaterName, $location, $address, $maxCapacity, $idSeatTypeList)
    {
        $oldTheater = $this->theaterDao->getById($oldIdTheater);

        try{
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
                $filePath = File::upload($file);
            } else {
                $filePath = null;
            }
        }catch (Exception $ex) {
            echo "<script> alert('Error al subir imágen: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
        
        try{
            $theater = new Theater();

            $args = func_get_args();
            array_unshift($args, null); //put null at first of array for id
            array_shift($args); //take out oldIdTheater
            array_pop($args); //take out serialized list

            $idSeatTypeList = json_decode($idSeatTypeList);

            foreach ($idSeatTypeList as $idSeatType) {
                $seatType = $this->seatTypeDao->getById($idSeatType);
                $theater->addSeatType($seatType);
            }

            array_push($args, $filePath); //push image

            $theaterAttributeList = array_combine(array_keys($theater->getAll()),array_values($args));

            foreach ($theaterAttributeList as $attribute => $value) {
                $theater->__set($attribute,$value);
            }

            $this->theaterDao->Update($oldTheater, $theater);
        }catch (Exceptionaaaa $ex){
            echo "<script> alert('No se pudo modificar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        $this->theaterList();
    }
}