
<?php namespace Dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatsByEvent as ISeatsByEvent;
use Models\SeatsByEventsLine as SeatsByEventsLine;

class SeatsByEventDao extends SingletonDao implements ISeatsByEventDao
{
    private $connection;
    private $tableName = 'SeatsByEvent';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(SeatsByEvent $seatsByEvent)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $seatsByEvent->getName();
        $parameters["lastname"] = $seatsByEvent->getLastName();
        */
        $parameters = array_filter($seatsByEvent->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('SeatsByEvent agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $seatsByEvent = new SeatsByEvent();

        $seatsByEventProperties = array_keys($seatsByEvent->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$seatsByEventProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($seatsByEventProperties as $value) { //auto fill object with magic function __set
                    $seatsByEvent->__set($value, $row[$value]);
                }
            }

            return $seatsByEvent;
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar buscar SeatsByEvent: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar SeatsByEvent: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all SeatsByEventsLines as an array of SeatsByEventsLines
     */
    public function RetrieveAll()
    {
        $seatsByEventList = array();
        $seatsByEvent = new SeatsByEvent();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar SeatsByEvent: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar SeatsByEvent: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $seatsByEventProperties = array_keys($seatsByEvent->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $seatsByEvent = new SeatsByEvent();
            
            foreach ($seatsByEventProperties as $value) { //auto fill object with magic function __set
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
            if ($key != "idSeatsByEven") {
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
            echo "<script> alert('SeatsByEvent modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(SeatsByEvent $seatsByEvent)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$seatsByEventProperties[0]." = " . $seatsByEvent->getIdSeatsByEventsLine();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idSeatsByEvent = ".$seatsByEvent->getIdSeatsByEvent();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('SeatsByEvent eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
