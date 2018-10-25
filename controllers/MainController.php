<?php
	namespace Controllers;

	use Dao\BD\EventDao as EventDao;
	
	class MainController{
		
		private $eventsDao;

		public function __construct()
		{
			$this->eventDao = EventDao::getInstance();
		}
		
		function index()
		{	
			try{ 
				$eventList = $this->eventDao->RetrieveEventsOnly();
			}catch(Exception $ex){
				echo "<script> alert('Error al intentar listar Eventos: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
			}
			var_dump($eventList); //this is for the dyinamic event slot in the main view
			require "Views/home.php";
		}
	}
?>
