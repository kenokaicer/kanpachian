<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IEventByDateDao as IEventByDateDao;
use Models\EventByDate as EventByDate;
use Dao\BD\TheaterDao as TheaterDao;
use Dao\BD\ArtistDao as ArtistDao;
use Dao\BD\EventDao as EventDao;

class EventByDateDao extends SingletonDao implements IEventByDateDao
{
    private $connection;
    private $tableName = 'EventByDates';
    private $tableName2 = 'Artists_x_EventByDate';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    /**
     * Add eventByDate without EventByDatesByDate
     */
    public function Add(EventByDate $eventByDate)
    {
        $columns = "";
        $values = "";
        
        $parameters["date"] = $eventByDate->getDate();
        $parameters["idTheater"] = $eventByDate->getTheater()->getIdTheater();
        $parameters["idEvent"] = $eventByDate->getEvent()->getIdEvent();

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

    public function getByID($id) //right now not returning eventByDatesByDate
    {   
        $eventByDate = new EventByDate();

        $eventByDateProperties = array_keys($eventByDate->getAll()); //get atribute names from object for use in __set
        array_pop($eventByDateProperties);
        array_pop($eventByDateProperties);

        $query = "SELECT * FROM " . $this->tableName ."  
                WHERE ".$eventByDateProperties[0]." = ".$id." 
                AND e.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        }

        $row = reset($resultSet);
   
        foreach ($eventByDateProperties as $value) { //auto fill object with magic function __set
            $eventByDate->__set($value, $row[$value]);
        }

        try{
            $event = EventDao::getInstance()->getByID($row["idEvent"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        }

        $eventByDate->setEvent($event);

        try{
            $theater = TheaterDao::getInstance()->getByID($row["idTheater"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        }
        
        $eventByDate->setTheater($theater);
        
        //----------artist list get, make method

        $eventByDate->setCategory($category);

        return $eventByDate;
    }

    public function getAll()
    {
        $eventByDateList = array();
        $eventByDate = new EventByDate();

        $query = "SELECT e.idEventByDate, eventByDateName, image, description, c.idCategory, c.category
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
        
        $eventByDateProperties = array_keys($eventByDate->getAll());
        array_pop($eventByDateProperties);
        array_pop($eventByDateProperties);

        $categoryProperties = array_keys($category->getAll());

        foreach ($resultSet as $row)
        {                
            $eventByDate = new EventByDate();
            
            foreach ($eventByDateProperties as $value) {
                $eventByDate->__set($value, $row[$value]);
            }

            $category = new Category();

            foreach ($categoryProperties as $value) {
                $category->__set($value, $row[$value]);
            }
    
            $eventByDate->setCategory($category);

            array_push($eventByDateList, $eventByDate);
        }

        return $eventByDateList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object EventByDate
     */
    public function Update(EventByDate $oldEventByDate, EventByDate $newEventByDate){}

    /**
     * Logical Delete
     */
    public function Delete(EventByDate $eventByDate)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idEventByDate = ".$eventByDate->getIdEventByDate();

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
