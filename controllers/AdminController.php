<?php
	namespace Controllers;
	use Dao\BD\TheatersDao as TheatersDao;
	
	class AdminController{
		
		function index()
		{	
			require "Views/admin.php";
		}
	}
?>
