<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ICreditCardDao as ICreditCardDao;
use Models\CreditCard as CreditCard;

class CreditCardsDao extends SingletonDao implements ICreditCardDao
{
    private $connection;
    private $tableName = 'CreditCards';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(CreditCard $creditCard)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($creditCard->getAll()); //get object atribute names 

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
        } catch (PDOException $ex) {
            throw new Exception ("Add error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Add error: ".$ex->getMessage());
        }
    }

    public function retrieveById($id)
    {   
        $creditCard = new CreditCard();

        $creditCardProperties = array_keys($creditCard->getAll()); //get atribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$creditCardProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($creditCardProperties as $value) { //auto fill object with magic function __set
                    $creditCard->__set($value, $row[$value]);
                }
            }

            return $creditCard;
        } catch (PDOException $ex) {
            throw new Exception ("CreditCard search error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("CreditCard search error: ".$ex->getMessage());
        }
    }

    public function GetAll()
    {
        $creditCardList = array();
        $creditCard = new CreditCard();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception ("GetAll error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("GetAll error: ".$ex->getMessage());
        }
        
        $creditCardProperties = array_keys($creditCard->getAll());

        foreach ($resultSet as $row)
        {                
            $creditCard = new CreditCard();
            
            foreach ($creditCardProperties as $value) {
                $creditCard->__set($value, $row[$value]);
            }

            array_push($creditCardList, $creditCard);
        }

        return $creditCardList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object CreditCard
     */
    public function Update(CreditCard $oldCreditCard, CreditCard $newCreditCard)
    {
        $valuesToModify = "";
        $oldCreditCardArray = $oldCreditCard->getAll(); //convert object to array of values
        $creditCardArray = $newCreditCard->getAll();

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

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idCreditCard = " . $oldCreditCard->getIdCreditCard();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Update error: ".$ex->getMessage());
        }
    }

    /**
     * Logical Delete
     */
    public function Delete(CreditCard $creditCard)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idCreditCard = ".$creditCard->getIdCreditCard();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception ("Delete error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Delete error: ".$ex->getMessage());
        }
    }
}
