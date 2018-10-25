<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatTypeDao as ISeatTypeDao;
use Models\SeatType as SeatType;

class SeatTypeDao extends SingletonDao implements ISeatTypeDao
{
    private $connection;
    private $tableName = 'SeatTypes';
    private $tableName2 = 'SeatTypes_x_Theater';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatType $seatType)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($seatType->getAll()); //get object atribute names 

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
        $seatType = new SeatType();

        $seatTypeProperties = array_keys($seatType->getAll()); //get atribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$seatTypeProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($seatTypeProperties as $value) { //auto fill object with magic function __set
                    $seatType->__set($value, $row[$value]);
                }
            }

            return $seatType;
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function getAll()
    {
        $seatTypeList = array();
        $seatType = new SeatType();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $seatTypeProperties = array_keys($seatType->getAll());

        foreach ($resultSet as $row)
        {                
            $seatType = new SeatType();
            
            foreach ($seatTypeProperties as $value) {
                $seatType->__set($value, $row[$value]);
            }

            array_push($seatTypeList, $seatType);
        }

        return $seatTypeList;
    }

    /**
     * Retruns array of all SeatTypes associated to a Theater
     */
    public function getAllByTheaterId($id)
    {
        $seatTypeList = array();
        $seatType = new SeatType();

        $query = "SELECT SeatTypes.idSeatType, name, description 
        FROM " . $this->tableName2." 
        INNER JOIN ".$this->tableName." 
        ON SeatTypes_x_Theater.idSeatType = SeatTypes.idSeatType
        WHERE SeatTypes_x_Theater.idTheater = ".$id;

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        $seatTypeProperties = array_keys($seatType->getAll());

        foreach ($resultSet as $row)
        {                
            $seatType = new SeatType();
            
            foreach ($seatTypeProperties as $value) {
                $seatType->__set($value, $row[$value]);
            }

            array_push($seatTypeList, $seatType);
        }

        return $seatTypeList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatType
     */
    public function Update(SeatType $oldSeatType, SeatType $newSeatType)
    {
        $valuesToModify = "";
        $oldSeatTypeArray = $oldSeatType->getAll(); //convert object to array of values
        $seatTypeArray = $newSeatType->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldSeatTypeArray as $key => $value) {
            if ($key != "idSeatType") {
                if ($oldSeatTypeArray[$key] != $seatTypeArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $seatTypeArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idSeatType = " . $oldSeatType->getIdSeatType();
        
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

    /**
     * Logical Delete
     */
    public function Delete(SeatType $seatType)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idSeatType = ".$seatType->getIdSeatType();

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
