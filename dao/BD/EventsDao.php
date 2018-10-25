<?php namespace Dao\BD; //to do: update for complex dao

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IEventDao as IEventDao;
use Models\Event as Event;

class EventsDao extends SingletonDao //implements IEventDao
{
    private $connection;
    private $tableName = 'Events';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Event $event)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($event->getAll()); 

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
        $event = new Event();

        $eventProperties = array_keys($event->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$eventProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($eventProperties as $value) { //auto fill object with magic function __set
                    $event->__set($value, $row[$value]);
                }
            }

            return $event;
        } catch (PDOException $ex) {
            throw new Exception ("Event search error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Event search error: ".$ex->getMessage());
        }
    }

    /**
     * Returns all Events as an array of Events
     */
    public function RetrieveEventsOnly()
    {
        $eventList = array();
        $event = new Event();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception ("RetrieveAll error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("RetrieveAll error: ".$ex->getMessage());
        }
        
        $eventProperties = array_keys($event->getAll()); 
        array_pop($eventProperties);
        array_pop($eventProperties);

        foreach ($resultSet as $row) 
        {                
            $event = new Event();
            
            foreach ($eventProperties as $value) { 
                $event->__set($value, $row[$value]);
            }

            array_push($eventList, $event);
        }

        return $eventList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Event
     */
    public function Update(Event $oldEvent, Event $newEvent)
    {
        $valuesToModify = "";
        $oldEventArray = $oldEvent->getAll(); //convert object to array of values
        $eventArray = $newEvent->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldEventArray as $key => $value) {
            if ($key != "idEvent") {
                if ($oldEventArray[$key] != $eventArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $eventArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); 

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idEvent = " . $oldEvent->getIdEvent();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); 
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        }
    }

    public function Delete(Event $event)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idEvent = ".$event->getIdEvent();

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
