<?php namespace dao;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class rolesDao
{
      private $tabla='roles';
      
      public function __construct() {
          
      }
      public  function todos() {
          $todos = $this->traerTodos();
          return $todos;
      }
      public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new rolesDao();
        }
        return $inst;
    }
    
    public function traerTodos() {
        $sql = "SELECT * FROM " . $this->tabla;
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        while ($row = $sentencia->fetch()) {
            $array[] = $row;
        }
        if (!empty($array))
            return $array;
    }

    public function agregarEquipo($rol)
        { 
        $sql = "INSERT INTO roles (nombre) VALUES (?)";
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $rol['nombre'], \PDO::PARAM_STR);
        try {
            $sentencia->execute();
        } catch (PDOException $e) {
            echo "DataBase Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
             return;
        } catch (Exception $e) {
            echo "General Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            return;
        }
             if($sentencia == true)
                 \controllers\defaultController::registroCompletado();
             
             
        }
}