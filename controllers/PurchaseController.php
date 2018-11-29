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
use Models\Mail as Mail;
use Cross\Session as Session;
use Exception as Exception;
use PHPMailer\PHPMailer\Exception as PHPMailerExcpetion;

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
        
            $eventByDateList = $this->eventByDateDao->getByEventId($idEvent, LoadType::Lazy1);
            $theaterArray = array();

            foreach ($eventByDateList as $eventByDate) { //get a list of all theater of eventByDate
                $theaterArray[] = $eventByDate->getTheater();
            }

            $theaterArray =  array_unique($theaterArray, SORT_REGULAR); //leave only a list of note repeated theaters
        }catch (Exception $ex){
            require VIEWS_PATH."Redirect.php";
            echo "<script>swal({title:'No se pudo cargar el evento!', 
                text:'". str_replace(array("\r","\n","'"), "", $ex->getMessage()) ."', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
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
            echo "<script>swal('Error al listar por categoría!', '". str_replace(array("\r","\n","'"), "", $ex->getMessage()) ."', 'error');</script>";
        }
    }

    public function searchByArtist($artistString)
    {
        try{
            $artistList = $this->artistDao->getByNameOrAndLastname($artistString); //using BD like

            if(empty($artistList)){
                echo "<script>swal({
                            title:'No hay coincidencias!', 
                            text:'No se encontró ninguna artista que conincida con los términos de buesqueda', 
                            icon:'warning'}).then(
                            function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
                exit;
            }else if(sizeof($artistList) > 1){
                require VIEWS_PATH."artistSearch.php";
            }else{
                $artist = reset($artistList);
                $this->showEventByDatesByArtist($artist->getIdArtist());
            }
        }catch (Exception $ex){
            echo "<script>swal({
                title:'Error al cargar artista!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }
    }

    /**
     * Result of search by date
     */
    public function showEventByDate($date)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getAllByDate($date);

            if(empty($eventByDateList))
            {
                echo "<script>swal({
                    title:'No hay calendarios para esa fecha', 
                    text:' ', 
                    icon:'warning'}).then(
                    function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
            }
            
            setlocale(LC_TIME, array("ES","esl","spa"));
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar calendarios!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        require VIEWS_PATH."EventByDateByDate.php";
    }

    public function showEventByDatesByArtist($idArtist)
    {
        try{
            $eventByDateList = $this->eventByDateDao->getAllByArtist($idArtist);
            $artist = $this->artistDao->getById($idArtist);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar calendarios!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        require VIEWS_PATH."EventByDateByArtist.php";
    }


    public function showEventByDates($idEvent, $idTheater)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar evento!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, LoadType::Lazy1);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar teatro!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        try{
            $eventByDateList = $this->eventByDateDao->getByEventIdAndTheaterIdLazy($idEvent, $idTheater); //no loadType in this one
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar fechas!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        require VIEWS_PATH."EventByDate.php";
    }

    public function showSeatsByEvent($idEvent, $idTheater, $idEventByDate)
    {
        try{
            $event = $this->eventDao->getById($idEvent);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar evento!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        try{
            $theater = $this->theaterDao->getById($idTheater, LoadType::Lazy1);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar teatro!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }

        try{
            $seatsByEventList = $this->seatsByEventDao->getByEventByDateId($idEventByDate);
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar el evento por fecha!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
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
            $alert["title"] = "No se pudo generar la compra";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->showCart($alert);
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
            $ticketCodeList = array(); //array used to send tickets to email

            foreach ($purchaseLines as $purchaseLine) {
                //store PurchaseLine
                $idPurchaseLine = $this->purchaseLineDao->add($purchaseLine, $idPurchase);

                //store Ticket
                $ticket = new Ticket();

                $ticket->setTicketCode(uniqid());
                $ticket->setQrCode(FRONT_ROOT."Account/viewTicket?ticketCode=".$ticket->getTicketCode()); //set qrCode with ticket code
                $ticketCodeList[] = $ticket->getQrCode(); //set ticket address in array for email
                $purchaseLine->setIdPurchaseLine($idPurchaseLine);
                $ticket->setPurchaseLine($purchaseLine);

                $this->ticketDao->add($ticket);
                
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

            //Empty cart

            $_SESSION["virtualCart"] = array();

            //Send Email

            $email = $_SESSION["userLogged"]->getEmail();
            $client = $this->clientDao->getByUserId($idUser);
            (new Mail)->send($email, $client, $purchase->getDate(), $ticketCodeList);

            $this->showTickets($idPurchase);
        }catch (PHPMailerExcpetion $ex){
            $alert["title"] = "Email no pudo ser enviado, pero la compra se realizó con éxito";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->viewCart($alert);
        }
        catch (Exception $ex){
            $alert["title"] = "Hubo un problema al cerrar la compra";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
            $this->viewCart($alert);
        }
    }

    public function viewCart($alert = array())
    {
        if(!empty($alert)){
            echo "<script>swal({
                title: '".@$alert["title"]."!',
                text: '".@$alert["text"]."!',
                icon: '".@$alert["icon"]."',
              });</script>";
        }

        try{
            Session::userLogged();
            Session::virtualCartCheck();

            $purchaseLines = $_SESSION["virtualCart"];
        }catch (Exception $ex){
            echo "<script>swal({title:'No se pudo cargar el carrito!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
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
                        echo "<script> swal('No hay asientos disponibles!', '!', 'warning');<script>;";
                    }
                }else{
                    throw new Exception("SeatsByEvent null");
                }
                $this->viewCart();
            }else{
                throw new Exception("idSeatsByEvent not set");
            }
        }catch(Exception $ex){
            echo "<script>swal({title:'Error al agregar linea de compra!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
            exit;
        } 
    }  

    public function removePurchaseLine($indexPurchaseLine)
    {
        $alert = array();

        try{
            if(isset($_SESSION["virtualCart"])){
                $purchaseLines = $_SESSION["virtualCart"];
            }else{
                throw new Exception (__METHOD__."VirtualCart not set");
            }

            array_splice($purchaseLines,$indexPurchaseLine, 1);

            $_SESSION["virtualCart"] = $purchaseLines;
        }catch (Exception $ex){
            $alert["title"] = "Error al borrar item del carrito";
            $alert["text"] = str_replace(array("\r","\n","'"), "", $ex->getMessage());
            $alert["icon"] = "error";
        }

        $this->viewCart($alert);
    }

    public function showTickets($idPurchase)
    {   
        Session::userLogged();

        try{
            $ticketList = $this->ticketDao->getAllByPurchase($idPurchase);
            setlocale(LC_TIME, array("ES","esl","spa")); //set locale of time to spanish, array tries each code until it gets a success
        }catch (Exception $ex){
            echo "<script>swal({title:'Error al cargar tickets!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'}).then(
                function(){window.location.href = '".FRONT_ROOT."Home/index';});</script>";
        }
        
        require VIEWS_PATH."ticket.php";
    }
}