<?php namespace Dao\BD; //This one doesn't need a controller and views?

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ICreditCardDao as ICreditCardDao;
use Models\CreditCard as CreditCard;

class CreditCardDao extends SingletonDao implements ICreditCardDao
{
    private $connection;
    private $tableName = 'CreditCards';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(CreditCard $creditCard)
    {
        $columns = "";
        $values = "";
        
        try { 
            $parameters = array_filter($creditCard->getAll()); //get object attribute names 

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

    public function getByID($idClient)
    {   
        $parameters = get_defined_vars();
        $creditCard = null;

        try {

            $creditCardAttributes = array_keys(CreditCard::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName .
                " WHERE ".$creditCardAttributes[0]." = :".key($parameters)." 
                AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query);

            if(lenght($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned more than 1 result, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $creditCard = new CreditCard();
                foreach ($creditCardAttributes as $value) { //auto fill object with magic function __set
                    $creditCard->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $creditCard;
    }

    public function getAll()
    {
        $creditCardList = array();
        
        try{

            $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

            $resultSet = $this->connection->Execute($query);
        
            $creditCardAttributes = array_keys($creditCard->getAll());

            foreach ($resultSet as $row)
            {                
                $creditCard = new CreditCard();
                
                foreach ($creditCardAttributes as $value) {
                    $creditCard->__set($value, $row[$value]);
                }

                array_push($creditCardList, $creditCard);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $creditCardList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object CreditCard
     */
    public function Update(CreditCard $oldCreditCard, CreditCard $newCreditCard)
    {
        $valuesToModify = "";
       
        try {
            $oldCreditCardArray = $oldCreditCard->getAll(); //convert object to array of values
            $creditCardArray = $newCreditCard->getAll();
            $parameters["idCreditCard"] = $oldCreditCard->getIdCreditCard();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldCreditCardArray as $key => $value) {
                if ($key != "idCreditCard") {
                    if ($oldCreditCardArray[$key] != $creditCardArray[$key]) {
                        $valuesToModify .= $key . " = " . "'" . $creditCardArray[$key] . "', ";
                    }
                }
            }

            $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

            $query = "UPDATE ".$this->tableName." 
                SET ".$valuesToModify." 
                WHERE idCreditCard = :idCreditCard";
        
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

    public function Delete(CreditCard $creditCard)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$creditCardAttributes[0]." = " . $creditCard->getIdCreditCard();
        try {
            $parameters["idCreditCard"] = $creditCard->getIdCreditCard();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idCreditCard = :idCreditCard";

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
