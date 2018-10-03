<?php namespace dao\BD;

require_once 'Conexion.php';

use dao\IDao as IDao;
use dao\SingletonDao as SingletonDao;
use models\Artist as Artist;

class ArtistsDao extends SingletonDao implements IDao
{
    private $tabla = 'Artists';

    public function RetrieveAll()
    {
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
        if (!empty($array)) {
            return $array;
        }
    }

    public function Add(Artist $artist)//intefas se
    {
        // $sql = "INSERT INTO Artists VALUES (?)";
        $sql = "INSERT INTO Artists (Nombre,Apellido) VALUES ('Robie','Williams');";
        $obj_pdo = new Conexion();
        $pdoConexion = $obj_pdo->conectar();
        $sentencia = $pdoConexion->prepare($sql);
        // $sentencia->bindParam(1, $equipo['nombre'], \PDO::PARAM_STR);
        try {
            $sentencia->execute();
        } catch (PDOException $e) {
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
