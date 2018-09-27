<?php //namespace dao;

require_once('Conexion.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class pedidosDao
{
      private $tabla='wp_posts';
      
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

        public function traerTodosPost() {

            // Guardo como string la consulta sql
            $sql = "SELECT * FROM " . $this->tabla; //. " WHERE post_type='post'";
         // $sql="SELECT * FROM wp_posts WHERE post_type='post'";


            // creo el objeto conexion
            $obj_pdo = new Conexion();


            // Conecto a la base de datos.
            $conexion = $obj_pdo->conectar();


            // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
            $sentencia = $conexion->prepare($sql);


            // Ejecuto la sentencia.
            $sentencia->execute();

            //Obtiene la siguiente fila de un conjunto de resultados
            while ($row = $sentencia->fetch()) 
            {
                $array[] = $row;
            }

           // var_dump($array);

            if(!empty($array))
            return $array;
        }



    public function traerTodosTest()
    {


            // Guardo como string la consulta sql
            $sql = "SELECT * FROM " . $this->tabla; //. " WHERE post_type='post'";
         // $sql="SELECT * FROM wp_posts WHERE post_type='post'";


            // creo el objeto conexion
            $obj_pdo = new Conexion();


            // Conecto a la base de datos.
            $conexion = $obj_pdo->conectar();


            // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
            $sentencia = $conexion->prepare($sql);


            // Ejecuto la sentencia.
            $sentencia->execute();

            //Obtiene la siguiente fila de un conjunto de resultados
            while ($row = $sentencia->fetch()) 
            {
                $array[] = $row;
            }

           var_dump($array);

            if(!empty($array))
            return $array;
        }
    
    public function crearPedido()
        { 
       // $sql = "INSERT INTO Artistas VALUES (?)";
        $sql = "INSERT INTO Artistas (Nombre,Apellido) VALUES ('Robie','Williams');";
        $obj_pdo = new Conexion();
        $pdoConexion = $obj_pdo->conectar();
        $sentencia = $pdoConexion->prepare($sql);
       // $sentencia->bindParam(1, $equipo['nombre'], \PDO::PARAM_STR);
        try {
            $sentencia->execute();
        } catch (PDOException $e) 
        {
            echo "DataBase Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
             return;
        } catch (Exception $e) {
            echo "General Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            return;
        }
            // if($sentencia == true)
              //   \controllers\defaultController::registroCompletado();
                
        }
}