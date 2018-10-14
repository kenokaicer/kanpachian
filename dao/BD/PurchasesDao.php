
<?php namespace Dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IPurchaseDao as IPurchaseDao;
use Models\Purchase as Purchase;

class PurchasesDao extends SingletonDao implements IPurchaseDao
{
    private $connection;
    private $tableName = 'Purchases';

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(Purchase $purchase)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $purchase->getName();
        $parameters["lastname"] = $purchase->getLastName();
        */
        $parameters = array_filter($purchase->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('Purchasea agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $purchase = new Purchase();

        $purchaseProperties = array_keys($purchase->getAll()); //get propierty names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$purchaseProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($purchaseProperties as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }
            }

            return $purchase;
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar buscar Purchasea: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar Purchasea: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all Purchases as an array of Purchases
     */
    public function RetrieveAll()
    {
        $purchaseList = array();
        $purchase = new Purchase();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar Purchaseas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar Purchaseas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $purchaseProperties = array_keys($purchase->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $purchase = new Purchase();
            
            foreach ($purchaseProperties as $value) { //auto fill object with magic function __set
                $purchase->__set($value, $row[$value]);
            }

            array_push($purchaseList, $purchase);
        }

        return $purchaseList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Purchase
     */
    public function Update(Purchase $oldPurchase, Purchase $newPurchase)
    {
        $valuesToModify = "";
        $oldPurchaseArray = $oldPurchase->getAll(); //convert object to array of values
        $purchaseArray = $newPurchase->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldPurchaseArray as $key => $value) {
            if ($key != "idPurchase") {
                if ($oldPurchaseArray[$key] != $purchaseArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $purchaseArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idPurchase = " . $oldPurchase->getIdPurchase();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Purchasea modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(Purchase $purchase)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$purchaseProperties[0]." = " . $purchase->getIdPurchase();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idPurchase = ".$purchase->getIdPurchase();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('Purchasea eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
