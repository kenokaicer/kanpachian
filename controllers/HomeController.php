<?php
	namespace Controllers;

	use Dao\BD\EventDao as EventDao;
	use Cross\Session as Session;
	
	class HomeController{
		
		private $eventsDao;

		public function __construct()
		{
			$this->eventDao = EventDao::getInstance();
		}
		
		function index()
		{	
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
			require VIEWS_PATH."/old/login-box.php";
		}
	}
?>
