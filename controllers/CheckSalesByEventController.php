<?php
namespace Controllers;

use Dao\BD\EventDao as EventDao;
use Exception as Exception;
use Cross\Session as Session;

class CheckSalesByEventController
{   
    private $eventDao;

    public function __construct()
    {
        Session::adminLogged();
        $this->eventDao = new EventDao();
    }
    
    public function index()
    {	
        try{
            $eventList = $this->eventDao->getAll();
        }catch (Exception $ex){
            echo "<script> alert('Error al cargar lista de eventos. " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
        }

        require VIEWS_PATH."SalesByEvent.php";
    }
}