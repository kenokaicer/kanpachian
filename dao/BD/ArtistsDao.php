<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\Interfaces\IArtistDao as IArtistDao;
use Dao\SingletonDao as SingletonDao;
use Models\Artist as Artist;
use PDO as PDO;

class ArtistsDao extends SingletonDao implements IArtistDao
{
    private $connection;
    private $tableName = 'Artists';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(Artist $artist)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $artist->getName();
        $parameters["lastname"] = $artist->getLastName();
        */
        $parameters = array_filter($artist->getAll()); //does the same as the above but automated, array filter takes out null values (id)

        /**
         * Auto fill values for querry
         * end result
         * $query = "INSERT INTO " . $this->tableName . " (name,lastname) VALUES (:name,:lastname);";
         */
        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO " . $this->tableName . " (".$columns.") VALUES (".$values.");";
        
        $this->connection->executeNonQuery($query, $parameters); //See if this need to throw excetion to controller
        //will return number of row modified in table, so 0 if nothing added (wrong), 1 if one added, >2 if more than one
        //added, this in most cases is wrong. So adding a check and trowing an exception could be good.

        /*try { //this is here until try-catch from connection works, then arrange it
            $this->connection->executeNonQuery($query, $parameters);
            echo "<script> alert('Artista agregado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        }*/
    }

    public function retrieveById($id)
    {   
        $artist = new Artist();

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE idArtist = " . $id;
        
        $resultSet = $this->connection->Execute($query);

        foreach ($resultSet as $row) //loops returned rows
        {               
            foreach ($artistPropierties as $value) { //auto fill object with magic function __set
                $artist->__set($value, $row[$value]);
            }
        }

        return $artist;


        /*try {
            $sentence->execute();
            //$sentence->setFetchMode(PDO::FETCH_CLASS, 'Models/Artist'); //can't convert to object artist
            return $sentence->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<script> alert('Error al intentar buscar Artista: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('Error al intentar buscar Artista: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        }*/
    }

    /**
     * Returns all Artists as an array of Artists
     */
    public function RetrieveAll()
    {
        $artistList = array();
        $artist = new Artist();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        $resultSet = $this->connection->Execute($query);

        $artistPropierties = array_keys($artist->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $artist = new Artist();
            
            foreach ($artistPropierties as $value) { //auto fill object with magic function __set
                $artist->__set($value, $row[$value]);
            }

            array_push($artistList, $artist);
        }

        return $artistList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Artist
     */
    public function Update(Artist $oldArtist, Artist $newArtist)
    {
        $valuesToModify = "";
        $oldArtistArray = $oldArtist->getAll(); //convert object to array of values
        $artistArray = $newArtist->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldArtistArray as $key => $value) {
            if ($key != "idArtist") {
                if ($oldArtistArray[$key] != $artistArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $artistArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idArtist = " . $oldArtist->getIdArtist();

        $this->connection->executeNonQuery($query, array()); //no parameters needed so send empty array
        
        /*
        try {
            $sentence->execute();
            echo "<script> alert('Artista modificado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        }*/
    }

    public function Delete(Artist $artist)
    {
        $query = "DELETE FROM " . $this->tableName . " WHERE idArtist = " . $artist->getIdArtist();
        
        $this->connection->executeNonQuery($query, array());

        /*try {
            $sentence->execute();
            echo "<script> alert('Artista eliminado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $e->getMessage()) . "');</script>";
        }*/
    }
}
