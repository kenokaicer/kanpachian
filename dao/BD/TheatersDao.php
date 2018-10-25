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

class TheatersDao extends SingletonDao implements ITheaterDao
{
    private $connection;
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
     * Add Theater and all SeatTypes inide it to second table
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
            throw new Exception ("Add error: ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception ("Add error: ".$ex->getMessage());
            return;
        }
            
        //---Get ID of the Theater inserted---//

        $query= "SELECT LAST_INSERT_ID()";

        try {
            $resultSet = $this->connection->Execute($query);  
        } catch (PDOException $ex) {
            throw new Exception ("Error getting last insert id. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception ("Error getting last insert id. ".$ex->getMessage());
            return;
        }   
        $row = reset($resultSet); //gives first object of array
        $idTheater = reset($row); //get value of previous first object

        //---Insert each SeatType in a separate querry, N:N Table ---//

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
                throw new Exception ("Error inserting SeatType. ".$ex->getMessage());
                return;
            } catch (Exception $ex) {
                throw new Exception ("Error inserting SeatType. ".$ex->getMessage());
                return;
            }
        }
    }

    public function Retrieve($var){
    }
    
    public function RetrieveAll(){
        $theaterList = array();
        $theater = new Theater();
        $seatType = new SeatType();

        $query = "SELECT * FROM " . $this->tableName." WHERE enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception ("Theater list error. ".$ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception ("Theater list error. ".$ex->getMessage());
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

        //----------------This should be done by SeatTypes controller, method "get all seattypes by theater id"
        //------Should return an array, and then set that array
        $query = "SELECT SeatTypes.idSeatType, name, description, idTheater 
        FROM " . $this->tableName2." 
        INNER JOIN SeatTypes 
        ON SeatTypes_x_Theater.idSeatType = SeatTypes.idSeatType 
        ORDER BY idTheater, SeatTypes.idSeatType";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            echo "<script> alert('SeatType listing error, code: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('SeatType listing error, code: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }

        $seatTypeProperties = array_keys($seatType->getAll());

        foreach ($resultSet as $row){
            $seatType = new SeatType();

            foreach ($seatTypeProperties as $value) {
                $seatType->__set($value, $row[$value]);
            }

            if($theater->getIdTheater()!=$row["idTheater"]){
                foreach ($theaterList as $value) {
                    if($row["idTheater"]==$value->getIdTheater()){
                        $theater = $value;
                        break;
                    }
                }
            }

            $theater->addSeatType($seatType);
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
            throw new Exception ("Delete error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception ("Delete error: ".$ex->getMessage());
        }
    }
}