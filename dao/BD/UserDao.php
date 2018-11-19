<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IUserDao as IUserDao;
use Models\User as User;

class UserDao implements IUserDao
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
        
        try { 
            $parameters = array_filter($user->getAll()); //get object attribute names 

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

    public function getById($idUser)
    {   
        $parameters = get_defined_vars();
        $user = null;

        try {
            $userAttributes = array_keys(User::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName." 
                WHERE ".$userAttributes[0]." = :".key($parameters)." 
                AND Enabled = 1";
        
            $resultSet = $this->connection->Execute($query, $parameters);

            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row) //loops returned rows
            {      
                $user =  new User();
                
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

    /**
     * Return even deleted usernames
     * Type: enabled default, disabled, returns logical deleted entries
     */
    public function getByUsername($username, $type = "enabled")
    { 
        $parameters = get_defined_vars();
        array_pop($parameters);
        $user = null;

        try {
            $userAttributes = array_keys(User::getAttributes());

            $parameters["username"] = $username;

            if($type == "enabled"){
                $query = "SELECT * FROM " . $this->tableName ." 
                WHERE username = :".key($parameters)." 
                AND Enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableName ." 
                WHERE username = :".key($parameters);
            }
            
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row)
            {      
                $user = new User();         
                foreach ($userAttributes as $value) {
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

        try{
            $query = "SELECT * FROM ".$this->tableName." 
                WHERE enabled = 1";
        
            $resultSet = $this->connection->Execute($query);
        
            $userAttributes = array_keys(User::getAttributes());

            foreach ($resultSet as $row)
            {                
                $user = new User();
                
                foreach ($userAttributes as $value) {
                    $user->__set($value, $row[$value]);
                }

                array_push($userList, $user);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $userList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object User
     */
    public function Update(User $oldUser, User $newUser)
    {
        $valuesToModify = "";
       
        try {
            $oldUserArray = $oldUser->getAll(); //convert object to array of values
            $userArray = $newUser->getAll();
            $parameters["idUser"] = $oldUser->getIdUser();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldUserArray as $key => $value) {
                if ($key != "idUser") {
                    if ($oldUserArray[$key] != $userArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $userArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idUser = :idUser";
            
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
    public function Delete(User $user)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$userAttributes[0]." = " . $user->getIdUser();
        try {
            $parameters["idUser"] = $user->getIdUser();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idUser = :idUser";

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
