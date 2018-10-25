<?php

namespace Controllers;

use Models\Theater as Theater;
use Dao\BD\TheatersDao as TheatersDao;
use Dao\BD\SeatTypesDao as SeatTypesDao;

class TheaterManagementController
{
    private $theatersDao;

    public function __construct()
    {
        $this->theatersDao = TheatersDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require VIEWS_PATH."TheaterManagement.php";
    }

    public function viewAddTheater()
    {
        $seatTypeList = SeatTypesDao::getInstance()->getAll();
        require VIEWS_PATH."TheaterManagementAdd.php";
    }

    public function addTheater($name, $location, $image="", $maxCapacity, $seatTypeList="")
    {    
        $theater = new Theater();
        //$theater = $_SESSION["seatTypesForTheater"]; //seatTypeList no longer by session, should be passed by post

        $args = func_get_args();
        array_unshift($args, null); //put null at first of array for id
        array_pop($args); //take out serialized list

        ///----
        //deserialize list
        ///-----

        array_push($args, $seatTypeList); //$seatTypeList should be an object array now

        $theaterAtributeList = array_combine(array_keys($theater->getAll()),array_values($args));

        foreach ($theaterAtributeList as $atribute => $value) {
            $theater->__set($atribute,$value);
        }

        try{
            $this->theatersDao->Add($theater);
            echo "<script> alert('Teatro agregado exitosamente');</script>";
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el teatro. " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }
        
        $this->index();
    }

    public function theaterList()
    {
        try{
            $theaterList = $this->theatersDao->getAll();
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo listar los teatros. " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }

        
        var_dump($theaterList);
        foreach ($theaterList as $key => $value) {
            var_dump($value->getSeatTypes());
        }
        $this->index();//temp
        //require VIEWS_PATH."TheaterManagementList.php";
    }

    public function deleteTheater($id)
    {
        $this->theatersDao->Delete($id);
        $this->TheaterList();
    }

    public function viewEditTheater($id, $name, $location, $maxCapacity)
    {   
        $oldTheater = new Theater();
        $oldTheater->setIdTheater($id)->setName($name)->setLocation($location)->setMaxCapacity($maxCapacity);
        $_SESSION["oldTheater"] = $oldTheater;
        require VIEWS_PATH."TheaterManagementEdit.php";
    }

    public function editTheater($name, $location, $maxCapacity)
    {
        if(isset($_SESSION["oldTheater"])){
            $oldTheater = $_SESSION["oldTheater"];
        }else{
            echo "<script>alert('Error al editar, [Session for old object not set]');</script>";
            $this->theaterList();
        }
        $newTheater = new Theater();

        $newTheater->setName($name)->setLocation($location)->setMaxCapacity($maxCapacity);

        $this->theatersDao->Update($oldTheater, $newTheater);
        unset($_SESSION["oldTheater"]);
        $this->theaterList();
    }
}