<?php namespace controllers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 Class equiposController
 {
     public function decirHola()
{

  print("HOLA");
}
     public function agregarEquipo($datos)
     {
         if(!empty($datos))
         \dao\equiposDao::agregarEquipo($datos);
         else
             errorController::get()->valoresVacios();
         
     }
         public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new equiposController();
        }
        return $inst;
    }
     
     public static function mostrarTodos()
     {
         $dao = new \dao\equiposDao();
         $todos = $dao->todos();
         return $todos;
     }
     
     
 }
 
