<?php namespace Controllers\Ajax; 

require_once "../../config/Config.php";
require_once "../../config/Autoload.php";
use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
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

    if($func == "getByEventId"){
        try{
            $eventByDateDao = new EventByDateDao();
            
            $eventByDateList = $eventByDateDao->getByEventId($var);

            $eventByDateListInArray = array();

            foreach ($eventByDateList as $eventByDate) {
                $array = array();

                $array["idEventByDate"] = $eventByDate->getIdEventByDate();
                $array["date"] = $eventByDate->getDate();
                $array["endPromoDate"] = $eventByDate->getEndPromoDate();
                $array["isSale"] = $eventByDate->getIsSale();
                $array["theaterName"] = $eventByDate->getTheater()->getTheaterName();

                $artistList = $eventByDate->getArtists();
                $stringArtistas = "";
                foreach ($artistList as $artist) {
                    $stringArtistas .= $artist->getName()." ".$artist->getLastname().", "; 
                }
                $stringArtistas = rtrim($stringArtistas, ", ");

                $array["artistList"] = $stringArtistas;

                array_push($eventByDateListInArray, $array);
            }

            echo json_encode($eventByDateListInArray);
        }catch (Exception $ex){
            //echo "<script> alert('No se pudo cargar los calendarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            //I think this alert won't work here, how to pass an error in ajax?
            echo $ex->getMessage();
        }
    }
}


?>