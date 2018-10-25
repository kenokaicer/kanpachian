<?php
	namespace Controllers;
	use Dao\BD\TheaterDao as TheaterDao;
	
	class AdminController{
		
		function index()
		{	
			require "Views/admin.php";
		}
	}
?>
