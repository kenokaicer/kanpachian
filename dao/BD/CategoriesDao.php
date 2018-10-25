<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ICategoryDao as ICategoryDao;
use Models\Category as Category;

class CategoriesDao extends SingletonDao implements ICategoryDao
{
    private $connection;
    private $tableName = 'Categories';

    protected function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Category $category)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($category->getAll()); //get object atribute names 

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
        $category = new Category();

        $categoryProperties = array_keys($category->getAll()); //get atribute names from object for use in __set

        $query = "SELECT * FROM " . $this->tableName .
            " WHERE ".$categoryProperties[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) //loops returned rows
            {               
                foreach ($categoryProperties as $value) { //auto fill object with magic function __set
                    $category->__set($value, $row[$value]);
                }
            }

            return $category;
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    public function getAll()
    {
        $categoryList = array();
        $category = new Category();

        $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

        try{
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        $categoryProperties = array_keys($category->getAll());

        foreach ($resultSet as $row)
        {                
            $category = new Category();
            
            foreach ($categoryProperties as $value) {
                $category->__set($value, $row[$value]);
            }

            array_push($categoryList, $category);
        }

        return $categoryList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Category
     */
    public function Update(Category $oldCategory, Category $newCategory)
    {
        $valuesToModify = "";
        $oldCategoryArray = $oldCategory->getAll(); //convert object to array of values
        $categoryArray = $newCategory->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldCategoryArray as $key => $value) {
            if ($key != "idCategory") {
                if ($oldCategoryArray[$key] != $categoryArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $categoryArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idCategory = " . $oldCategory->getIdCategory();
        
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
    public function Delete(Category $category)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idCategory = ".$category->getIdCategory();

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
