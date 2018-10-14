
<?php namespace Dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IEventDao as IEventDao;
use Models\Event as Event;

class EventsDao extends SingletonDao implements IEventDao
{
    private $connection;
    private $tableName = 'Events';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(Event $event)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $event->getName();
        $parameters["lastname"] = $event->getLastName();
        */
        $parameters = array_filter($event->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

        /**
         * Auto fill values for querry
         * end result:
         * $query = "INSERT INTO " . $this->tableName . " (name,lastname) VALUES (:name,:lastname);";
         */
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
            echo "<script> alert('Eventa agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
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
            echo "<script> alert('Error al intentar buscar Eventa: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar Eventa: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all Events as an array of Events
     */
    public function RetrieveAll()
    {
        $eventList = array();
        $event = new Event();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar Eventas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Eventas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $eventProperties = array_keys($event->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $event = new Event();
            
            foreach ($eventProperties as $value) { //auto fill object with magic function __set
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

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idEvent = " . $oldEvent->getIdEvent();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Eventa modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(Event $event)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$eventProperties[0]." = " . $event->getIdEvent();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idEvent = ".$event->getIdEvent();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Eventa eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
