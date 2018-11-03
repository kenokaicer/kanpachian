<?php
namespace dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Models\Theater as Theater;
use Models\SeatType as SeatType;

class TheaterDao extends SingletonDao implements ITheaterDao
{
    private $connection;
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
                $query = "INSERT INTO ".$this->tableName2." (idSeatType, idTheater) 
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

    public function getById($idTheater){
        $parameters = get_defined_vars();

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " T
                    INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
                    ON T.idTheater = STT.idTheater
                    INNER JOIN " . $this->tableNameSeatType . " ST
                    ON STT.idSeatType = ST.idSeatType
                    WHERE STT.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                if (!isset($theater)) { //load theater only on first loop
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
                    WHERE T.enabled = 1";
        
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) {
                if (($theater->getIdTheater() != $row["idTheater"]) || !isset($theater)) { //load theater only on first loop
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
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        }

        return $theaterList;
    }

    public function Update(Theater $oldTheater, Theater $newTheater){}

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
}