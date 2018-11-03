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

    public function Add(Event $event)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters = array_filter($event->getAll()); //get object attribute names 
            $parameters["idCategory"] = $event->getCategory()->getIdCategory();

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

    public function getById($id)
    {   
        $parameters = get_defined_vars();
        $event = null;

        try {
            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT e.idEvent, eventName, image, description, c.idCategory, c.category
                    FROM " . $this->tableName ." e
                    INNER JOIN ".$this->tableName2." c
                    On e.idCategory = c.idCategory  
                    WHERE ".$eventAttributes[0]." = :".key($parameters)." 
                    AND e.enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned more than 1 result, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $event = new Event();
                foreach ($eventAttributes as $value) { //auto fill object with magic function __set
                    $event->__set($value, $row[$value]);
                }

                $category = new Category();
                foreach ($categoryAttributes as $value) {
                    $category->__set($value, $row[$value]);
                }

                $event->setCategory($category);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $event;
    }

    public function getAll()
    {
        $eventList = array();
        
        try {
            $query = "SELECT e.idEvent, eventName, image, description, c.idCategory, c.categoryName
                    FROM " . $this->tableName ." e
                    INNER JOIN ".$this->tableName2." c
                    On e.idCategory = c.idCategory  
                    AND e.enabled = 1";

            $resultSet = $this->connection->Execute($query);
        
            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

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
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
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
        try {
            $parameters["idEvent"] = $event->getIdEvent();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idEvent = :idEvent";

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
