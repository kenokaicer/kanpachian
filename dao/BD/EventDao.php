<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IEventDao as IEventDao;
use Models\Event as Event;
use Models\Category as Category;

class EventDao extends SingletonDao implements IEventDao
{
    private $connection;
    private $tableName = 'Events';
    private $tableName2 = 'Categories';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    /**
     * Add event without EventByDate
     */
    public function Add(Event $event)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($event->getAll()); //get object attribute names 
        array_pop($parameters);
        array_pop($parameters);
        $parameters["idCategory"] = $event->getCategory()->getIdCategory();

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

    public function getByID($id) //right now not returning eventByDate
    {   
        $event = new Event();
        $category = new Category();

        $eventAttributes = array_keys($event->getAll()); //get attribute names from object for use in __set
        array_pop($eventAttributes);

        $categoryAttributes = array_keys($category->getAll());

        $query = "SELECT e.idEvent, eventName, image, description, c.idCategory, c.category
                FROM " . $this->tableName ." e
                INNER JOIN ".$this->tableName2." c
                On e.idCategory = c.idCategory  
                WHERE ".$eventAttributes[0]." = ".$id." 
                AND e.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        $row = reset($resultSet);
   
        foreach ($eventAttributes as $value) { //auto fill object with magic function __set
            $event->__set($value, $row[$value]);
        }

        foreach ($categoryAttributes as $value) {
            $category->__set($value, $row[$value]);
        }

        $event->setCategory($category);

        return $event;
    }

    public function getAll() //right now not returning eventByDate
    {
        $eventList = array();
        $event = new Event();
        $category = new Category();

        $query = "SELECT e.idEvent, eventName, image, description, c.idCategory, c.categoryName
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
        
        $eventAttributes = array_keys($event->getAll());
        array_pop($eventAttributes);

        $categoryAttributes = array_keys($category->getAll());

        foreach ($resultSet as $row)
        {                
            $event = new Event();
            
            foreach ($eventAttributes as $value) {
                $event->__set($value, $row[$value]);
            }

            $category = new Category();

            foreach ($categoryAttributes as $value) {
                $category->__set($value, $row[$value]);
            }
    
            $event->setCategory($category);

            array_push($eventList, $event);
        }

        return $eventList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Event
     */
    public function Update(Event $oldEvent, Event $newEvent){}

    /**
     * Logical Delete
     */
    public function Delete(Event $event)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idEvent = ".$event->getIdEvent();

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
