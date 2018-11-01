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

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Artist $artist)
    {
        $columns = "";
        $values = "";
        
        try {
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

    public function getByID($idArtist)
    {   
        $parameters = get_defined_vars();
        $artist = null;
        
        try {
            $artistAttributes = array_keys(Artist::getAttributes()); //get attributes names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$artistAttributes[0]." = :".key($parameters)." 
            AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            if(lenght($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned more than 1 result, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $row = reset($resultSet);
                
                $artist = new Artist();          
                foreach ($artistAttributes as $value) { //auto fill object with magic function __set
                    $artist->__set($value, $row[$value]);
                }
            }  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        return $artist;
    }

    /**
     * Returns all Artists as an array of Artists
     */
    public function getAll()
    {
        $artistList = array();

        try{
            $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

            $resultSet = $this->connection->Execute($query);
       
            $artistAttributes = array_keys(Artist::getAttributes()); //get attributes names from object for use in __set

            foreach ($resultSet as $row) //loops returned rows
            {                
                $artist = new Artist();
                
                foreach ($artistAttributes as $value) { //auto fill object with magic function __set
                    $artist->__set($value, $row[$value]);
                }

                array_push($artistList, $artist);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $artistList;
    }

    public function getAllArtitsByEventByDate($idEvent)
    {
        $artistList = array();

        try{
            $query = "SELECT a.idArtist, name, lastname 
            FROM " . $this->tableName." a 
            INNER JOIN ".$this->tableName2." ae 
            ON a.idArtist = ae.idArtist
            WHERE ae.idArtist = ".$idEvent;

            $resultSet = $this->connection->Execute($query);

            $artistAttributes = array_keys(Artist::getAttributes());

            foreach ($resultSet as $row)
            {                
                $artist = new Artist();
                
                foreach ($artistAttributes as $value) {
                    $artist->__set($value, $row[$value]);
                }

                array_push($artistList, $artist);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $artistList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Artist
     */
    public function Update(Artist $oldArtist, Artist $newArtist)
    {
        $valuesToModify = "";
       
        try {
            $oldArtistArray = $oldArtist->getAll(); //convert object to array of values
            $artistArray = $newArtist->getAll();
            $parameters["idArtist"] = $oldArtist->getIdArtist();

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

            $query = "UPDATE ".$this->tableName." 
                SET ".$valuesToModify." 
                WHERE idArtist = :idArtist";
        
            $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
            
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
        try {
            $parameters["idArtist"] = $artist->getIdArtist();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idArtist = :idArtist";

            $modifiedRows = $this->connection->executeNonQuery($query, $parameters);

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
