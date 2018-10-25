<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\Interfaces\IArtistDao as IArtistDao;
use Dao\SingletonDao as SingletonDao;
use Models\Artist as Artist;
use PDO as PDO;

/**
 * TO DO
 * Retrieve, Update and Delete
 */

class ArtistsDao extends SingletonDao implements IArtistDao
{
    private $table = 'Artists';

    /**
     * Returns all Artists as an array of rows
     */
    public function getAll()
    {
        // Guardo como string la consulta sql
        $sql = "SELECT * FROM " . $this->table;

        // creo el objeto conexion
        $obj_pdo = new Connection();

        // Conecto a la base de datos.
        $connection = $obj_pdo->connect();

        // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
        $sentence = $connection->prepare($sql);

        // Ejecuto la sentencia.
        $sentence->execute();

        //Obtiene la siguiente fila de un conjunto de resultados
        //PDO::FETCH_ASSOC Only return next row as an array indexed by column name (no column number)
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $row;
        }
        if (!empty($array)) {
            return $array;
        }
    }

    public function Add(Artist $artist)
    {
        $query = "INSERT INTO " . $this->table . " (name,lastname) VALUES (?,?);";
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        $name = $artist->getName();
        $lastname = $artist->getLastname();

        $sentence->bindParam(1, $name, \PDO::PARAM_STR); //doesn't like if you use the get method here
        $sentence->bindParam(2, $lastname, \PDO::PARAM_STR);

        try {
            $sentence->execute();
            echo "<script> alert('Artista agregado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        }
    }

    public function Retrieve($var)
    {}

    private function retrieveByID($id)
    {
        $query = "SELECT * FROM ".$this->table.
            " WHERE idArtist = ".$id;
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            //$sentence->setFetchMode(PDO::FETCH_CLASS, 'Models/Artist'); //can't convert to object artist
            return $sentence->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<script> alert('Error al intentar buscar Artista: " . $e->getMessage() . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('Error al intentar buscar Artista: " . $e->getMessage() . "');</script>";
        }

    }

    /**
     * Updates values that are diferent from the ones recieved in the object Artist
     */
    public function Update(Artist $oldArtist, Artist $newArtist)
    {
        $valuesToModify = "";
        $oldArtistArray = $this->retrieveByID($oldArtist->getIdArtist()); //get row of id as array of values
        $artistArray = $newArtist->getAll(); //convert object to array of values

        /**
         * Check if a value is different from the one on the database, if different, sets the column and 
         * value for the SET query
         */
        foreach ($oldArtistArray as $key => $value) { 
            if($key != "idArtist"){
                if($oldArtistArray[$key] != $artistArray[$key]){
                    $valuesToModify .= $key." = "."'".$artistArray[$key]."', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify,", "); //strip ", " from last character

        $query = "UPDATE ".$this->table." SET ".$valuesToModify." WHERE idArtist = ".$oldArtist->getIdArtist();
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            echo "<script> alert('Artista modificado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        }
    }

    public function Delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_artist = " . $id;
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . $e->getMessage() . "');</script>";
        }
    }
}
