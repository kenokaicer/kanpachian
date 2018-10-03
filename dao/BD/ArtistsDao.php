<?php namespace dao\BD;

use dao\intefaces\IArtistDao as IArtistDao;
use dao\SingletonDao as SingletonDao;
use models\Artist as Artist;
use dao\BD\Connection as Connection;

class ArtistsDao extends SingletonDao implements IArtistDao
{
    private $table = 'Artists';

    public function RetrieveAll()
    {
        // Guardo como string la consulta sql
        $sql = "SELECT * FROM " . $this->table;

        // creo el objeto conexion
        $obj_pdo = new Connection();

        // Conecto a la base de datos.
        $conexion = $obj_pdo->connect();

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
        $sql = "INSERT INTO Artists (name,lastname) VALUES ('Robie','Williams');";
        $obj_pdo = new Conexion();
        $pdoConexion = $obj_pdo->connect();
        $sentencia = $pdoConexion->prepare($sql);
        // $sentencia->bindParam(1, $equipo['name'], \PDO::PARAM_STR);
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

    public function Retrieve($var){}
    public function Update(Artist $artist){}
    public function Delete($var){}
}
