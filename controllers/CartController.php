<?php
namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Models\PurchaseLine as PurchaseLine;
use Exception as Exception;
use Cross\Session as Session;

class CartController
{
    private $seatsByEventDao;

    public function __construct()
    {
        $this->seatsByEventDao = new SeatsByEventDao();
    }

    public function index()
    {
        Session::userLogged();

        if(isset($_SESSION["virtualCart"])){
            $purchaseLines = $_SESSION["virtualCart"];
        }else{
            $purchaseLines = array();
        }

        require VIEWS_PATH."Cart.php";
    }

    public function addPurchaseLine($idSeatsByEvent=null)
    {
        try{
            if(!isset($_SESSION["userLogged"]))
            {   
                if(isset($idSeatsByEvent)){
                    Session::add("lastLocation", $idSeatsByEvent);
                }else{
                    throw new Exception("idSeatsByEvent not set");
                }
                header("location:".FRONT_ROOT."Account/index");
                exit;
            }

            if(isset($_SESSION["lastLocation"]))
            {
                $idSeatsByEvent = $_SESSION["lastLocation"];
                Session::remove("lastLocation");
            }

            if(isset($idSeatsByEvent)){
                Session::virtualCartCheck();

                $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent, "lazy");
                
                if($seatsByEvent->getRemnants() > 0){ //Check already done in EventByDate view
                    $purchaseLine = new PurchaseLine();

                    $purchaseLine->setPrice($seatsByEvent->getPrice());
                    $purchaseLine->setSeatsByEvent($seatsByEvent);

                    $array = $_SESSION["virtualCart"];
                    array_push($array, $purchaseLine);

                    $_SESSION["virtualCart"] = $array;
                }else{
                    echo "<script> alert('No hay asientos disponibles'<script>;";
                }

                $this->index();
            }else{
                throw new Exception("idSeatsByEvent not set");
            }

        }catch(Exception $ex){
            echo "<script> alert('Error al agregar linea de compra. ".$ex->getMessage()."'); 
                window.location.replace('".FRONT_ROOT."Home/index');
            </script>";
            exit;
        } 

    }  

    public function removePurchaseLine($indexPurchaseLine)
    {
        try{
            if(isset($_SESSION["virtualCart"])){
                $purchaseLines = $_SESSION["virtualCart"];
            }else{
                throw new Exception (__METHOD__."VirtualCart not set");
            }

            array_splice($purchaseLines,$indexPurchaseLine, 1);

            $_SESSION["virtualCart"] = $purchaseLines;
        }catch (Exception $ex){
            echo "<script> alert('No se pudo borrar el item del carrito. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        $this->index();
    }
}