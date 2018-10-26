<?php namespace controllers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of errorController
 *
 * @author Gon
 */
Class errorController
{
    private $message ='';
    private static $instancia;
    
    public function getErrorMessage()
    {
        return $this->message;
    }
    
    public function cleanMessage()
    {
        $this->message = '';
    }
    
    public static function get()
   {
      if (self::$instancia == null)
      {
         self::$instancia = new self;
      }
      return self::$instancia;
   }
    public function __construct() {
        $this->message = 'default error';
    }
                
   public  function showView()
    {
         require_once ROOT . 'views/error.php';
    }
    public  function usuarioIncorrecto()
    {
        $this->message = 'Usuario o contraseña incorrectos';
        $this->showView();  
    }
     public  function valoresVacios()
    {
        $this->message = 'No se pueden ingresar campos vacios';
        $this->showView();  
    }
    public  function showExceptionError($exception)
    {
        $this->message = $exception;
        $this->showView();
    }
    
}
