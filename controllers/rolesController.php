<?php namespace controllers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 Class rolesController
 {
     
     public function agregarRol($datos)
     {
         print("AGREGANDO ROL");
         var_dump($datos);
         if(!empty($datos))
         \dao\rolesDao::agregarEquipo($datos);
         else
             errorController::get()->valoresVacios();
         
     }
         public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new rolesController();
        }
        return $inst;
    }
     
     public static function mostrarTodos()
     {
         $dao = new \dao\rolesDao();
         $todos = $dao->todos();
         return $todos;
     }
     
     
 }
 
