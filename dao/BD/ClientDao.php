<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IClientDao as IClientDao;
use Models\Client as Client;
use Models\User as User;
use Model\CreditCard as CreditCard;

class ClientDao extends SingletonDao implements IClientDao
{
    private $connection;
    private $tableName = 'Clients';
    private $tableNameUser = 'Users';
    private $tableNameCreditCard = 'CreaditCards';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Client $client)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($client->getAll()); //get object attribute names 
        array_pop($parameters);
        array_pop($parameters);
        $parameters["idCreditCard"] = $client->getCreditCard()->getIdCreditCard();
        $parameters["idUser"] = $client->getUser()->getIdUser();

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
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function getByID($id) 
    {   
        $client = new Client();
        $user = new User();
        $creditCard = new CreditCard();

        $clientAttributes = array_keys($client->getAll()); //get atribute names from object for use in __set
        array_pop($clientAttributes);
        array_pop($clientAttributes);

        $userAttributes = array_keys($user->getAll());

        $creditCardAttributes = array_keys($creditCard->getAll());

        $query = "SELECT *
                FROM " . $this->tableName ." C
                INNER JOIN ".$this->tableNameUser." U 
                ON C.idUser = U.idUser
                INNER JOIN ".$this->tableNameCreditCard." CC 
                ON C.idCreditCard = CC.idCreditCard
                WHERE ".$clientAttributes[0]." = ".$id." 
                AND C.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        $row = reset($resultSet);
   
        foreach ($clientAttributes as $value) { //auto fill object with magic function __set
            $client->__set($value, $row[$value]);
        }

        foreach ($userAttributes as $value) {
            $user->__set($value, $row[$value]);
        }

        $client->setUser($user);

        foreach ($creditCardAttributes as $value) {
            $creditCard->__set($value, $row[$value]);
        }

        $client->setCreditCard($creditCard);

        return $client;
    }

    public function getAll()
    {
        $clientList = array();
        $client = new Client();
        $user = new User();
        $creditCard = new CreditCard();

        $clientAttributes = array_keys($client->getAll()); //get atribute names from object for use in __set
        array_pop($clientAttributes);
        array_pop($clientAttributes);

        $userAttributes = array_keys($user->getAll());

        $creditCardAttributes = array_keys($creditCard->getAll());

        $query = "SELECT *
                FROM " . $this->tableName ." C
                INNER JOIN ".$this->tableNameUser." U 
                ON C.idUser = U.idUser
                INNER JOIN ".$this->tableNameCreditCard." CC 
                ON C.idCreditCard = CC.idCreditCard
                WHERE ".$clientAttributes[0]." = ".$id." 
                AND C.enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        foreach ($resultSet as $row) {
            foreach ($clientAttributes as $value) { //auto fill object with magic function __set
                $client->__set($value, $row[$value]);
            }

            foreach ($userAttributes as $value) {
                $user->__set($value, $row[$value]);
            }

            $client->setUser($user);

            foreach ($creditCardAttributes as $value) {
                $creditCard->__set($value, $row[$value]);
            }

            $client->setCreditCard($creditCard);

            array_push($clientList, $client);
        }

        return $clientList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Client
     */
    public function Update(Client $oldClient, Client $newClient){}

    /**
     * Logical Delete
     */
    public function Delete(Client $client)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idClient = ".$client->getIdClient();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
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
