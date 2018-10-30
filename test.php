<?php
require_once "config/Config.php";
require_once "config/Autoload.php";

use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Exception as Exception;

Autoload::start();


try{
    $var = EventByDateDao::getInstance()->getByID(1);
}catch(Exception $ex){
    echo $ex->getMessage();
}


var_dump($var);
