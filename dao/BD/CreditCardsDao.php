
<?php namespace Dao\BD; /*Needs testing.*/

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
        //See if having this here causes problems in the future, so far so good.
    }

    public function Add(CreditCard $creditCard)
    {
        $columns = "";
        $values = "";
        
        /*
        $parameters["name"] = $creditCard->getName();
        $parameters["lastname"] = $creditCard->getLastName();
        */
        $parameters = array_filter($creditCard->getAll()); //does the same as the above but automated, array filter unsets null values (id), or values not set

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
            echo "<script> alert('CreditCarda agregado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo agregar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function retrieveById($id)
    {   
        $creditCard = new CreditCard();

        $creditCardProperties = array_keys($creditCard->getAll()); //get propierty names from object for use in __set

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
            echo "<script> alert('Error al intentar buscar CreditCarda: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar buscar CreditCarda: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    /**
     * Returns all CreditCards as an array of CreditCards
     */
    public function RetrieveAll()
    {
        $creditCardList = array();
        $creditCard = new CreditCard();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('Error al intentar listar CreditCardas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('Error al intentar listar CreditCardas: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
        
        $creditCardProperties = array_keys($creditCard->getAll()); //get propierty names from object for use in __set

        foreach ($resultSet as $row) //loops returned rows
        {                
            $creditCard = new CreditCard();
            
            foreach ($creditCardProperties as $value) { //auto fill object with magic function __set
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
            echo "<script> alert('CreditCarda modificado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo modificar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }

    public function Delete(CreditCard $creditCard)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$creditCardProperties[0]." = " . $creditCard->getIdCreditCard();
        
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idCreditCard = ".$creditCard->getIdCreditCard();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
            }
            echo "<script> alert('CreditCarda eliminado exitosamente');</script>";
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el artista, codigo de error: " . str_replace("'", "", $ex->getMessage()) . "');</script>";
        }
    }
}
