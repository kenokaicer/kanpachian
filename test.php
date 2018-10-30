<?php
require_once "config/Config.php";
require_once "config/Autoload.php";

use Config\Autoload as Autoload;
use Dao\BD\EventByDateDao as EventByDateDao;
use Exception as Exception;
use Models\Role as Role;

Autoload::start();

/*
try{
    $var = EventByDateDao::getInstance()->getByID(1);
}catch(Exception $ex){
    echo $ex->getMessage();
}


var_dump($var);
*/

$var = Role::getConstants();

var_dump($var);

foreach ($var as $key => $value) {
    echo key($value);
}
