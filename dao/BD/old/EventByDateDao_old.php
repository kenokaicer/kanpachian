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
    private $tableNameArtist = 'Artists';
    private $tableNameTheater = 'Theaters';
    private $tableNameArtistEventByDate = 'Artists_x_EventByDate';
    private $tableNameEvent = 'Events';
    private $tableNameCatergory = 'Categories';

    public function __construct(){
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

    public function getById($id) //right now not returning eventByDatesByDate
    {   
        $eventByDate = new EventByDate();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName ."  
                WHERE ".$eventByDateAttributes[0]." = ".$id." 
                AND e.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        }

        $row = reset($resultSet);
   
        foreach ($eventByDateAttributes as $value) { //auto fill object with magic function __set
            $eventByDate->__set($value, $row[$value]);
        }

        try{
            $event = EventDao::getInstance()->getById($row["idEvent"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
        }

        $eventByDate->setEvent($event);

        try{
            $theater = TheaterDao::getInstance()->getById($row["idTheater"]);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
        }
        
        $eventByDate->setTheater($theater);
        
        try{
            $artistList = ArtistDao::getInstance()->getAllArtitsByEventByDate($theater->getIdTheater());
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",artist list query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",artist list query error: ".$ex->getMessage());
        }
        
        $eventByDate->setArtists($artistList);

        return $eventByDate;
    }

    public function getAll()
    {
        $eventByDateList = array();
        $eventByDate = new EventByDate();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set

        $query = 
        "SELECT
         * FROM " . $this->tableName ." 
         INNER JOIN ".$this->table." 
         WHERE enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.",eventByDate query error: ".$ex->getMessage());
        }

        foreach ($resultSet as $row){
            $eventByDate = new EventByDate();
            
            foreach ($eventByDateAttributes as $value) {
                $eventByDate->__set($value, $row[$value]);
            }

            array_push($eventByDateList, $eventByDate);
        }

        foreach ($eventByDateList as $value) {
            try{
                $event = EventDao::getInstance()->getById($row["idEvent"]);
            } catch (PDOException $ex) {
                throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
            } catch (Exception $ex) {
                throw new Exception (__METHOD__.",event query error: ".$ex->getMessage());
            }

            $eventByDate->setEvent($event);

            try{
                $theater = TheaterDao::getInstance()->getById($row["idTheater"]);
            } catch (PDOException $ex) {
                throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
            } catch (Exception $ex) {
                throw new Exception (__METHOD__.",theater query error: ".$ex->getMessage());
            }

            $eventByDate->setTheater($theater);

            try{
                $artistList = ArtistDao::getInstance()->getAllArtitsByEventByDate($theater->getIdTheater());
            } catch (PDOException $ex) {
                throw new Exception (__METHOD__.",artist list query error: ".$ex->getMessage());
            } catch (Exception $ex) {
                throw new Exception (__METHOD__.",artist list query error: ".$ex->getMessage());
            }
    
            $eventByDate->setArtists($artistList);
        }

        return $eventByDate;
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
