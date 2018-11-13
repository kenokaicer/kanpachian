<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\ClientDao as ClientDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\PurchaseDao as PurchaseDao;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Models\Purchase as Purchase;
use Models\PurchaseLine as PurchaseLine;
use Cross\Session as Session;
use Exception as Exception;

class PurchaseController //migracion de cart controller acá y dejar de usar cart controller
{
    private $eventDao;
    private $categoryDao;
    private $clientDao;
    private $seatsByEventDao;
    private $purchaseDao;
    private $purchaseLineDao;

    public function __construct()
    {
        $this->eventDao = new EventDao();
        $this->eventByDate = new EventByDateDao();
        $this->seatsByEventDao = new SeatsByEventDao();
        $this->clientDao = new ClientDao();
        $this->theaterDao = new TheaterDao();
    }

    public function index($idEvent)
    { 
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $eventByDateList = $this->eventByDate->getByEventIdLazy($idEvent);
            $theaterArray = array();

            foreach ($eventByDateList as $eventByDate) { //get a list of all theater of eventByDate
                $theaterArray[] = $eventByDate->getTheater();
            }

            $theaterArray =  array_unique($theaterArray, SORT_REGULAR); //leave only a list of note repeated theaters
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        require VIEWS_PATH."Event.php";
    }

    public function showEventByDates($idEvent, $idTheater){
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, "lazy");
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $eventByDateList = $this->eventByDate->getByEventIdAndTheaterIdLazy($idEvent, $idTheater);  
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar las fechas. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        require VIEWS_PATH."EventByDate.php";
    }

    public function showSeatsByEvent($idEvent, $idTheater, $idEventByDate)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, "lazy");
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento por fecha. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
        require VIEWS_PATH."SeatsByEvent.php";
    }

    public function confirmPurchase()
    {
        try{
            Session::userLogged();
            Session::virtualCartCheck();
            
            if(empty($_SESSION["virtualCart"])){ //There's a check in the view already
                header("location:".FRONT_ROOT."Cart/index");
                exit;
            }

            $idUser = $_SESSION["userLogged"]->getIdUser();
            $client = $this->clientDao->getByUserId($idUser);

            if(is_null($client->getCreditCard())){
                header("location:".FRONT_ROOT."Account/viewRegisterCreditCard");
                exit;
            }
//view to confirm sale, client and card data, and a total price
            require VIEWS_PATH.".php";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo generar la compra. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            $this->showCart();
        }
    }

    public function completePurchase()
    {
        $purchaseLines = $_SESSION["virtualCart"];

        $purchase = new Purchase();

        $purchase->setDate(date("Y/m/d-h:i:sa")); // set current date and time, ex: 2018/11/08-01:48:31am (timestamp)
        $purchase->setClient($client);
        $purchase->setPurchaseLines($purchaseLines);
        
        //print tickets and store them

        //$this->purchaseDao() //store purchase

        //deduct form remnants in seats by event
    }

    public function viewCart()
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

                $this->viewCart();
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

        $this->viewCart();
    }
}