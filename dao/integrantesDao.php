<?php namespace dao;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class integrantesDao
{
      private $tabla='integrantes';
      
      public function __construct() {
          
      }
      public  function todos() {
          $todos = $this->traerTodos();
          return $todos;
      }
      public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new integrantesDao();
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

    public function editar($datos)
    {
         $sql = "UPDATE integrantes SET nombre=? , dni=? ,fk_rol=,fk_equipo=? WHERE id = " .$datos['id'];
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $datos['nombre'], \PDO::PARAM_STR);
        $sentencia->bindParam(2, $datos['dni'], \PDO::PARAM_STR);
        $sentencia->bindParam(3, $datos['rol'], \PDO::PARAM_INT);
        $sentencia->bindParam(4, $datos['equipo'], \PDO::PARAM_INT);
        try {
            $sentencia->execute();
        } catch (PDOException $e) {
            echo "DataBase Error.<br>" . $e->getMessage();
             return;
        } catch (Exception $e) {
            echo "General Error.<br>" . $e->getMessage();
            return;
        }
             if($sentencia == true)
                 \controllers\defaultController::registroCompletado();
             
    }
    public function agregar($datos)
        { 
        $sql = "INSERT INTO integrantes (nombre,dni,fk_rol,fk_equipo) VALUES (?,?,?,?)";
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $datos['nombre'], \PDO::PARAM_STR);
        $sentencia->bindParam(2, $datos['dni'], \PDO::PARAM_STR);
        $sentencia->bindParam(3, $datos['rol'], \PDO::PARAM_INT);
        $sentencia->bindParam(4, $datos['equipo'], \PDO::PARAM_INT);
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