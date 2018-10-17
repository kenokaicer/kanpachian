<?php

namespace Controllers;

use Models\Theater as Theater;
use Dao\BD\TheatersDao as TheatersDao;

class TheaterManagementController
{
    private $theatersDao;

    public function __construct()
    {
        $this->theatersDao = TheatersDao::getInstance(); //BD
    }

    public function index()
    { //agregar validaciones aca (ej userLogged)

        require ROOT."Views/TheaterManagement.php";
    }

    public function viewAddTheater()
    {
        $seatTypeList = $_SESSION["seatTypes"];
        require ROOT."Views/TheaterManagementAdd.php";
    }

    public function addTheater($name, $location, $maxCapacity)
    {

        $theater = $_SESSION["seatTypesForTheater"];
        $theater->setName($name)->setLocation($location)->setMaxCapacity($maxCapacity);

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
            $theaterList = $this->theatersDao->RetrieveAll();
        }catch (Exception $ex) {
            echo "<script> alert('No se pudo listar los teatros. " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }

        
        var_dump($theaterList);
        foreach ($theaterList as $key => $value) {
            var_dump($value->getSeatTypes());
        }
        $this->index();
        //require ROOT."Views/TheaterManagementList.php";
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
        require ROOT."Views/TheaterManagementEdit.php";
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