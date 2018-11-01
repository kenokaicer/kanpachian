<?php
require_once "config/Config.php";
require_once "config/Autoload.php";

use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Exception as Exception;
use Models\Role as Role;
use Models\Artist as Artist;
use Models\User as User;
use Models\Client as Client;

Autoload::start();

//var_dump(Artist::getAttributes());

/*
try{
    $var = SeatsByEventDao::getInstance()->getByEventByDateID(1);
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
