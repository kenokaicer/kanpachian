<?php
require_once "config/Config.php";
require_once "config/Autoload.php";

use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\TicketDao as TicketDao;
use Dao\BD\LoadType as LoadType;
use Dao\BD\PurchaseDao as PurchaseDao;
use Exception as Exception;
use Models\Role as Role;
use Models\Artist as Artist;
use Models\User as User;
use Models\Client as Client;
use Models\SeatType as SeatType;
use Models\SeatsByEvent as SeatsByEvent;
use Models\Ticket as Ticket;
use chillerlan\QRCode\QRCode as QRCode;
use Models\Mail as Mail;

Autoload::start();


/*
$dao = new TicketDao();
$ticket = $dao->getById(6);
$oldTicket = clone $ticket;
$ticket->setQrCode(FRONT_ROOT."Account/viewTicket?idTicket=2");
var_dump($ticket);
var_dump($oldTicket);
$dao->Update($oldTicket,$ticket);
$ticket = $dao->getById(6);
var_dump($ticket);3
*/


$dao = new PurchaseDao();

try{
    $list = $dao->getAllByDate(date("Y-m-d"));

    var_dump($list);
    
}catch(Exception $ex){
    echo $ex->getMessage();
}


/*QR code test
$data = "texto lala";
echo '<img src="'.(new QRCode)->render($data).'" />';
*/

/*
session_start();

$var = array();
$_SESSION["a"] = $var;
var_dump($_SESSION["a"]);
echo isset($_SESSION["a"]);
*/

//var_dump($list[2]->getSeatTypes());

/*
$c = new Client();
$c->setUser(null);
////header("location:".FRONT_ROOT."Home/Index");
*/
/*
$hasedPass = password_hash(1234, PASSWORD_DEFAULT);

echo password_verify(1234, $hasedPass);
*/
/*
try{
    $var = ArtistDao::getInstance()->getById(8);
}catch(Exception $ex){
    echo "<script> alert('Error: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
}


var_dump($var);

if(empty($var))
    echo "true";
else {
    echo "false";
}
*/
/*
try{
    $var = SeatsByEventDao::getInstance()->getByEventByDateId(1);
}catch(Exception $ex){
    echo $ex->getMessage();
}

var_dump($var);
*/
/*
try{
    $var = EventByDateDao::getInstance()->getAll();
}catch(Exception $ex){
    echo $ex->getMessage();
}

var_dump($var);
*/

/*
$var = Role::getConstants();

var_dump($var);

foreach ($var as $key => $value) {
    echo key($value);
}
*/

