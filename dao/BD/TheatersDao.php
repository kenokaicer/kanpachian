<?php
namespace dao\BD; /*Needs testing.*/

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Models\Theater as Theater;
use Dao\BD\SeatTypesDao as SeatTypesDao;

class TheatersDao extends SingletonDao implements ITheaterDao
{
    private $connection;
    private $seatTypesDao;
    private $tableName = 'Theaters';
    private $tableName2 = 'SeatTypes_x_Theater';

    //*-------------------------------------------------------------------------------------*//
    //When using this for copying other daos change Theater to primary object
    //and SeatType to secondary object, if more than one secondary object, 
    //code will need to be added.

    //Also code will need to be checked for secondary class as isn't inserting to a N:N table

    //Check array_pop if you need to unset a parameter that is an object or an array in primary object
    //*-------------------------------------------------------------------------------------*//

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }
    
    /**
     * Add Theater and all SeatTypes inside it to second table
     */
    public function Add(Theater $theater){

        $columns = "";
        $values = "";

        $parameters = array_filter($theater->getAll());
        array_pop($parameters); //unset SeatsType

        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        //---Add Theater---//

        $query = "INSERT INTO " . $this->tableName . " (".$columns.") VALUES (".$values.");";

        try {
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
            
        //---Get ID of the Theater inserted---//

        $query= "SELECT LAST_INSERT_ID()";

        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__.", Error getting last insert id. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception (__METHOD__.", Error getting last insert id. ".$ex->getMessage());
            return;
        }   
        $row = reset($resultSet); //gives first object of array
        $idTheater = reset($row); //get value of previous first object

        //---Insert each SeatType in a separate querry, N:N Table ---// //This could have been delegated to SeatTypesDao//

        foreach ($theater->getSeatTypes() as $value) {
            $query = "INSERT INTO ".$this->tableName2." (idSeatType, idTheater) VALUES (:idSeatType,:idTheater);";
            
            $parameters = array();
            $parameters["idSeatType"] = $value->getIdSeatType();
            $parameters["idTheater"] = $idTheater;
            var_dump($parameters);

            try {
                $addedRows = $this->connection->executeNonQuery($query, $parameters);
                if($addedRows!=1){
                    throw new Exception("Number of rows added ".$addedRows.", expected 1, in SeatType");
                }
            } catch (PDOException $ex) {
                throw new Exception (__METHOD__.", Error inserting SeatType. ".$ex->getMessage());
                return;
            } catch (Exception $ex) {
                throw new Exception (__METHOD__.", Error inserting SeatType. ".$ex->getMessage());
                return;
            }
        }
    }

    public function getByID($id){
        $this->seatTypesDao = SeatTypesDao::getInstance();
        $theater = new Theater();

        $query = "SELECT * FROM " . $this->tableName." 
        WHERE idTheater = ".$id." 
        AND enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error. ".$ex->getMessage());
            return;
        }

        $theaterProperties = array_keys($theater->getAll());
        array_pop($theaterProperties); //delete from properties list the object array, as we don't have it yet

        $row = reset($resultSet);

        foreach ($theaterProperties as $value) {
            $theater->__set($value, $row[$value]);
        }

        $seatTypesList = $this->seatTypesDao->getAllByTheaterId($theater->getIdTheater());
        $theater->setSeatTypes($seatTypesList);

        return $theater;
    }
    
    public function getAll(){
        $this->seatTypesDao = SeatTypesDao::getInstance();
        $theaterList = array();
        $theater = new Theater();

        $query = "SELECT * FROM " . $this->tableName." WHERE enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error. ".$ex->getMessage());
            return;
        }
        
        $theaterProperties = array_keys($theater->getAll());
        array_pop($theaterProperties);

        foreach ($resultSet as $row){
            $theater = new Theater();
            
            foreach ($theaterProperties as $value) {
                $theater->__set($value, $row[$value]);
            }

            array_push($theaterList, $theater);
        }

        foreach ($theaterList as $theater) {
            $seatTypesList = $this->seatTypesDao->getAllByTheaterId($theater->getIdTheater());
            $theater->setSeatTypes($seatTypesList);
        }

        return $theaterList;
    }

    public function Update(Theater $oldTheater, Theater $newTheater){}

    public function Delete(Theater $theater){
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idTheater = ".$theater->getIdArtist();

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