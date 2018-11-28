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
					echo "<script>swal({
						title:'WARNING: Your PHP version is".phpversion()."!', 
						text:'The site expects a PHP version 7.2 or higher!', 
						icon:'warning'
						});</script>";
				}

				$eventList = $this->eventDao->getAll();
			}catch(Exception $ex){
				echo "<script>swal({
					title:'Error al cargar lista de eventos!', 
					text:'" . str_replace(array("\r","\n","'"), "", $ex->getMessage()) . "', 
					icon:'error'
					});</script>";
			}
			require VIEWS_PATH."home.php";
		}

		function notFound()
		{
			require VIEWS_PATH."404.php";
		}
	}
?>
