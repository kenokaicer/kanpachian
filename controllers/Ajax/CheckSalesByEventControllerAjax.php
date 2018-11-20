<?php namespace Controllers\Ajax; 

require_once "../../config/Config.php";
require_once "../../config/Autoload.php";
use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\LoadType as LoadType;

Autoload::start();
session_start();

if(isset($_SESSION["userLogged"]) && $_SESSION["userLogged"]->getRole() == "Admin")
{
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
    
    /**
     * Returns all event dates, with all its seatTypes
     */
    if($func == "getByEventId"){
        try{
            $eventByDateDao = new EventByDateDao();
            $seatsByEventDao = new SeatsByEventDao();
            
            $eventByDateList = $eventByDateDao->getByEventId($var);
    
            $seatsByEventInArray = array();
    
            foreach ($eventByDateList as $eventByDate) {
                $array = array();
                $seatsByEventList = $seatsByEventDao->getByEventByDateId($eventByDate->getIdEventByDate());
    
                foreach ($seatsByEventList as $seatsByEvent) {
                    $array["theaterName"] = $eventByDate->getTheater()->getTheaterName();
                    $array["date"] = $eventByDate->getDate();
                    $array["seatType"] = $seatsByEvent->getSeatType()->getSeatTypeName();
                    $array["sold"] = $seatsByEvent->getQuantity() - $seatsByEvent->getRemnants();
                    $array["remnants"] = $seatsByEvent->getRemnants();
                    array_push($seatsByEventInArray, $array);
                }     
            }
    
            echo json_encode($seatsByEventInArray);
        }catch (Exception $ex){
            //echo "<script> alert('No se pudo cargar los calendarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            //I think this alert won't work here, how to pass an error in ajax?
            echo $ex->getMessage();
        }
    }
}


?>