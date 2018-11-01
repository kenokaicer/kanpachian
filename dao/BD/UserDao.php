<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IUserDao as IUserDao;
use Models\User as User;

class UserDao extends SingletonDao implements IUserDao
{
    private $connection;
    private $tableName = 'Users';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(User $user)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($user->getAll()); //get object atribute names 

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
        $user = new User();
        $userAttributes = array_keys($user->getAll()); //get atribute names from object for use in __set

        $parameters["id"] = $id;

        $query = "SELECT * FROM " . $this->tableName." 
            WHERE ".$userAttributes[0]." = :id
            AND enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($userAttributes as $value) { //auto fill object with magic function __set
                    $user->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $user;
    }

    public function getByUsername($username)
    { 
        $user = new User();

        $userAttributes = array_keys($user->getAll()); //get atribute names from object for use in __set

        $parameters["username"] = $username;

        $query = "SELECT * FROM " . $this->tableName ." 
            WHERE username = :username
            AND enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($userAttributes as $value) { //auto fill object with magic function __set
                    $user->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $user;
    }

    public function getAll()
    {
        $userList = array();
        $user = new User();

        $query = "SELECT * FROM ".$this->tableName." 
            WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $userAttributes = array_keys($user->getAll());

        foreach ($resultSet as $row)
        {                
            $user = new User();
            
            foreach ($userAttributes as $value) {
                $user->__set($value, $row[$value]);
            }

            array_push($userList, $user);
        }

        return $userList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object User
     */
    public function Update(User $oldUser, User $newUser)
    {
        $valuesToModify = "";
        $oldUserArray = $oldUser->getAll(); //convert object to array of values
        $userArray = $newUser->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldUserArray as $key => $value) {
            if ($key != "idUser") {
                if ($oldUserArray[$key] != $userArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $userArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idUser = " . $oldUser->getIdUser();
        
        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array()); //no parameters needed so sending an empty array
            if($modifiedRows!=1){
                throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
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
    public function Delete(User $user)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idUser = ".$user->getIdUser();

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
