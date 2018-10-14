
<?php namespace Dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IPurchaseLineDao as IPurchaseLineDao;
use Models\PurchaseLine as PurchaseLine;

class PurchaseLinesDao extends SingletonDao implements IPurchaseLineDao
{
    private $connection;
    private $tableName = 'PurchaseLines';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(PurchaseLine $purchaseLines)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $purchaseLines->getName();
        $parameters["lastname"] = $purchaseLines->getLastName();
        */
        $parameters = array_filter($purchaseLines->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('PurchaseLinea agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $purchaseLines = new PurchaseLine();

        $purchaseLinesProperties = array_keys($purchaseLines->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$purchaseLinesProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($purchaseLinesProperties as $value) { //auto fill object with magic function __set
                    $purchaseLines->__set($value, $row[$value]);
                }
            }

            return $purchaseLines;
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar buscar PurchaseLinea: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar PurchaseLinea: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all PurchaseLines as an array of PurchaseLines
     */
    public function RetrieveAll()
    {
        $purchaseLinesList = array();
        $purchaseLines = new PurchaseLine();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar PurchaseLineas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar PurchaseLineas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $purchaseLinesProperties = array_keys($purchaseLines->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $purchaseLines = new PurchaseLine();
            
            foreach ($purchaseLinesProperties as $value) { //auto fill object with magic function __set
                $purchaseLines->__set($value, $row[$value]);
            }

            array_push($purchaseLinesList, $purchaseLines);
        }

        return $purchaseLinesList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object PurchaseLine
     */
    public function Update(PurchaseLine $oldPurchaseLine, PurchaseLine $newPurchaseLine)
    {
        $valuesToModify = "";
        $oldPurchaseLineArray = $oldPurchaseLine->getAll(); //convert object to array of values
        $purchaseLinesArray = $newPurchaseLine->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldPurchaseLineArray as $key => $value) {
            if ($key != "idPurchaseLine") {
                if ($oldPurchaseLineArray[$key] != $purchaseLinesArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $purchaseLinesArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idPurchaseLine = " . $oldPurchaseLine->getIdPurchaseLine();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('PurchaseLinea modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(PurchaseLine $purchaseLines)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$purchaseLinesProperties[0]." = " . $purchaseLines->getIdPurchaseLine();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idPurchaseLine = ".$purchaseLines->getIdPurchaseLine();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('PurchaseLinea eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
