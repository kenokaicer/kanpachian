
<?php namespace Dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IClientDao as IClientDao;
use Models\Client as Client;

class ClientsDao extends SingletonDao implements IClientDao
{
    private $connection;
    private $tableName = 'Clients';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(Client $client)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $client->getName();
        $parameters["lastname"] = $client->getLastName();
        */
        $parameters = array_filter($client->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('Clienta agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $client = new Client();

        $clientProperties = array_keys($client->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$clientProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($clientProperties as $value) { //auto fill object with magic function __set
                    $client->__set($value, $row[$value]);
                }
            }

            return $client;
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar buscar Clienta: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar Clienta: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all Clients as an array of Clients
     */
    public function RetrieveAll()
    {
        $clientList = array();
        $client = new Client();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar Clientas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Clientas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $clientProperties = array_keys($client->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $client = new Client();
            
            foreach ($clientProperties as $value) { //auto fill object with magic function __set
                $client->__set($value, $row[$value]);
            }

            array_push($clientList, $client);
        }

        return $clientList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Client
     */
    public function Update(Client $oldClient, Client $newClient)
    {
        $valuesToModify = "";
        $oldClientArray = $oldClient->getAll(); //convert object to array of values
        $clientArray = $newClient->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldClientArray as $key => $value) {
            if ($key != "idClient") {
                if ($oldClientArray[$key] != $clientArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $clientArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idClient = " . $oldClient->getIdClient();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Clienta modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(Client $client)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$clientProperties[0]." = " . $client->getIdClient();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idClient = ".$client->getIdClient();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Clienta eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
