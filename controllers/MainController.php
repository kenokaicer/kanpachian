<?php
	namespace Controllers;

	use Dao\BD\EventsDao as EventsDao;
	
	class MainController{
		
		private $eventsDao;

		public function __construct()
		{
			$this->eventsDao = EventsDao::getInstance();
		}
		
		function index()
		{	
			try{
				$eventList = $this->eventsDao->RetrieveEventsOnly();
			}catch(Exception $ex){
				echo "<script> alert('Error al intentar listar Eventos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
			}
			var_dump($eventList);
			require "Views/home.php";
		}
	}
?>
