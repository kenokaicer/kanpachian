<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IClientDao as IClientDao;
use Models\Client as Client;
use Models\User as User;
use Models\CreditCard as CreditCard;

class ClientDao extends SingletonDao implements IClientDao
{
    private $connection;
    private $tableName = 'Clients';
    private $tableNameUser = 'Users';
    private $tableNameCreditCard = 'CreditCards';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Client $client)
    {
        //--Add user--//
        try{
            $this->addUser($client->getUser());
            $idUser = $this->lastInsertId();
        }catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        //------------//

        try {
            $columns = "";
            $values = "";
            
            $parameters = array_filter($client->getAll()); //get object attribute names 
            $parameters["idUser"] = $idUser; 

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

    private function addUser(User $user)
    {
        try {
            $columns = "";
            $values = "";
            
            $parameters = array_filter($user->getAll()); //get object attribute names 

            foreach ($parameters as $key => $value) {
                $columns .= $key.",";
                $values .= ":".$key.",";
            }
            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");

            $query = "INSERT INTO " . $this->tableNameUser . " (".$columns.") VALUES (".$values.");";
         
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

    public function getById($idClient) 
    {   
        $parameters = get_defined_vars();
        $client = null;
        
        try {
            $clientAttributes = array_keys(Client::getAttributes()); //get attribute names from object for use in __set

            $userAttributes = array_keys(User::getAttributes());

            $creditCardAttributes = array_keys(CreditCard::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." C
                    INNER JOIN ".$this->tableNameUser." U 
                    ON C.idUser = U.idUser
                    INNER JOIN ".$this->tableNameCreditCard." CC 
                    ON C.idCreditCard = CC.idCreditCard
                    WHERE ".$clientAttributes[0]." = :".key($parameters)." 
                    AND C.enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);  
        
            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $client = new Client();
                foreach ($clientAttributes as $value) { //auto fill object with magic function __set
                    $client->__set($value, $row[$value]);
                }
                
                $user = new User();
                foreach ($userAttributes as $value) {
                    $user->__set($value, $row[$value]);
                }
    
                $client->setUser($user);
                
                $creditCard = new CreditCard();
                foreach ($creditCardAttributes as $value) {
                    $creditCard->__set($value, $row[$value]);
                }
    
                $client->setCreditCard($creditCard);
            } 
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $client;
    }

    public function getByUserId($idUser)
    {   
        $parameters = get_defined_vars();
        $client = null;

        try {
            $clientAttributes = array_keys(Client::getAttributes()); //get attribute names from object for use in __set

            $creditCardAttributes = array_keys(CreditCard::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." C
                    INNER JOIN ".$this->tableNameCreditCard." CC 
                    ON C.idCreditCard = CC.idCreditCard
                    WHERE idUser = :".key($parameters)." 
                    AND C.enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);  
            var_dump($resultSet);
            die();
            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $client = new Client();
                foreach ($clientAttributes as $value) { //auto fill object with magic function __set
                    $client->__set($value, $row[$value]);
                }

                $user = new User();
                foreach ($userAttributes as $value) {
                    $user->__set($value, $row[$value]);
                }

                $client->setUser($user);

                $creditCard = new CreditCard();
                foreach ($creditCardAttributes as $value) {
                    $creditCard->__set($value, $row[$value]);
                }

                $client->setCreditCard($creditCard);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $client;
    }

    public function getAll()
    {
        $clientList = array();

        try {
            $clientAttributes = array_keys(Client::getAttributes()); //get attribute names from object for use in __set

            $userAttributes = array_keys(User::getAttributes());

            $creditCardAttributes = array_keys(CreditCard::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." C
                    INNER JOIN ".$this->tableNameUser." U 
                    ON C.idUser = U.idUser
                    INNER JOIN ".$this->tableNameCreditCard." CC 
                    ON C.idCreditCard = CC.idCreditCard
                    WHERE C.enabled = 1";
        
            $resultSet = $this->connection->Execute($query); 

            foreach ($resultSet as $row) {
                $client = new Client();
                foreach ($clientAttributes as $value) { //auto fill object with magic function __set
                    $client->__set($value, $row[$value]);
                }

                $user = new User();
                foreach ($userAttributes as $value) {
                    $user->__set($value, $row[$value]);
                }

                $client->setUser($user);

                $creditCard = new CreditCard();
                foreach ($creditCardAttributes as $value) {
                    $creditCard->__set($value, $row[$value]);
                }

                $client->setCreditCard($creditCard);

                array_push($clientList, $client);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
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
        try {
            $parameters["idClient"] = $client->getIdClient();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idClient = :idClient";

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

    public function lastInsertId()
    {
        try {
            $query = "SELECT LAST_INSERT_Id()";

            $resultSet = $this->connection->Execute($query);

            $row = reset($resultSet); //gives first object of array
            $id = reset($row); //get value of previous first object
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        }

        return $id;
    }

    /**
     * Adds object CreditCard
     */
    public function addCreditCardByClientIdComplete($idClient, CreditCard $creditCard)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters = array_filter($creditCard->getAll());

            foreach ($parameters as $key => $value) {
                $columns .= $key.",";
                $values .= ":".$key.",";
            }
            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");

            $query = "INSERT INTO ".$this->tableNameCreditCard." (".$columns.") VALUES (".$values.");";

            $addedRows = $this->connection->executeNonQuery($query, $parameters);
            if($addedRows!=1){
                throw new Exception("Number of rows added ".$addedRows.", expected 1");
            }

            $parameters = array();

            $parameters["idCreditCard"] = $this->lastInsertId();
            $parameters["idClient"] = $idClient;

            $query = "UPDATE ".$this->tableName." 
                    SET idCreditCard = :idCreditCard
                    WHERE idClient = :idClient";

            $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
        
            if($modifiedRows!=1){
                throw new Exception("Number of rows modified ".$modifiedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Sets id of CreditCard only
     */
    public function addCreditCardByClientId($idClient, $idCreditCard)
    {
        try{
            $parameters["idCreditCard"] = $idCreditCard;
            $parameters["idClient"] = $idClient;

            $query = "UPDATE ".$this->tableName." 
                    SET idCreditCard = :idCreditCard
                    WHERE idClient = :idClient";

            $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
        
            if($modifiedRows!=1){
                throw new Exception("Number of rows modified ".$modifiedRows.", expected 1");
            }
        
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }
}
