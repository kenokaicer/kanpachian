<?php
	namespace Controllers;

	use Dao\BD\SeatTypesDao as SeatTypesDao;
	
	class AdminController{

		public function __construct()
		{
			if(!isset($_SESSION["seatTypes"]))
				$_SESSION["seatTypes"] = SeatTypesDao::getInstance()->getAll();
		}
		
		function index()
		{
			require "Views/admin.php";
		}
	}
?>
