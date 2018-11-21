<?php
	namespace Controllers;

	use Dao\BD\EventDao as EventDao;
	use Cross\Session as Session;
	
	class HomeController{
		
		private $eventsDao;
		private $categoryDao;

		public function __construct()
		{
			$this->eventDao = new EventDao();
		}
		
		function index()
		{	
			//Session::printAll();
			try{ 
				if(isset($_SESSION["userLogged"]) && $_SESSION["userLogged"]->getRole()=="Admin"){ //if admin, stay out of the rest of the site
					echo "<script>window.location.replace('".FRONT_ROOT."Admin/index');</script>";
					exit;
				}

				if(phpversion() < "7.2"){
					echo "<script>alert('WARNING: Your PHP version is".phpversion().", site expects a PHP version 7.2 or higher');</script>";
				}

				$eventList = $this->eventDao->getAll();
			}catch(Exception $ex){
				echo "<script> alert('Error al intentar listar Eventos: " . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "');</script>";
			}
			require VIEWS_PATH."home.php";
		}	
	}
?>
