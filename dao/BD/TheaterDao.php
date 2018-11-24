<?php
namespace dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\BD\DaoBD as DaoBD;
use Dao\BD\LoadType as LoadType;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Models\Theater as Theater;
use Models\SeatType as SeatType;

class TheaterDao extends DaoBD implements ITheaterDao
{
    protected $connection;
    private $seatTypeDao;
    private $tableName = 'Theaters';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameSeatTypesTheater = 'SeatTypes_x_Theater';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }
    
    /**
     * Add Theater and all SeatTypes inside it to second table
     */
    public function Add(Theater $theater){

        $columns = "";
        $values = "";

        try {
            $parameters = array_filter($theater->getAll());

            foreach ($parameters as $key => $value) {
                $columns .= $key.",";
                $values .= ":".$key.",";
            }
            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");

            //---Add Theater---//

            $query = "INSERT INTO " . $this->tableName . " (".$columns.") VALUES (".$values.");";

            $addedRows = $this->connection->executeNonQuery($query, $parameters);
            if($addedRows!=1){
                throw new Exception("Number of rows added ".$addedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
            return;
        }
            
        //---Get Id of the Theater inserted---//

         $idTheater = $this->lastInsertId(); //get value of previous first object

        //---Insert each SeatType in a separate querry, N:N Table ---//

        try {
            foreach ($theater->getSeatTypes() as $value) {
                $query = "INSERT INTO ".$this->tableNameSeatTypesTheater." (idSeatType, idTheater) 
                        VALUES (:idSeatType,:idTheater);";
                
                $parameters = array();
                $parameters["idSeatType"] = $value->getIdSeatType();
                $parameters["idTheater"] = $idTheater;

                $addedRows = $this->connection->executeNonQuery($query, $parameters);

                if($addedRows!=1){
                    throw new Exception("Number of rows added ".$addedRows.", expected 1, in SeatType");
                }
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.", Error inserting SeatType. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.", Error inserting SeatType. ".$ex->getMessage());
            return;
        }
    }

    /**
     * lazy load = no seatTypes
     */
    public function getById($idTheater, $load = LoadType::All){
        $parameters = get_defined_vars();
        array_pop($parameters);

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            if($load = LoadType::All){
                $query = "SELECT * FROM " . $this->tableName . " T
                    INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
                    ON T.idTheater = STT.idTheater
                    INNER JOIN " . $this->tableNameSeatType . " ST
                    ON STT.idSeatType = ST.idSeatType
                    WHERE STT.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableName . " T
                    WHERE STT.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
            }
            
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                if (!isset($theater)) { //load theater only on first loop
                    $theater = new Theater();
                    foreach ($theaterAttributes as $value) {
                        $theater->__set($value, $row[$value]);
                    }
                }

                if($theater->getIdTheater() != $row["idTheater"]){
                    throw new Exception(__METHOD__."More than one theater returned, expected only one");
                }

                if($load = LoadType::All){
                    $seatType = new SeatType();
                    foreach ($seatTypeAttributes as $value) {
                        $seatType->__set($value, $row[$value]);
                    }

                    $theater->addSeatType($seatType);
                }  
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        }

        return $theater;
    }
    
    public function getAll()
    {
        $theaterList = array();

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " T
                    INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
                    ON T.idTheater = STT.idTheater
                    INNER JOIN " . $this->tableNameSeatType . " ST
                    ON STT.idSeatType = ST.idSeatType
                    WHERE T.enabled = 1 
                    ORDER BY STT.idTheater, STT.idSeatType";
        
            $resultSet = $this->connection->Execute($query);

            $i=0;
            foreach ($resultSet as $row) {
                if (!isset($theaterList[0]) || ($theaterList[$i-1]->getIdTheater() != $row["idTheater"])) { //load theater only on first loop
                    $theaterList[$i] = new Theater();
                    foreach ($theaterAttributes as $value) {
                        $theaterList[$i]->__set($value, $row[$value]);
                    }   
                    $i++;     
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $theaterList[$i-1]->addSeatType($seatType);
            }
            /**
             * Alternative Method to fill the list array
             */
            /*foreach ($resultSet as $row) {
                if(isset($theater) && ($theater->getIdTheater() != $row["idTheater"])){ //push only when id changes
                    array_push($theaterList, $theater);
                }

                if (!isset($theater) || ($theater->getIdTheater() != $row["idTheater"])) { //load theater only on first loop
                    $theater = new Theater();
                    foreach ($theaterAttributes as $value) {
                        $theater->__set($value, $row[$value]);
                    }     
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $theater->addSeatType($seatType);
            }
            if(isset($theaterList)){
                array_push($theaterList, $theater); //push last theater
            }*/
            
             
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        }

        return $theaterList;
    }

    public function Update(Theater $oldTheater, Theater $newTheater)
    {
        $valuesToModify = "";
       
        try {
            $oldTheaterArray = $oldTheater->getAll(); //convert object to array of values
            $theaterArray = $newTheater->getAll();
            $parameters["idTheater"] = $oldTheater->getIdTheater();

            if(is_null($theaterArray["image"])){ //if image is null don't change it
                unset($theaterArray["image"]);
                unset($oldTheaterArray["image"]);
            }

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldTheaterArray as $key => $value) {
                if ($key != "idTheater") {
                    if ($oldTheaterArray[$key] != $theaterArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $theaterArray[$key];
                    }
                }
            }

            $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

            if($valuesToModify != '')
            {
                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idTheater = :idTheater";

                $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
            }

            $this->updateSeatTypes($oldTheater,$newTheater);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Checks difference in seatTypes, adds, and removes if neccesary
     */
    private function updateSeatTypes(Theater $oldTheater, Theater $newTheater)
    {
        try {
            $newSeatTypeArr = array();
            $oldSeatTypeArr = array();
            $idTheater = $oldTheater->getIdTheater();

            foreach ($newTheater->getSeatTypes() as $seatType) {
                $newSeatTypeArr[] = $seatType->getIdSeatType();
            }

            foreach ($oldTheater->getSeatTypes() as $seatType) {
                $oldSeatTypeArr[] = $seatType->getIdSeatType();
            }
            
            foreach ($newSeatTypeArr as $value) {
                if(!in_array($value,$oldSeatTypeArr)){ //if new entry is not in old array add it to database
                    $query = "INSERT INTO ".$this->tableNameSeatTypesTheater." (idSeatType, idTheater) 
                        VALUES (:idSeatType,:idTheater);";
                    
                    $parameters2 = array();
                    $parameters2["idSeatType"] = $value;
                    $parameters2["idTheater"] = $idTheater;

                    $this->connection->executeNonQuery($query, $parameters2);
                }
            }

            foreach ($oldSeatTypeArr as $value) {
                if(!in_array($value,$newSeatTypeArr)){  //if old entry is not in new array delete it from database
                    $query = "DELETE FROM ".$this->tableNameSeatTypesTheater." 
                    WHERE idSeatType = :idSeatType 
                    AND idTheater = :idTheater";
                    
                    $parameters2 = array();
                    $parameters2["idSeatType"] = $value;
                    $parameters2["idTheater"] = $idTheater;

                    $this->connection->executeNonQuery($query, $parameters2);
                }
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
    public function Delete(Theater $theater)
    {
        try {
            $parameters["idTheater"] = $theater->getIdTheater();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idTheater = :idTheater";

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