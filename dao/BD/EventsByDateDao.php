<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IEventsByDateDao as IEventsByDateDao;
use Models\EventsByDate as EventsByDate;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;

class EventsByDateDao extends SingletonDao implements IEventsByDateDao
{
    private $connection;
    private $tableName = 'EventsByDates';
    private $tableName2 = 'Artists_x_EventsByDate';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    /**
     * Add eventsByDate without EventsByDatesByDate
     */
    public function Add(EventsByDate $eventsByDate)
    {
        $columns = "";
        $values = "";
        
        $parameters["date"] = $eventsByDate->getDate();
        $parameters["idTheater"] = $eventsByDate->getTheater()->getIdTheater();
        $parameters["idEvent"] = $eventsByDate->getEvent()->getIdEvent();

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

    public function getByID($id) //right now not returning eventsByDatesByDate
    {   
        $eventsByDate = new EventsByDate();

        $eventsByDateProperties = array_keys($eventsByDate->getAll()); //get atribute names from object for use in __set
        array_pop($eventsByDateProperties);
        array_pop($eventsByDateProperties);

        $query = "SELECT * FROM " . $this->tableName ."  
                WHERE ".$eventsByDateProperties[0]." = ".$id." 
                AND e.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",eventsByDate query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",eventsByDate query error: ".$ex->getMessage());
        }

        $row = reset($resultSet);
   
        foreach ($eventsByDateProperties as $value) { //auto fill object with magic function __set
            $eventsByDate->__set($value, $row[$value]);
        }

        try{
            $event = EventDao::getInstance()->getByID($row["idEvent"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        }

        $eventsByDate->setEvent($event);

        try{
            $theater = TheaterDao::getInstance()->getByID($row["idTheater"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        }
        
        $eventsByDate->setTheater($theater);
        
        //----------artist list get, make method

        $eventsByDate->setCategory($category);

        return $eventsByDate;
    }

    public function getAll() //right now not returning eventsByDatesByDate
    {
        $eventsByDateList = array();
        $eventsByDate = new EventsByDate();
        $category = new Category();

        $query = "SELECT e.idEventsByDate, eventsByDateName, image, description, c.idCategory, c.category
                FROM " . $this->tableName ." e
                INNER JOIN ".$this->tableName2." c
                On e.idCategory = c.idCategory  
                AND e.enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $eventsByDateProperties = array_keys($eventsByDate->getAll());
        array_pop($eventsByDateProperties);
        array_pop($eventsByDateProperties);

        $categoryProperties = array_keys($category->getAll());

        foreach ($resultSet as $row)
        {                
            $eventsByDate = new EventsByDate();
            
            foreach ($eventsByDateProperties as $value) {
                $eventsByDate->__set($value, $row[$value]);
            }

            $category = new Category();

            foreach ($categoryProperties as $value) {
                $category->__set($value, $row[$value]);
            }
    
            $eventsByDate->setCategory($category);

            array_push($eventsByDateList, $eventsByDate);
        }

        return $eventsByDateList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object EventsByDate
     */
    public function Update(EventsByDate $oldEventsByDate, EventsByDate $newEventsByDate){}

    /**
     * Logical Delete
     */
    public function Delete(EventsByDate $eventsByDate)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idEventsByDate = ".$eventsByDate->getIdEventsByDate();

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
