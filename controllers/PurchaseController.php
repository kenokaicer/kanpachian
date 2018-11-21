<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\ClientDao as ClientDao;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\PurchaseDao as PurchaseDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\PurchaseLineDao as PurchaseLineDao;
use Dao\BD\TicketDao as TicketDao;
use Dao\BD\LoadType as LoadType;
use Models\Purchase as Purchase;
use Models\PurchaseLine as PurchaseLine;
use Models\Ticket as Ticket;
use Cross\Session as Session;
use Exception as Exception;

class PurchaseController
{
    private $eventDao;
    private $eventByDateDao;
    private $seatsByEventDao;
    private $clientDao;
    private $theaterDao;
    private $artistDao;
    private $purchaseDao;
    private $purchaseLineDao;
    private $ticketDao;

    public function __construct()
    {
        $this->eventDao = new EventDao();
        $this->eventByDateDao = new EventByDateDao();
        $this->seatsByEventDao = new SeatsByEventDao();
        $this->clientDao = new ClientDao();
        $this->theaterDao = new TheaterDao();
        $this->artistDao = new ArtistDao();
        $this->purchaseDao = new PurchaseDao();
        $this->purchaseLineDao = new PurchaseLineDao();
        $this->ticketDao = new TicketDao();
    }

    public function index($idEvent = null)
    { 
        try{
            if(is_null($idEvent))
            {
                echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
                exit;
            }
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        try{
            $eventByDateList = $this->eventByDateDao->getByEventId($idEvent, LoadType::Lazy1);
            $theaterArray = array();

            foreach ($eventByDateList as $eventByDate) { //get a list of all theater of eventByDate
                $theaterArray[] = $eventByDate->getTheater();
            }

            $theaterArray =  array_unique($theaterArray, SORT_REGULAR); //leave only a list of note repeated theaters
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        require VIEWS_PATH."Event.php";
    }

    /**
     * Returns array with events by category, used in navbar
     * categories as key
     */
    public static function getCategoryList()
    {
        try{
            $eventList = (new EventDao)->getAll();
            $eventsByCategory = array();
            
            /**
             * Create an array with categories as key and events as value
             */
            foreach ($eventList as $event) {
                if(!array_key_exists($event->getCategory()->getCategoryName(),$eventsByCategory)) //check if a category is already set in the array
                {
                    $eventsByCategory[$event->getCategory()->getCategoryName()] = array(); //if it's no, set that position in the array as an array
                }
                array_push($eventsByCategory[$event->getCategory()->getCategoryName()], $event); //push event into it's category
            }
            
            return $eventsByCategory;
        }catch(Exception $ex){
            echo "<script> alert('Error al listar por categoría: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }
    }

    public function searchByArtist($artistString)
    {
        try{
            $artistList = $this->artistDao->getByNameOrAndLastname($artistString); //using BD like

            if(empty($artistList)){
                echo "<script> alert('No hay coincidencias'); 
                window.location.replace('".FRONT_ROOT."Home/index');
                </script>";
                exit;
            }else if(sizeof($artistList) > 1){
                require VIEWS_PATH."artistSearch.php";
            }else{
                $artist = reset($artistList);
                $this->showEventByDatesByArtist($artist->getIdArtist());
            }
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el artista. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }
    }

    public function showEventByDatesByArtist($idArtist)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getAllByArtist($idArtist);
            $artist = $this->artistDao->getById($idArtist);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar los calendarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        require VIEWS_PATH."EventByDateByArtist.php";
    }


    public function showEventByDates($idEvent, $idTheater)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, LoadType::Lazy1);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        try{
            $eventByDateList = $this->eventByDateDao->getByEventIdAndTheaterIdLazy($idEvent, $idTheater); //no loadType in this one
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar las fechas. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        require VIEWS_PATH."EventByDate.php";
    }

    public function showSeatsByEvent($idEvent, $idTheater, $idEventByDate)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, LoadType::Lazy1);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el teatro. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate);
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el evento por fecha. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }
        require VIEWS_PATH."SeatsByEvent.php";
    }

    /**
     * Shows view with purchase data
     * checks if theres a creditCard asosiated with client
     */
    public function confirmPurchase()
    {
        try{
            Session::userLogged();
            Session::virtualCartCheck();
            
            //Check if there are PurchaseLines
            //There is a check in the view already
            if(empty($_SESSION["virtualCart"])){ 
                echo "<script>window.location.replace('".FRONT_ROOT."Cart/index');</script>";
                exit;
            }

            $idUser = $_SESSION["userLogged"]->getIdUser();
            $client = $this->clientDao->getByUserId($idUser);

            if(is_null($client->getCreditCard())){
                echo "<script>window.location.replace('".FRONT_ROOT."Account/viewRegisterCreditCard');</script>";
                exit;
            }

            $total = 0;
            foreach ($_SESSION["virtualCart"] as $purchaseLine) {
                $total += $purchaseLine->getPrice();
            }

            $creditCardLast4Digits = substr($client->getCreditCard()->getCreditCardNumber(), -4);

            require VIEWS_PATH."confirmPurchase.php";
        }catch (Exception $ex){
            echo "<script> alert('No se pudo generar la compra. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            $this->showCart();
        }
    }

    /**
     * store purchase, purchaseLines, and tickets
     */
    public function completePurchase()
    {
        Session::userLogged();
        Session::virtualCartCheck();

        try{
            $purchaseLines = $_SESSION["virtualCart"];

            if(empty($purchaseLines)){
                throw new Exception("purchaseLines array empty");
            }

            $purchase = new Purchase();

            $total = 0;
            foreach ($purchaseLines as $purchaseLine) {
                $total += $purchaseLine->getPrice();
            }

            $purchase->setTotalPrice($total);
            $purchase->setDate(date("Y/m/d-h:i:sa")); // set current date and time, ex: 2018/11/08-01:48:31am (timestamp)

            $idUser = $_SESSION["userLogged"]->getIdUser();
            $client = $this->clientDao->getByUserId($idUser);

            $purchase->setClient($client);
            $purchase->setPurchaseLines($purchaseLines);

            $idPurchase = $this->purchaseDao->add($purchase); //store purchase

            foreach ($purchaseLines as $purchaseLine) {
                //store PurchaseLine
                $idPurchaseLine = $this->purchaseLineDao->add($purchaseLine, $idPurchase);

                //store Ticket
                $ticket = new Ticket();

                $ticket->setTicketCode(uniqid());
                $purchaseLine->setIdPurchaseLine($idPurchaseLine);
                $ticket->setPurchaseLine($purchaseLine);

                $idTicket = $this->ticketDao->add($ticket); //add ticket returning id
                $ticket->setIdTicket($idTicket);
                $oldTicket = clone $ticket;
                $ticket->setQrCode(FRONT_ROOT."Account/viewTicket?idTicket=".$idTicket); //set qrCode with ticket id

                $this->ticketDao->update($oldTicket, $ticket); //update ticket with qrCode

                //deduct form remnants in seats by event 
                //this could have been done with a dao method

                    //This is needed if more than one ticket is bought from the same eventByDate
                    $oldSeatsByEvent = $this->seatsByEventDao->getById($purchaseLine->getSeatsByEvent()->getidSeatsByEvent(), LoadType::Lazy3); //minimal load
                    $newSeatsByEvent = clone $oldSeatsByEvent;
                    
                $remnants = $newSeatsByEvent->getRemnants();
                $remnants--;
                $remnants = (string)$remnants;
                $newSeatsByEvent->setRemnants($remnants);
  
                $this->seatsByEventDao->update($purchaseLine->getSeatsByEvent(),$newSeatsByEvent);
            }

            //empty cart

            $_SESSION["virtualCart"] = array();

            $this->showTickets($idPurchase);
        }catch (Exception $ex){
            echo "<script> alert('Hubo un problema al cerrar la compra. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            $this->viewCart();
        }
    }

    public function viewCart()
    {
        try{
            Session::userLogged();
            Session::virtualCartCheck();

            $purchaseLines = $_SESSION["virtualCart"];
        }catch (Exception $ex){
            echo "<script> alert('No se pudo cargar el carrito. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";        
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }

        require VIEWS_PATH."Cart.php";
    }

    /**
     * Checks if user is logged, if it's not asks to login, redirecting back to this method
     */
    public function addPurchaseLine($quantity=null, $idSeatsByEvent=null)
    {
        try{
            if(!isset($_SESSION["userLogged"])) //if not logged
            {   
                if(isset($idSeatsByEvent)){ //store data for redirect
                    $arr = array();
                    $arr[] = $idSeatsByEvent;
                    $arr[] = $quantity;
                    Session::add("lastLocation", $arr);
                }else{
                    throw new Exception("idSeatsByEvent not set");
                }
                echo "<script>window.location.replace('".FRONT_ROOT."Account/index');</script>";
                exit;
            }

            if(isset($_SESSION["lastLocation"])) //if comming from redirect set stored data
            {
                $arr = $_SESSION["lastLocation"];
                Session::remove("lastLocation");
                $idSeatsByEvent = $arr[0];
                $quantity = $arr[1];
            }

            if(isset($idSeatsByEvent)){ //check for something that could have been wrong in the redirect
                Session::virtualCartCheck();

                $seatsByEvent = $this->seatsByEventDao->getById($idSeatsByEvent, LoadType::Lazy1);
                
                if(!is_null($seatsByEvent)) //this should never be null
                {
                    //Check there's availability of seats 
                    //Check already done in EventByDate view
                    if($seatsByEvent->getRemnants() > $quantity){ 
                        
                        $purchaseLine = new PurchaseLine();

                        $purchaseLine->setPrice($seatsByEvent->getPrice());
                        $purchaseLine->setSeatsByEvent($seatsByEvent);

                        $array = $_SESSION["virtualCart"];

                        for ($i=0; $i < $quantity; $i++) { 
                            array_push($array, clone $purchaseLine);
                        }

                        $_SESSION["virtualCart"] = $array;
                       
                    }else{
                        echo "<script> alert('No hay asientos disponibles'<script>;";
                    }
                }else{
                    throw new Exception("SeatsByEvent null");
                }
                $this->viewCart();
            }else{
                throw new Exception("idSeatsByEvent not set");
            }
        }catch(Exception $ex){
            echo "<script> alert('Error al agregar linea de compra. ".$ex->getMessage()."');</script>"; 
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
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

    public function showTickets($idPurchase)
    {   
        Session::userLogged();

        try{
            $ticketList = $this->ticketDao->getAllByPurchase($idPurchase);
            setlocale(LC_TIME, array("ES","esl","spa")); //set locale of time to spanish, array tries each code until it gets a success
        }catch (Exception $ex){
            echo "<script> alert('Error getting tickets. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            echo "<script>window.location.replace('".FRONT_ROOT."Home/index');</script>";
        }
        
        require VIEWS_PATH."ticket.php";
    }
}