<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatTypeDao as ISeatTypeDao;
use Models\SeatType as SeatType;

class SeatTypeDao implements ISeatTypeDao
{
    private $connection;
    private $tableName = 'SeatTypes';
    private $tableName2 = 'SeatTypes_x_Theater';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatType $seatType)
    {
        $columns = "";
        $values = "";
        
        try { 
            $parameters = array_filter($seatType->getAll()); //get object attribute names 

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

    public function getById($idSeatType)
    {   
        $parameters = get_defined_vars();
        $seatType = null;
        
        try {
            $seatTypeAttributes = array_keys(SeatType::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName ." 
                WHERE ".$seatTypeAttributes[0]." = :".key($parameters)." 
                AND Enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);
            
            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) { //auto fill object with magic function __set
                    $seatType->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $seatType;
    }

    public function getBySeatTypeName($seatTypeName)
    {   
        $parameters = get_defined_vars();
        $seatType = null;
        
        try {
            $seatTypeAttributes = array_keys(SeatType::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName ." 
                WHERE seatTypeName = :".key($parameters);
            
            $resultSet = $this->connection->Execute($query,$parameters);
            
            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) { //auto fill object with magic function __set
                    $seatType->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $seatType;
    }

    public function getAll()
    {
        $seatTypeList = array();

        try{
            $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";
        
            $resultSet = $this->connection->Execute($query);
        
            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            foreach ($resultSet as $row)
            {                
                $seatType = new SeatType();
                
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                array_push($seatTypeList, $seatType);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $seatTypeList;
    }

    /**
     * Retruns array of all SeatTypes associated to a Theater
     */
    public function getAllByTheaterId($idTheater)
    {
        $parameters = get_defined_vars();
        $seatTypeList = array();
        
        try{
            $query = "SELECT SeatTypes.idSeatType, seatTypeName, description 
            FROM " . $this->tableName2." STT
            INNER JOIN ".$this->tableName." ST
            ON STT.idSeatType = ST.idSeatType
            WHERE STT.idTheater = :".key($parameters)." 
            AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            foreach ($resultSet as $row)
            {                
                $seatType = new SeatType();
                
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                array_push($seatTypeList, $seatType);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $seatTypeList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatType
     */
    public function Update(SeatType $oldSeatType, SeatType $newSeatType)
    {
        $valuesToModify = "";
       
        try {
            $oldSeatTypeArray = $oldSeatType->getAll(); //convert object to array of values
            $seatTypeArray = $newSeatType->getAll();
            $parameters["idSeatType"] = $oldSeatType->getIdSeatType();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldSeatTypeArray as $key => $value) {
                if ($key != "idSeatType") {
                    if ($oldSeatTypeArray[$key] != $seatTypeArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $seatTypeArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idSeatType = :idSeatType";
            
                $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
                
                if($modifiedRows!=1){
                    throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
                }
            }else{
                throw new Exception("No hay datos para modificar, ningún campo nuevo ingresado");
            }
        } catch (PDOException $ex) {
            echo "update pdo";
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            echo "update ex";
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Logical Delete
     */
    public function Delete(SeatType $seatType)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$seatTypeAttributes[0]." = " . $seatType->getIdSeatType();
        try {
            $parameters["idSeatType"] = $seatType->getIdSeatType();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idSeatType = :idSeatType";

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
