<?php
namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Models\PurchaseLine as PurchaseLine;
use Exception as Exception;
use Cross\Session as Session;

class CartController
{
    private $Dao;

    public function __construct()
    {
        $this->seatsByEventDao = new SeatsByEventDao();
    }

    public function index()
    {
        Session::userLogged();

        if(isset($_SESSION["vitualCart"])){
            $purchaseLines = $_SESSION["vitualCart"];
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
                    header("location:".FRONT_ROOT."Home/index");
                }
                
                header("location:".FRONT_ROOT."Account/index");
            }

            if(isset($_SESSION["lastLocation"]))
            {
                $idSeatsByEvent = $_SESSION["lastLocation"];
                Session::remove("lastLocation");
            }

            if(isset($idSeatsByEvent)){
                Session::virtualCartCheck();

                $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent); //full load
                
                if($seatsByEvent > 0){ //Check already done in EventByDate view
                    $purchaseLine = new PruchaseLine();

                    $purchaseLine->setPrice($seatsByEvent->getPrice());
                    $purchaseLine->setSeatsByEvent($seatsByEvent);
    
                    $array = $_SESSION["vitualCart"];
                    array_push($array, $purchaseLine);
                    $_SESSION["vitualCart"] = $array;
                }else{
                    echo "<script> alert('No hay asientos disponibles'<script>;";
                }

                $this->index();
            }else{
                throw new Exception("idSeatsByEvent not set");
                header("location:".FRONT_ROOT."Home/index");
            }

        }catch(Exception $ex){
            echo "<script> alert('Error al agregar linea de compra. ".$ex->getMessage()."'<script>;";
            header("location:".FRONT_ROOT."Home/index");
        } 
    }  

    public function removePurchaseLine(){

    }
}