<?php namespace Controllers;

use Dao\BD\SeatsByEventDao as SeatsByEventDao;
use Dao\BD\SeatTypeDao as SeatTypeDao;
use Dao\BD\EventByDateDao as EventByDateDao;
use Dao\BD\EventDao as EventDao;
use Models\SeatsByEvent as SeatsByEvent;

use Exception as Exception;

	class EventDetailsController
	{
		private $folder = "";


		 public function index()
	    { 
	        require VIEWS_PATH . "demo.php";
	    }
	}

?>