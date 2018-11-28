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
            echo "<script>swal({
                title:'Error al cargar lista de eventos!', 
                text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
                icon:'error'
                });</script>";
        }

        require VIEWS_PATH."SalesByEvent.php";
    }
}