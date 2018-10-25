<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\Interfaces\IArtistDao as IArtistDao;
use Dao\SingletonDao as SingletonDao;
use Models\Artist as Artist;
use PDO as PDO;

class ArtistDao extends SingletonDao implements IArtistDao
{
    private $table = 'Artists';

    /**
     * Returns all Artists as an array of rows
     */
    public function getAll()
    {
        // Guardo como string la consulta sql
        $querry = "SELECT * FROM " . $this->table;

        // creo el objeto conexion
        $obj_pdo = new Connection();

        // Conecto a la base de datos.
        $connection = $obj_pdo->connect();

        // Creo una sentencia llamando a prepare. Esto devuelve un objeto statement
        $sentence = $connection->prepare($querry);

        // Ejecuto la sentencia.
        $sentence->execute();

        //Obtiene la siguiente fila de un conjunto de resultados
        
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) { //PDO::FETCH_ASSOC Only return next row as an array indexed by column name (no column number)
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
        $sentence->bindValue(1, $artist->getName(), \PDO::PARAM_STR); //use bindValue insted of bindParam to be able to use getters
        $sentence->bindValue(2, $artist->getLastname(), \PDO::PARAM_STR);

        try {
            $sentence->execute();
            echo "<script> alert('Artista agregado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
    }

    public function Retrieve($var)
    {}

    private function retrieveByID($id) //Deprecated, no longer used
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
            echo "<script> alert('Error al intentar buscar Artista: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('Error al intentar buscar Artista: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }

    }

    /**
     * Updates values that are diferent from the ones recieved in the object Artist
     */
    public function Update(Artist $oldArtist, Artist $newArtist)
    {
        $valuesToModify = "";
        $oldArtistArray = $oldArtist->getAll();  //convert object to array of values
        $artistArray = $newArtist->getAll();

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
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
    }

    public function Delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE idArtist = " . $id;
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
    }
}
