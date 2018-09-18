<?php //namespace dao;

require_once('Conexion.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class pedidosDao
{
      private $tabla='wp19_posts';
      
      public function __construct() {
          
      }
      public  function todos() {
          $todos = $this->traerTodos();
          return $todos;
      }

      public static function get() {

        static $inst = null;
        if ($inst === null) {
            $inst = new pedidosDao();
        }
        return $inst;
    }
    
    

        public function traerTodosPost() {
        print("buscando posts en el servidorZ \n");
            // Guardo como string la consulta sql
           // $sql = "SELECT * FROM " . 'wp19_posts' . " WHERE POST_TYPE='product'";//'POST'
          $sql="SELECT * FROM wp19_posts WHERE post_type='producto'";


            // creo el objeto conexion
            $obj_pdo = new Conexion();
            $conexion = $obj_pdo->conectar();

            $sentencia = $conexion->prepare($sql);

      
            // Ejecuto la sentencia.
            
            
            try 
            {
                print("ejecutando sentencia "+ $sentencia);
                $sentencia->execute();

            } 
            catch (Exception $e) 
            {
             echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
      

            while ($row = $sentencia->fetch()) {
                $array[] = $row;
            }
            
            print(" Llego del servidor :" );
            var_dump($array);
            if(!empty($array))
             return $array;
        }

    public function agregarEquipo($equipo)
        { 
        $sql = "INSERT INTO equipos (nombre) VALUES (?)";
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $equipo['nombre'], \PDO::PARAM_STR);
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