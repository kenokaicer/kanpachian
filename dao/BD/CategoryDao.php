<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ICategoryDao as ICategoryDao;
use Models\Category as Category;

class CategoryDao implements ICategoryDao
{
    private $connection;
    private $tableName = 'Categories';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Category $category)
    {
        $columns = "";
        $values = "";

        try {
            $parameters = array_filter($category->getAll()); //get object attribute names 

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

    public function getById($idCategory)
    {   
        $parameters = get_defined_vars();
        $category = null;

        try {
            $categoryAttributes = array_keys(Category::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName .
                " WHERE ".$categoryAttributes[0]." = :".key($parameters)."
                AND enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);
            
            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row) //loops returned rows
            {   
                $category =  new Category();            
                foreach ($categoryAttributes as $value) { //auto fill object with magic function __set
                    $category->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $category;
    }

    public function getByCategoryName($categoryName)
    {   
        $parameters = get_defined_vars();
        $category = null;

        try {
            $categoryAttributes = array_keys(Category::getAttributes()); //get attribute names from object for use in __set

            $query = "SELECT * FROM " . $this->tableName .
                " WHERE categoryName = :".key($parameters);
        
            $resultSet = $this->connection->Execute($query,$parameters);
            
            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row) //loops returned rows
            {   
                $category =  new Category();            
                foreach ($categoryAttributes as $value) { //auto fill object with magic function __set
                    $category->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $category;
    }

    public function getAll()
    {
        $categoryList = array();

        try{
            $query = "SELECT * FROM ".$this->tableName." WHERE enabled = 1";

            $resultSet = $this->connection->Execute($query);
        
            $categoryAttributes = array_keys(Category::getAttributes());

            foreach ($resultSet as $row)
            {                
                $category = new Category();
                
                foreach ($categoryAttributes as $value) {
                    $category->__set($value, $row[$value]);
                }

                array_push($categoryList, $category);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $categoryList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Category
     */
    public function Update(Category $oldCategory, Category $newCategory)
    {
        $valuesToModify = "";
       
        try {
            $oldCategoryArray = $oldCategory->getAll(); //convert object to array of values
            $categoryArray = $newCategory->getAll();
            $parameters["idCategory"] = $oldCategory->getIdCategory();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldCategoryArray as $key => $value) {
                if ($key != "idCategory") {
                    if ($oldCategoryArray[$key] != $categoryArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $categoryArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idCategory = :idCategory";
            
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
    public function Delete(Category $category)
    {
        try {
            $parameters["idCategory"] = $category->getIdCategory();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idCategory = :idCategory";

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
