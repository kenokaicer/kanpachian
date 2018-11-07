<?php //namespace Controllers\Ajax; //Move this file to that folder when done testing, the idea is having one for every controller that uses ajax
//file name SeatsByEventManagementAjax.php

require_once "config/Config.php";
require_once "config/Autoload.php";
use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
Autoload::start();

if(isset($_POST['function'])){
    $func = $_POST['function'];
}
else {
    //error
    echo "error, function not set";
}

if(isset($_POST['value'])){
    $var = $_POST['value'];
}
else {
    //error
    echo "error, value not set";
}

if ($func == "test"){
    $var = array(array("test1","test2"),array("test3","test4"));

    echo json_encode($var);
}

if($func == "getByEventId"){
    try{
        $eventByDateDao = new EventByDateDao();
        
        $eventByDateList = $eventByDateDao->getByEventId($var);

        $eventByDateListInArray = array();

        foreach ($eventByDateList as $eventByDate) {
            $array = array();

            $array["idEventByDate"] = $eventByDate->getIdEventByDate();
            $array["theaterName"] = $eventByDate->getTheater()->getTheaterName();
            $array["date"] = $eventByDate->getDate();

            array_push($eventByDateListInArray, $array);
        }

        echo json_encode($eventByDateListInArray);
    }catch (Exception $ex){
        //echo "<script> alert('No se pudo cargar los calendarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        //I think this alert won't work here, how to pass an error in ajax?
        echo $ex->getMessage();
    }
}

/**
 * Return only not setted SeatTypes
 */
if($func == "getSeatTypes"){
    try{
        $eventByDateDao = new EventByDateDao();
        $seatsByEventDao = new SeatsByEventDao();
        
        $theater = $eventByDateDao->getTeatherByEventByDateId($var);
        $seatTypes = $theater->getSeatTypes();

        $existingSeatTypes = $seatsByEventDao->getIdSeatTypesByEventByDate($var);

        $seatTypesArray = array();
        
        foreach ($seatTypes as $seatType) {
            $exist = false;
            foreach ($existingSeatTypes as $existingSeatType) { //check if SeatType is already loaded
                if($existingSeatType == $seatType->getIdSeatType()){
                    $exist = true;                   
                }
            }

            if(!$exist){
                $array = array();

                $array["idSeatType"] = $seatType->getIdSeatType();
                $array["seatTypeName"] = $seatType->getSeatTypeName();

                array_push($seatTypesArray, $array);
            }
        }

        echo json_encode($seatTypesArray);
    }catch (Exception $ex){
        echo $ex->getMessage();
    }
}

?>