<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IPurchaseLineDao as IPurchaseLineDao;
use Models\PurchaseLine as PurchaseLine;
use Models\Category as Category;

class PurchaseLineDao implements IPurchaseLineDao
{
    private $connection;
    private $tableName = 'PurchaseLines';
    private $tableNameSeatsByEvent = 'SeatsByEvents';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameEventByDate = 'EventByDates';
    private $tableNameEvent = 'Events';
    private $tableNameCatergory = 'Categories';
    private $tableNameTheater = 'Theaters';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(PurchaseLine $purchaseLine)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters = array_filter($purchaseLine->getAll()); //get object attribute names 
            $parameters["idSeatsByEvent"] = $purchaseLine->getSeatsByEvent()->getIdSeatsByEvent();
            $parameters["idPurchase"] = $purchaseLine->getPurchase()->getIdPurchase();

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

    public function getById($idPurchaseLine)
    {   
        $parameters = get_defined_vars();
        $purchaseLine = null;

        try {
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ."  
                    WHERE ".$purchaseLineAttributes[0]." = :".key($parameters)." 
                    AND enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) { //auto fill object with magic function __set
                    $purchaseLine->__set($value, $row[$value]);
                }

                //Get seatsByEvent, lazy

                $SeatsByEvent = $this->getSeatsByEventById($row["idSeatsByEvent"]);
                $purchaseLine->setSeatsByEvent($SeatsByEvent);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $purchaseLine;
    }

    public function getAll()//not done yet
    {
        $purchaseLineList = array();
        
        try {
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ."  
                    WHERE enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            
            foreach ($resultSet as $row)
            {
                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) { //auto fill object with magic function __set
                    $purchaseLine->__set($value, $row[$value]);
                }

                //Get seatsByEvent, lazy

                $SeatsByEvent = $this->getSeatsByEventById($row["idSeatsByEvent"]);
                $purchaseLine->setSeatsByEvent($SeatsByEvent);
            
                array_push($purchaseLineList, $purchaseLine);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $purchaseLineList;
    }

    public function getAllByPurchaseId($idPurchase)//not done yet
    {
        $parameters = get_defined_vars();
        $purchaseLineList = array();
        
        try {
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ."  
                    WHERE idPurchase = :".key($parameters)." 
                    AND enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  
            
            foreach ($resultSet as $row)
            {
                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) { //auto fill object with magic function __set
                    $purchaseLine->__set($value, $row[$value]);
                }

                //Get seatsByEvent, lazy

                $SeatsByEvent = $this->getSeatsByEventById($row["idSeatsByEvent"]);
                $purchaseLine->setSeatsByEvent($SeatsByEvent);
            
                array_push($purchaseLineList, $purchaseLine);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $purchaseLineList;
    }

    public function getAllByIdPurchase($idPurchase)
    {}

    /**
     * Updates values that are diferent from the ones recieved in the object Event
     */
    public function Update(PurchaseLine $oldPurchaseLine, PurchaseLine $newPurchaseLine){
        $valuesToModify = "";
       
        try {
            $oldPurchaseLineArray = $oldPurchaseLine->getAll(); //convert object to array of values
            $PurchaseLineArray = $newPurchaseLine->getAll();
            $parameters["idPurchaseLine"] = $oldPurchaseLine->getIdPurchaseLine();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldPurchaseLineArray as $key => $value) {
                if ($key != "idPurchaseLine") {
                    if ($oldPurchaseLineArray[$key] != $PurchaseLineArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $PurchaseLineArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idPurchaseLine = :idPurchaseLine";
            
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
    public function Delete(PurchaseLine $PurchaseLine)
    {
        try {
            $parameters["idPurchaseLine"] = $PurchaseLine->getIdPurchaseLine();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idPurchaseLine = :idPurchaseLine";

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

    public function getSeatsByEventById($idSeatsByEvent)
    {   
        $parameters = get_defined_vars();
        $seatsByEvent = null;

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameSeatsByEvent." SE 
                    INNER JOIN ".$this->tableNameSeatType." ST
                    ON SE.idSeatType = ST.idSeatType
                    WHERE SE.".$seatsByEventAttributes[0]." = :".key($parameters)." 
                    AND SE.Enabled = 1";
                    
            $resultSet = $this->connection->Execute($query,$parameters);
        
            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setSeatType($seatType);

                //Get EventByDate

                $eventByDate = $this->getEventByDateById($row["idEventByDate"]);

                $seatsByEvent->setEventByDate($eventByDate);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatsByEvent;
    }

    public function getEventByDateById($idEventByDate)
    {
        $parameters = get_defined_vars();
        $eventByDate = null;
        
        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes()); //get attribute names from object for use in __set

            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameEventByDate . " ED
                    INNER JOIN " . $this->tableNameEvent . " E
                    ON ED.idEvent = E.idEvent
                    INNER JOIN " . $this->tableNameCatergory . " C
                    ON E.idCategory = C.idCategory
                    WHERE ED.".$eventByDateAttributes[0]." = :".key($parameters)." 
                    AND ED.enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) { //auto fill object with magic function __set
                    $eventByDate->__set($value, $row[$value]);
                }

                $category = new Category();
                foreach ($categoryAttributes as $value) {
                    $category->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $event->setCategory($category);
                $eventByDate->setEvent($event);

                //---Get Theater---//

                $theater = $this->getTheaterByIdLazy($row["idTheater"]);

                $eventByDate->setTheater($theater); 
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        }

        return $eventByDate;
    }

    public function getTheaterByIdLazy($idTheater)
    {
        $parameters = get_defined_vars();

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameTheater . " T
                    WHERE T.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        }

        return $theater;
    }
}
