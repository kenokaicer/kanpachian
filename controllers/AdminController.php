<?php
	namespace Controllers;
	use Dao\BD\TheatersDao as TheatersDao;
	
	class AdminController{
		
		function index()
		{	
			var_dump(TheatersDao::getInstance()->getByID(1));
			require "Views/admin.php";
		}
	}
?>
