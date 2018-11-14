<?php
	namespace Controllers;

	use Dao\BD\EventDao as EventDao;
	use Cross\Session as Session;
	
	class HomeController{
		
		private $eventsDao;

		public function __construct()
		{
			$this->eventDao = new EventDao();
		}
		
		function index()
		{	
			//Session::printAll();
			require VIEWS_PATH."home.php";
		}


		public function getEventList()
		{
			try{ 
				$eventList = $this->eventDao->getAll();
			}catch(Exception $ex){
				echo "<script> alert('Error al intentar listar Eventos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
			}

			return $eventList;
		}
		
		public function test()
		{
			require VIEWS_PATH."Home2.php";
		}
		public function ticket()
		{
			require VIEWS_PATH."ticket.php";
		}
	}
?>
