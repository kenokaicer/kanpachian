<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IArtistDao as IArtistDao;
use Models\Artist as Artist;

class ArtistDao extends SingletonDao implements IArtistDao
{
    private $connection;
    private $tableName = 'Artists';
    private $tableName2 = 'Artists_x_EventByDate';

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
        $parameters = array_filter($artist->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

        /**
         * Auto fill values for querry
         * end result:
         * $query = "INSERT INTO " . $this->tableName . " (name,lastname) VALUES (:name,:lastname);";
         */
        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO " . $this->tableName . " (".$columns.") VALUES (".$values.");";

        try { 
            $addedRows = $this->connection->executeNonQuery($query, $parameters);
            if($addedRows!=1){
                throw new Exception("Number of rows added ".$addedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function getByID($id)
    {   
        $artist = new Artist();

        $artistAttributes = array_keys($artist->getAll()); //get attributes names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$artistAttributes[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($artistAttributes as $value) { //auto fill object with magic function __set
                    $artist->__set($value, $row[$value]);
                }
            }

            return $artist;
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Returns all Artists as an array of Artists
     */
    public function getAll()
    {
        $artistList = array();
        $artist = new Artist();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $artistAttributes = array_keys($artist->getAll()); //get attributes names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $artist = new Artist();
            
            foreach ($artistAttributes as $value) { //auto fill object with magic function __set
                $artist->__set($value, $row[$value]);
            }

            array_push($artistList, $artist);
        }

        return $artistList;
    }

    public function getAllArtitsByEventByDate($idEvent)
    {
        $artistList = array();
        $artist = new Artist();

        $query = "SELECT a.idArtist, name, lastname 
        FROM " . $this->tableName." a 
        INNER JOIN ".$this->tableName2." ae 
        ON a.idArtist = ae.idArtist
        WHERE ae.idArtist = ".$idEvent;

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        $artistAttributes = array_keys($artist->getAll());

        foreach ($resultSet as $row)
        {                
            $artist = new Artist();
            
            foreach ($artistAttributes as $value) {
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
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function Delete(Artist $artist)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$artistAttributes[0]." = " . $artist->getIdArtist();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idArtist = ".$artist->getIdArtist();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }
}
