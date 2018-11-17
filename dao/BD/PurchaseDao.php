<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\IPurchaseDao as IPurchaseDao;
use Models\Purchase as Purchase;
use Models\Client as Client;

class PurchaseDao implements IPurchaseDao
{
    private $connection;
    private $tableName = 'Purchases';
    private $tableNameClients = 'Clients';
    private $tableNamePurchaseLines = 'PurchaseLines';
    private $tableNameSeatsByEvent = 'SeatsByEvents';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameEventByDate = 'EventByDates';
    private $tableNameEvent = 'Events';
    private $tableNameCatergory = 'Categories';
    private $tableNameTheater = 'Theaters';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(Purchase $purchase)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters["date"] = $purchase->getDate();
            $parameters["idCategory"] = $purchase->getClient()->getIdClient();

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

    public function getById($idPurchase)
    {   
        $parameters = get_defined_vars();
        $purchase = null;

        try {
            $purchaseAttributes = array_keys(Purchase::getAttributes());

            $clientAttributes = array_keys(Client::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." P
                    INNER JOIN ".$this->tableNameClients." C
                    ON P.idClient = C.idClient  
                    WHERE ".$purchaseAttributes[0]." = :".key($parameters)." 
                    AND P.enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $purchase = new Purchase();
                foreach ($purchaseAttributes as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }

                $client = new Client();
                foreach ($clientAttributes as $value) {
                    $client->__set($value, $row[$value]);
                }

                $purchaseLines = $this->getPurchaseLinesByPurchaseId($row["idPurchase"]);

                $purchase->setClient($client);
                $purchase->setPurchaseLines($purchaseLines);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $purchase;
    }

    public function getAll()
    {
        $purchaseList = array();

        try {
            $purchaseAttributes = array_keys(Purchase::getAttributes());

            $clientAttributes = array_keys(Client::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." P
                    INNER JOIN ".$this->tableNameClients." C
                    ON P.idClient = C.idClient  
                    WHERE P.enabled = 1";
            
            $resultSet = $this->connection->Execute($query,$parameters);  

            if(sizeof($resultSet)!=1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $purchase = new Purchase();
                foreach ($purchaseAttributes as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }

                $client = new Client();
                foreach ($clientAttributes as $value) {
                    $client->__set($value, $row[$value]);
                }

                $purchaseLines = $this->getPurchaseLinesByPurchaseId($row["idPurchase"]);

                $purchase->setClient($client);
                $purchase->setPurchaseLines($purchaseLines);

                array_push($purchaseList, $purchase);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $purchaseList;
    }

    /**
     * no client returned, maybe this will be deprecated
     */
    function getAllNew()
    {
        $purchaseList = array();

        try{
            $query = "SELECT * FROM ".$this->tableName." 
                    WHERE enabled = 1";

            $resultSet = $this->connection->Execute($query);
       
            $artistAttributes = array_keys(Purchase::getAttributes()); //get attributes names from object for use in __set

            foreach ($resultSet as $row) //loops returned rows
            {                
                $purchase = new Purchase();
                
                foreach ($artistAttributes as $value) { //auto fill object with magic function __set
                    $purchase->__set($value, $row[$value]);
                }

                array_push($purchaseList, $purchase);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $purchaseList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Purchase
     */
    public function Update(Purchase $oldPurchase, Purchase $newPurchase){
        $valuesToModify = "";
       
        try {
            $oldPurchaseArray = $oldPurchase->getAll(); //convert object to array of values
            $purchaseArray = $newPurchase->getAll();
            $parameters["idPurchase"] = $oldPurchase->getIdPurchase();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldPurchaseArray as $key => $value) {
                if ($key != "idPurchase") {
                    if ($oldPurchaseArray[$key] != $purchaseArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $purchaseArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idPurchase = :idPurchase";
            
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
    public function Delete(Purchase $purchase)
    {
        try {
            $parameters["idPurchase"] = $purchase->getIdPurchase();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idPurchase = :idPurchase";

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

    public function getPurchaseLinesByPurchaseId($idPurchase)
    {
        $parameters = get_defined_vars();
        $purchaseLineList = array();
        
        try {
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableNamePurchaseLines ."  
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
