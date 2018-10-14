
<?php namespace Dao\BD;/* Need to test */

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITicketDao as ITicketDao;
use Models\Ticket as Ticket;

class TicketsDao extends SingletonDao implements ITicketDao
{
    private $connection;
    private $tableName = 'Tickets';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(Ticket $ticket)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $ticket->getName();
        $parameters["lastname"] = $ticket->getLastName();
        */
        $parameters = array_filter($ticket->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('Ticketa agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $ticket = new Ticket();

        $ticketProperties = array_keys($ticket->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$ticketProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($ticketProperties as $value) { //auto fill object with magic function __set
                    $ticket->__set($value, $row[$value]);
                }
            }

            return $ticket;
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar buscar Ticketa: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar Ticketa: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all Tickets as an array of Tickets
     */
    public function RetrieveAll()
    {
        $ticketList = array();
        $ticket = new Ticket();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar Ticketas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Ticketas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $ticketProperties = array_keys($ticket->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $ticket = new Ticket();
            
            foreach ($ticketProperties as $value) { //auto fill object with magic function __set
                $ticket->__set($value, $row[$value]);
            }

            array_push($ticketList, $ticket);
        }

        return $ticketList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Ticket
     */
    public function Update(Ticket $oldTicket, Ticket $newTicket)
    {
        $valuesToModify = "";
        $oldTicketArray = $oldTicket->getAll(); //convert object to array of values
        $ticketArray = $newTicket->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldTicketArray as $key => $value) {
            if ($key != "idTicket") {
                if ($oldTicketArray[$key] != $ticketArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $ticketArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idTicket = " . $oldTicket->getIdTicket();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Ticketa modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(Ticket $ticket)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$ticketProperties[0]." = " . $ticket->getIdTicket();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idTicket = ".$ticket->getIdTicket();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Ticketa eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
