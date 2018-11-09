<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\ClientDao as ClientDao;
use Dao\BD\PurchaseDao as PurchaseDao;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Models\Purchase as Purchase;
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
    }

    public function index($idEvent)
    { //agregar validaciones aca (ej userLogged)
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $eventByDateList = $this->eventByDate->getByEventIdLazy($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        require VIEWS_PATH."Event.php";
    }

    public function showSeatsByEvent($idEvent, $idEventByDate)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }

        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento por fecha. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
        require VIEWS_PATH."EventByDate.php";
    }

    public function completePurchase()
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

            $purchaseLines = $_SESSION["virtualCart"];

            $purchase = new Purchase();

            $purchase->setDate(date("Y/m/d-h:i:sa")); // set current date and time, ex: 2018/11/08-01:48:31am
            $purchase->setClient($client);
            $purchase->setPurchaseLines($purchaseLines);
            
            //print tickets and store them

            //$this->purchaseDao() //store purchase

        }catch (Exception $ex){
            echo "<script> alert('No se pudo generar la compra. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
        }
    }
}