<?php namespace controllers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 Class IntegrantesController
 {
     
     public function agregarIntegrante($datos)
     {
         print("AGREGANDO Integrante");
         var_dump($datos);
         if(!empty($datos))
         \dao\integrantesDao::agregar($datos);
         else
             errorController::get()->valoresVacios();
         
     }
     
      public function editarintegrante($datos)
     {
         var_dump($datos);
         if(!empty($datos))
         \dao\integrantesDao::editar($datos);
         else
             errorController::get()->valoresVacios();
         
     }
         public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new integrantesController();
        }
        return $inst;
    }
     
     public static function mostrarTodos()
     {
         $dao = new \dao\integrantesDao();
         $todos = $dao->todos();
         return $todos;
     }
     
     
 }
 
