<?php namespace dao;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class equiposDao
{
      private $tabla='equipos';
      
      public function __construct() {
          
      }
      public  function todos() {
          $todos = $this->traerTodos();
          return $todos;
      }
      public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new equiposDao();
        }
        return $inst;
    }
    
    public function traerTodos() {

			// Guardo como string la consulta sql
			$sql = "SELECT * FROM " . $this->tabla;


			// creo el objeto conexion
			$obj_pdo = new Conexion();


			// Conecto a la base de datos.
			$conexion = $obj_pdo->conectar();


			// Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
			$sentencia = $conexion->prepare($sql);


			// Ejecuto la sentencia.
			$sentencia->execute();

                        //Obtiene la siguiente fila de un conjunto de resultados
			while ($row = $sentencia->fetch()) {
				$array[] = $row;
			}
			if(!empty($array))
                            return $array;
		}

    public function agregarEquipo($equipo)
        { 
        $sql = "INSERT INTO equipos (name) VALUES (?)";
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $equipo['name'], \PDO::PARAM_STR);
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