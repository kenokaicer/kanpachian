<?php namespace dao;

	use Dao\Conexion as Conexion;

	class baseDao extends Conexion {

		protected $table = "";
                public function __construct($tableName) {
                    $this->table = $tableName;
                }
                
                public function getTableName()
                {
                    return $this->table;
                }
                public function setTableName($name)
                {
                    $this->table = $name;
                }
                
                public function add($value) 
                        {
                            // Guardo como string la consulta sql utilizando como values, marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales los valores reales serán sustituidos cuando la sentencia sea ejecutada
                            $sql = "INSERT INTO " . $this->tabla . " (nombre) VALUES (:nombre)";

                            // creo el objeto conexion
                            $obj_pdo = new Conexion();

                            // Conecto a la base de datos.
                            $conexion = $obj_pdo->conectar();

                            // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
                            $sentencia = $conexion->prepare($sql);

                            // Reemplazo los marcadores de parametro por los valores reales utilizando el método bindParam().
                            $sentencia->bindParam(":nombre", $value);

                          try
                          {
                            $sentencia->execute();
                          }
                          catch (PDOException $e) 
                          {
                            echo "DataBase Error: The user could not be added.<br>".$e->getMessage();
                          } 
                          catch (Exception $e) 
                          {
                            echo "General Error: The user could not be added.<br>".$e->getMessage();
                          }
                      }       

		public function obtain($value) 
                {
                       $sql = "SELECT * FROM " . $this->tabla;
			$obj_pdo = new Conexion();
			$conexion = $obj_pdo->conectar();
			$sentencia = $conexion->prepare($sql);
			$sentencia->execute();
			while ($row = $sentencia->fetch()) {
				$array[] = $row;
			}
			if(!empty($array))
                            return $array;
                }
                
                public function obtainQuery($queryString)
                {
                   
                            $sql = $queryString;
                            $obj_pdo = new Conexion();
                            $conexion = $obj_pdo->conectar();
                            $sentencia = $conexion->prepare($sql);
                            $sentencia->bindParam(":param", $queryString);

                          try
                          {
                            $sentencia->execute();
                          }
                          catch (PDOException $e) 
                          {
                            echo "DataBase Error: The user could not be added.<br>".$e->getMessage();
                          } 
                          catch (Exception $e) 
                          {
                            echo "General Error: The user could not be added.<br>".$e->getMessage();
                          }
                }
                public function insertQuery($table,$datos)
                {
                    var_dump($datos);
                    //Ojo con el this table
                    $query = "INSERT INTO " . $this->table . "( ";
                    $arrayKeys = array_keys($datos);
                    for ($i = 0; $i < count($arrayKeys); $i++) 
                    {
                       $query .= $arrayKeys[$i];
                       if(count($arrayKeys)-1 > $i)
                           $query .= ",";
                       else
                            $query .= ")";
                    }
                       $query.= " VALUES (";
                    for ($i = 0; $i < count($datos); $i++) 
                    {
                       $query .= "?";
                       if(count($datos)-1 > $i)
                           $query .= ",";
                       else
                            $query .= ")";
                    }
                    
                    
                    $obj_pdo = new Conexion();
                    $conexion = $obj_pdo->conectar();
                    $sentencia = $conexion->prepare($query);
                    
                    
                    for ($i = 0; $i < count($datos); $i++) 
                    {
                      $sentencia->bindParam($i + 1, $datos[$i],\PDO::PARAM_STR,120);
                    }
                    $sentencia->bindParam(":param", $value);
                    
                    var_dump($query);
                }
                
                
                
               /* public function insertQuery($table, $queryString) 
                {
                    //var_dump($queryString);
                    $call = "INSERT INTO" . " " . $table . "(";
                    for ($i = 0; $i < count($queryString); $i++) 
                    {
                        $call .= "?";
                        if ($i != count($queryString) - 1)
                            $call . ",";
                    }
                    $call .= ");";
                    $obj_pdo = new Conexion();
                    $conexion = $obj_pdo->conectar();
                    $sentencia = $conexion->prepare($call);
                    var_dump($sentencia);
                    for ($i = 0; $i < count($queryString); $i++) 
                    {
                      $sentencia->bindParam($i + 1, $queryString[$i]);
                    }
                    try 
                    {
                        $sentencia->execute();
                    } 
                    catch (PDOException $e) 
                    {
                        echo "DataBase Error: The user could not be added.<br>" . $e->getMessage();
                    } 
                    catch (Exception $e) 
                    {
                        echo "General Error: The user could not be added.<br>" . $e->getMessage();
                    }
                }
               
                */
                

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

		public function actualizar($value) {}

		public function eliminar($value) {

			// Guardo como string la consulta sql
			$sql = "DELETE FROM " . $this->tabla . " WHERE id=" . $value;


			// creo el objeto conexion
			$obj_pdo = new Conexion();


			// Conecto a la base de datos.
			$conexion = $obj_pdo->conectar();


			// Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
			$sentencia = $conexion->prepare($sql);


			// Ejecuto la sentencia.
			$sentencia->execute();

		}

		
	}

?>