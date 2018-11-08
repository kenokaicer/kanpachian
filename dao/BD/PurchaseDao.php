<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IPurchaseDao as IPurchaseDao;
use Models\Purchase as Purchase;
use Models\Client as Client;

class EventDao implements IPurchaseDao
{
    private $connection;
    private $tableName = 'Pruchases';
    private $tableName2 = 'Clients';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Purchase $purchase)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters["date"] = $purchase->getDate();
            $parameters["idCategory"] = $purchase->getClient()->getIdClient();

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

    public function getById($idPurchase)
    {   
        $parameters = get_defined_vars();
        $purchase = null;

        try {
            $purchaseAttributes = array_keys(Purchase::getAttributes());

            $clientAttributes = array_keys(Client::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." P
                    INNER JOIN ".$this->tableName2." C
                    ON P.idClient = C.idClient  
                    WHERE ".$purchaseAttributes[0]." = :".key($parameters)." 
                    AND P.enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned more than 1 result, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $purchase = new Purchase();
                foreach ($purchaseAttributes as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }

                $client = new Client();
                foreach ($clientAttributes as $value) {
                    $client->__set($value, $row[$value]);
                }
                //Missing purchaselines
                $purchase->setClient($client);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $purchase;
    }

    public function getAll()
    {
        $purchaseList = array();

        try {
            $purchaseAttributes = array_keys(Purchase::getAttributes());

            $clientAttributes = array_keys(Client::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." P
                    INNER JOIN ".$this->tableName2." C
                    ON P.idClient = C.idClient  
                    WHERE P.enabled = 1";

            $resultSet = $this->connection->Execute($query);
        
            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            foreach ($resultSet as $row)
            {
                $purchase = new Purchase();
                foreach ($purchaseAttributes as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }

                $client = new Client();
                foreach ($clientAttributes as $value) {
                    $client->__set($value, $row[$value]);
                }

                $purchase->setClient($client);

                array_push($purchaseList, $purchase);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $purchaseList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Purchase
     */
    public function Update(Purchase $oldPurchase, Purchase $newPurchase){
        $valuesToModify = "";
       
        try {
            $oldPurchaseArray = $oldPurchase->getAll(); //convert object to array of values
            $purchaseArray = $newPurchase->getAll();
            $parameters["idPurchase"] = $oldPurchase->getIdPurchase();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldPurchaseArray as $key => $value) {
                if ($key != "idPurchase") {
                    if ($oldPurchaseArray[$key] != $purchaseArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $purchaseArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idPurchase = :idPurchase";
            
                $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
                
                if($modifiedRows!=1){
                    throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
                }
            }else{
                throw new Exception("No hay datos para modificar, ningún campo nuevo ingresado");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Logical Delete
     */
    public function Delete(Purchase $purchase)
    {
        try {
            $parameters["idPurchase"] = $purchase->getIdPurchase();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idPurchase = :idPurchase";

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
