<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatsTypeDao as ISeatsTypeDao;
use Models\SeatsType as SeatsType;

class SeatsTypesDao extends SingletonDao implements ISeatsTypeDao
{
    private $connection;
    private $tableName = 'SeatsTypes';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatsType $seatsType)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($seatsType->getAll()); //get object atribute names 

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
            throw new Exception ("Add error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Add error: ".$ex->getMessage());
        }
    }

    public function retrieveById($id)
    {   
        $seatsType = new SeatsType();

        $seatsTypeProperties = array_keys($seatsType->getAll()); //get atribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$seatsTypeProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($seatsTypeProperties as $value) { //auto fill object with magic function __set
                    $seatsType->__set($value, $row[$value]);
                }
            }

            return $seatsType;
        } catch (PDOException $ex) {
            throw new Exception ("SeatsType search error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("SeatsType search error: ".$ex->getMessage());
        }
    }

    public function GetAll()
    {
        $seatsTypeList = array();
        $seatsType = new SeatsType();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception ("GetAll error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("GetAll error: ".$ex->getMessage());
        }
        
        $seatsTypeProperties = array_keys($seatsType->getAll());

        foreach ($resultSet as $row)
        {                
            $seatsType = new SeatsType();
            
            foreach ($seatsTypeProperties as $value) {
                $seatsType->__set($value, $row[$value]);
            }

            array_push($seatsTypeList, $seatsType);
        }

        return $seatsTypeList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatsType
     */
    public function Update(SeatsType $oldSeatsType, SeatsType $newSeatsType)
    {
        $valuesToModify = "";
        $oldSeatsTypeArray = $oldSeatsType->getAll(); //convert object to array of values
        $seatsTypeArray = $newSeatsType->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldSeatsTypeArray as $key => $value) {
            if ($key != "idSeatsType") {
                if ($oldSeatsTypeArray[$key] != $seatsTypeArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $seatsTypeArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idSeatsType = " . $oldSeatsType->getIdSeatsType();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        }
    }

    /**
     * Logical Delete
     */
    public function Delete(SeatsType $seatsType)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idSeatsType = ".$seatsType->getIdSeatsType();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception ("Delete error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Delete error: ".$ex->getMessage());
        }
    }
}
