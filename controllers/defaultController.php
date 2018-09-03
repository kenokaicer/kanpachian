<?php namespace controllers;
use config;
Class defaultController extends baseController
{
    protected $vista;
    public function __construct() {
    }

    public  function showView() {   
        require_once ROOT . 'views/home.php';
    }
     public  function closeView() { 
        require_once ROOT . 'views/close.php';
    }
     public  function registroCompletado() { 
        require_once ROOT . 'views/exito.php';
    }

   
}