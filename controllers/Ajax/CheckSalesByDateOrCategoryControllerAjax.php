<?php namespace Controllers\Ajax; 

require_once "../../config/Config.php";
require_once "../../config/Autoload.php";
use Config\Autoload as Autoload;
use Dao\BD\PurchaseDao as PurchaseDao;
use Dao\BD\LoadType as LoadType;

Autoload::start();
session_start();

if(isset($_SESSION["userLogged"]) && $_SESSION["userLogged"]->getRole() == "Admin")
{
    if(isset($_POST['function'])){
        $func = $_POST['function'];
    }
    else {
        //error
        echo "error, function not set";
    }
    
    if(isset($_POST['value'])){
        $var = $_POST['value'];
    }
    else {
        //error
        echo "error, value not set";
    }
    
    /**
     * Returns all event dates, with all its seatTypes
     */
    if($func == "getTotalByDate"){
        try{
            $purchaseDao = new PurchaseDao();

            $purchaseList = $purchaseDao->getAll(LoadType::Lazy1);
    
            $total = 0;
    
            foreach ($purchaseList as $purchase) {
                if($purchase->getDate() == $var)
                $total += $purchase->getTotalPrice();
            }
    
            echo json_encode($total);
        }catch (Exception $ex){
            //echo "<script> alert('No se pudo cargar los calendarios. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
            //I think this alert won't work here, how to pass an error in ajax?
            echo $ex->getMessage();
        }
    }
}


?>