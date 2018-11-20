<?php
	namespace Controllers;
	
	use Cross\Session as Session;

	class AdminController{

		public function __construct()
		{
			Session::adminLogged();
		}
		
		public function index()
		{	
			require "Views/admin.php";
		}
	}
?>
