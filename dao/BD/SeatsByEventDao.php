<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatsByEventDao as ISeatsByEventDao;
use Models\SeatsByEvent as SeatsByEvent;

class SeatsByEventDao extends SingletonDao implements ISeatsByEventDao
{
    private $connection;
    private $tableName = 'SeatsByEvents';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatsByEvent $seatsByEvent)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($seatsByEvent->getAll()); //get object atribute names
        array_pop($parameters);
        array_pop($parameters);
        $parameters["idSeatType"] = $seatsByEvent->getSeatType()->getIdSeatType();
        $parameters["idEventByDate"] = $seatsByEvent->getEventByDate()->getIdEventByDate();

        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO " . $this->tableName . " (".$columns.",idSeatType,idEventByDate) 
        VALUES (".$values.",:idSeatType,:idEventByDate);";

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
        $seatsByEvent = new SeatsByEvent();

        $seatsByEventAttributes = array_keys($seatsByEvent->getAll()); //get atribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$seatsByEventAttributes[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($seatsByEventAttributes as $value) { //auto fill object with magic function __set
                    $seatsByEvent->__set($value, $row[$value]);
                }
            }

            return $seatsByEvent;
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function getAll()
    {
        $seatsByEventList = array();
        $seatsByEvent = new SeatsByEvent();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $seatsByEventAttributes = array_keys($seatsByEvent->getAll());

        foreach ($resultSet as $row)
        {                
            $seatsByEvent = new SeatsByEvent();
            
            foreach ($seatsByEventAttributes as $value) {
                $seatsByEvent->__set($value, $row[$value]);
            }

            array_push($seatsByEventList, $seatsByEvent);
        }

        return $seatsByEventList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatsByEvent
     */
    public function Update(SeatsByEvent $oldSeatsByEvent, SeatsByEvent $newSeatsByEvent)
    {
        $valuesToModify = "";
        $oldSeatsByEventArray = $oldSeatsByEvent->getAll(); //convert object to array of values
        $seatsByEventArray = $newSeatsByEvent->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldSeatsByEventArray as $key => $value) {
            if ($key != "idSeatsByEvent") {
                if ($oldSeatsByEventArray[$key] != $seatsByEventArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $seatsByEventArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idSeatsByEvent = " . $oldSeatsByEvent->getIdSeatsByEvent();
        
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
    public function Delete(SeatsByEvent $seatsByEvent)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idSeatsByEvent = ".$seatsByEvent->getIdSeatsByEvent();

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
