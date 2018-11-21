<?php namespace Dao\BD; //This one doesn't need a controller and views?

use Dao\BD\Connection as Connection;
use Dao\BD\DaoBD as DaoBD;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ICreditCardDao as ICreditCardDao;
use Models\CreditCard as CreditCard;

class CreditCardDao extends DaoBD implements ICreditCardDao
{
    protected $connection;
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

            $idCreditCard = $this->lastInsertId(); 
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $idCreditCard;
    }

    public function getById($idClient)
    {   
        $parameters = get_defined_vars();
        $creditCard = null;

        try {
            $creditCardAttributes = array_keys(CreditCard::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName .
                " WHERE ".$creditCardAttributes[0]." = :".key($parameters)." 
                AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
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

    /**
     * used to check if there's a card already in BD
     */
    public function getByCreditCardNumber($creditCardNumber)
    {   
        $parameters = get_defined_vars();
        $creditCard = null;

        try {
            $creditCardAttributes = array_keys(CreditCard::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName ." 
                WHERE creditCardNumber = :".key($parameters)." 
                AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
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
        
            $creditCardAttributes = array_keys(CreditCard::getAttributes());

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
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $creditCardArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idCreditCard = :idCreditCard";
            
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
    public function Delete(CreditCard $creditCard)
    {
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
