<?php

namespace Controllers;

use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\SeatTypeDao as SeatTypeDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Models\Theater as Theater;
use Models\File as File;
use Cross\Session as Session;
use Exception as Exception;

class TheaterManagementController
{
    private $theaterDao;
    private $seatTypeDao; 
    private $seatsByEventDao;
    private $folder = "Management/Theater/";

    public function __construct()
    {
        Session::adminLogged();
        $this->theaterDao = new TheaterDao();
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

        require VIEWS_PATH.$this->folder."TheaterManagement.php";
    }

    public function viewAddTheater()
    {
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar tipos de asiento";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->index($alert);
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

            echo "<script>swal({
                title:'Advertencia, imagen no ingresada!', 
                text:'El teatro se cargó, pero deberá ingresar una imágen en el futuro', 
                icon:'error'
                });</script>";
            }
        }catch (Exception $ex) {
            echo "<script>swal({
                title:'Error al subir imágen!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
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

            $alert["title"] = "Teatro agregado exitosamente";
            $alert["icon"] = "success";
        }catch (Exception $ex) {
            $alert["title"] = "Error al agregar Teatro";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        $this->index($alert);
    }

    public function theaterList($alert = array())
    {
        try{
            $theaterList = $this->theaterDao->getAll();
            
        }catch (Exception $ex) {
            $alert["title"] = "Error al listar los teatros";
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

        require VIEWS_PATH.$this->folder."TheaterManagementList.php";
    }

    public function deleteTheater($idTheater)
    {
        try{
            if(empty($this->seatsByEventDao->getAllPastNowByTheater($idTheater))){
                $theater = $this->theaterDao->getById($idTheater);

                $this->theaterDao->Delete($theater);

                $alert["title"] = "Teatro eliminado exitosamente";
                $alert["icon"] = "success";
            }else{
                $alert["title"] = "No se puede eliminar el teatro";
                $alert["text"] = "Ya que hay calendarios no expirados en dicho teatro";
                $alert["icon"] = "warning";
            }
            
        } catch (Exception $ex) {
            $alert["title"] = "Error al eliminar el teatro";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        } 

        $this->theaterList($alert);
    }

    public function viewEditTheater($idTheater)
    {   
        try{
            $seatTypeList = $this->seatTypeDao->getAll();
            $theater = $this->theaterDao->getById($idTheater);
        }catch (Exception $ex) {
            $alert["title"] = "Error al cargar datos necesarios";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->theaterList($alert);
        }

        require VIEWS_PATH.$this->folder."TheaterManagementEdit.php";
    }

    public function editTheater($oldIdTheater, $theaterName, $location, $address, $maxCapacity, $idSeatTypeList)
    {
        try{
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
                $filePath = File::upload($file);
            } else {
                $filePath = null;
            }
        }catch (Exception $ex) {
            $alert["title"] = "Error al subir imágen";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }
        
        try{
            $oldTheater = $this->theaterDao->getById($oldIdTheater);

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
            $alert["title"] = "Error al modificar el teatro";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->theaterList($alert);
    }
}