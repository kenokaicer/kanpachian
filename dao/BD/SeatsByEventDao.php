<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\BD\LoadType as LoadType;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ISeatsByEventDao as ISeatsByEventDao;
use Models\SeatsByEvent as SeatsByEvent;
use Models\Artist as Artist;
use Models\Category as Category;
use Models\Event as Event;
use Models\EventByDate as EventByDate;
use Models\SeatType as SeatType;
use Models\Theater as Theater;

class SeatsByEventDao implements ISeatsByEventDao
{
    private $connection;
    private $tableName = 'SeatsByEvents';
    private $tableNameEventByDate = 'EventByDates';
    private $tableNameArtist = 'Artists';
    private $tableNameTheater = 'Theaters';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameSeatTypesTheater = 'SeatTypes_x_Theater';
    private $tableNameArtistEventByDate = 'Artists_x_EventByDate';
    private $tableNameEvent = 'Events';
    private $tableNameCategory = 'Categories';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatsByEvent $seatsByEvent)
    {
        $columns = "";
        $values = "";
        
        try { 
            $parameters = array_filter($seatsByEvent->getAll()); //get object attribute names and values
            $parameters["idSeatType"] = $seatsByEvent->getSeatType()->getIdSeatType();
            $parameters["idEventByDate"] = $seatsByEvent->getEventByDate()->getIdEventByDate();

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

    /**
     * lazy1: Theater without SeatTypes
     * lazy2: omit EventByDate
     * lazy3: SeatsByEvent only
     */
    public function getById($idSeatsByEvent, $load = LoadType::All)
    {   
        $parameters = get_defined_vars();
        array_pop($parameters);
        $seatsByEvent = null;

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());

            
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            

            if($load != LoadType::Lazy3)
            {
                $query = "SELECT * FROM " . $this->tableName." SE 
                    INNER JOIN ".$this->tableNameSeatType." ST
                    ON SE.idSeatType = ST.idSeatType
                    WHERE SE.".$seatsByEventAttributes[0]." = :".key($parameters)." 
                    AND SE.Enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableName." SE 
                    WHERE SE.".$seatsByEventAttributes[0]." = :".key($parameters)." 
                    AND SE.Enabled = 1";
            }
                  
            $resultSet = $this->connection->Execute($query,$parameters);
        
            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }
            
            foreach ($resultSet as $row)
            {
                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                if($load != LoadType::Lazy3)
                {
                    $seatType = new SeatType();
                    foreach ($seatTypeAttributes as $value) {
                        $seatType->__set($value, $row[$value]);
                    }

                    $seatsByEvent->setSeatType($seatType);
                }

                //Get EventByDate

                if($load == LoadType::Lazy1)
                {
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"], LoadType::Lazy1);
                    $seatsByEvent->setEventByDate($eventByDate);
                }else if ($load == LoadType::Lazy2 || $load == LoadType::Lazy3){
                    //don't load
                }else{
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"]);
                    $seatsByEvent->setEventByDate($eventByDate);
                }
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatsByEvent;
    }

    /**
     * Lazy1: Theater without SeatTypes
     * Lazy2: omit eventByDate
     * Lazy3: single object only
     */
    public function getByEventByDateId($idEventByDate, $load = LoadType::All)
    {
        $parameters = get_defined_vars();
        array_pop($parameters);
        $seatByEventList = array();

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());

            if($load != LoadType::Lazy3){
                $seatTypeAttributes = array_keys(SeatType::getAttributes());
            
                $query = "SELECT * FROM " . $this->tableName." SE 
                        INNER JOIN ".$this->tableNameSeatType." ST
                        ON SE.idSeatType = ST.idSeatType
                        WHERE SE.idEventByDate = :".key($parameters)." 
                        AND SE.enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableName." SE 
                        WHERE SE.idEventByDate = :".key($parameters)." 
                        AND SE.enabled = 1";
            }
        
            $resultSet = $this->connection->Execute($query,$parameters);
        
            foreach ($resultSet as $row) {
                $seatsByEvent = new SeatsByEvent();
                $seatType = new SeatType();

                foreach ($seatsByEventAttributes as $value) { //auto fill object with magic function __set
                    $seatsByEvent->__set($value, $row[$value]);
                }

                if($load != LoadType::Lazy3){
                    foreach ($seatTypeAttributes as $value) {
                        $seatType->__set($value, $row[$value]);
                    }

                    $seatsByEvent->setSeatType($seatType);
                }

                //Get EventByDate

                if($load == LoadType::Lazy1)
                {
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"], LoadType::Lazy1);
                    $seatsByEvent->setEventByDate($eventByDate);
                }else if($load == LoadType::Lazy2 || $load == LoadType::Lazy3){
                    //don't load eventByDate
                }else{
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"]);
                    $seatsByEvent->setEventByDate($eventByDate);
                }

                array_push($seatByEventList, $seatsByEvent);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatByEventList;
    }

    /**
     * Lazy1: Theater without SeatTypes
     */
    public function getAll($load = LoadType::All)
    {
        $seatByEventList = array();

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes()); 

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableName." SE 
                    INNER JOIN ".$this->tableNameSeatType." ST
                    ON SE.idSeatType = ST.idSeatType
                    WHERE SE.Enabled = 1";
        
            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row) {
                $seatsByEvent = new SeatsByEvent();
                $seatType = new SeatType();

                foreach ($seatsByEventAttributes as $value) { 
                    $seatsByEvent->__set($value, $row[$value]);
                }

                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setSeatType($seatType);

                //Get EventByDate

                if($load == LoadType::Lazy1)
                {
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"], LoadType::Lazy1);
                }else{
                    $eventByDate = $this->getEventByDateById($row["idEventByDate"]);
                }

                $seatsByEvent->setEventByDate($eventByDate);

                array_push($seatByEventList, $seatsByEvent);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatByEventList;
    }

    /**
     * Used for delete check
     */
    public function getAllPastNowBySeatType($idSeatType)
    {
        $parameters = get_defined_vars();
        $seatByEventList = array();

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes()); 

            $query = "SELECT * FROM " . $this->tableName." SE 
                    INNER JOIN ".$this->tableNameEventByDate." ED
                    ON SE.idEventByDate = ED.idEventByDate
                    WHERE SE.Enabled = 1 
                    AND ED.date > now()
                    AND SE.idSeatType = :".key($parameters);
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                $seatsByEvent = new SeatsByEvent();

                foreach ($seatsByEventAttributes as $value) { 
                    $seatsByEvent->__set($value, $row[$value]);
                }

                array_push($seatByEventList, $seatsByEvent);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatByEventList;
    }

    /**
     * Used for delete check
     */
    public function getAllPastNowByTheater($idTheater)
    {
        $parameters = get_defined_vars();
        $seatByEventList = array();

        try {
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes()); 

            $query = "SELECT * FROM " . $this->tableName." SE 
                    INNER JOIN ".$this->tableNameEventByDate." ED
                    ON SE.idEventByDate = ED.idEventByDate
                    INNER JOIN ".$this->tableNameTheater." T
                    ON ED.idTheater = T.idTheater
                    WHERE SE.Enabled = 1 
                    AND ED.date > now()
                    AND T.idTheater = :".key($parameters);
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                $seatsByEvent = new SeatsByEvent();

                foreach ($seatsByEventAttributes as $value) { 
                    $seatsByEvent->__set($value, $row[$value]);
                }

                array_push($seatByEventList, $seatsByEvent);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        return $seatByEventList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatsByEvent
     */
    public function Update(SeatsByEvent $oldSeatsByEvent, SeatsByEvent $newSeatsByEvent){
        $valuesToModify = "";
       
        try {
            $oldSeatsByEventArray = $oldSeatsByEvent->getAll(); //convert object to array of values
            $seatsByEventArray = $newSeatsByEvent->getAll();

            /**
             * Check if object is complete, otherwise lazy load would mess the update
             */
            if(!is_null($oldSeatsByEvent->getSeatType()) && !is_null($newSeatsByEvent->getSeatType())){
                $oldSeatsByEventArray["idSeatType"] = $oldSeatsByEvent->getSeatType()->getIdSeatType();
                $seatsByEventArray["idSeatType"] = $oldSeatsByEvent->getSeatType()->getIdSeatType();
            }

            $parameters["idSeatsByEvent"] = $oldSeatsByEvent->getIdSeatsByEvent();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldSeatsByEventArray as $key => $value) {
                if ($key != "idSeatsByEvent") {
                    if ($oldSeatsByEventArray[$key] != $seatsByEventArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $seatsByEventArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idSeatsByEvent = :idSeatsByEvent";
            
                $modifiedRows = $this->connection->executeNonQuery($query, $parameters);
                
                if($modifiedRows!=1){
                    throw new Exception("Number of rows added ".$modifiedRows.", expected 1");
                }
            }else{
                throw new Exception("No hay datos para modificar, ningún campo nuevo ingresado");
            }
        } catch (PDOException $ex) {
            echo "update pdo";
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            echo "update ex";
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
    }

    /**
     * Logical Delete
     */
    public function Delete(SeatsByEvent $seatsByEvent)
    {
        try {
            $parameters["idSeatsByEvent"] = $seatsByEvent->getIdSeatsByEvent();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idSeatsByEvent = :idSeatsByEvent";

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

    /**
     * Lazy1: Theater without SeatTypes
     */
    private function getEventByDateById($idEventByDate, $load = LoadType::All)
    {
        $parameters = get_defined_vars();
        array_pop($parameters);
        $eventByDate = null;
        
        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes()); //get attribute names from object for use in __set

            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameEventByDate . " ED
                    INNER JOIN " . $this->tableNameEvent . " E
                    ON ED.idEvent = E.idEvent
                    INNER JOIN " . $this->tableNameCategory . " C
                    ON E.idCategory = C.idCategory
                    WHERE ED.".$eventByDateAttributes[0]." = :".key($parameters)." 
                    AND ED.enabled = 1";
        
            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)>1){
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

                if($load == LoadType::Lazy1){
                    $theater = $this->getTheaterById($row["idTheater"], LoadType::Lazy1);
                }else{
                    $theater = $this->getTheaterById($row["idTheater"]);
                }

                $eventByDate->setTheater($theater);

                //---Get Artists---//

                
                $artistsList = $this->getArtistsByEventByDateId($eventByDate->getIdEventByDate());

                $eventByDate->setArtists($artistsList);$theater = $this->getTheaterById($row["idTheater"]);
                 
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        }

        return $eventByDate;
    }

     /**
     * Lazy1: omit SeatTypes
     */
    private function getTheaterById($idTheater, $load = LoadType::All)
    {
        $parameters = get_defined_vars();
        array_pop($parameters);

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            if($load == LoadType::All){
                $seatTypeAttributes = array_keys(SeatType::getAttributes());
            }

            if($load == LoadType::All){
                $query = "SELECT * FROM " . $this->tableNameTheater . " T
                    INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
                    ON T.idTheater = STT.idTheater
                    INNER JOIN " . $this->tableNameSeatType . " ST
                    ON STT.idSeatType = ST.idSeatType
                    WHERE STT.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableNameTheater . " T
                    WHERE T.".$theaterAttributes[0]." = :".key($parameters)." 
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

                if($load == LoadType::All){
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

    private function getArtistsByEventByDateId($idEventByDate)
    {
        $parameters = get_defined_vars();
        $artistsList = array();

        try {
            $artistAttributes = array_keys(Artist::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameArtist . " A
                    INNER JOIN " . $this->tableNameArtistEventByDate . " AED
                    ON A.idArtist = AED.idArtist
                    WHERE AED.idEventByDate = :".key($parameters)." 
                    AND A.enabled = 1";
        
            $resultSet = $this->connection->Execute($query, $parameters);
        
            foreach ($resultSet as $row) {
                $artist = new Artist();

                foreach ($artistAttributes as $value) {
                    $artist->__set($value, $row[$value]);
                }

                array_push($artistsList, $artist);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate list query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate list query error: " . $ex->getMessage());
        }

        return $artistsList;
    }

    public function getIdSeatTypesByEventByDate($idEventByDate)
    {
        $parameters = get_defined_vars();
        $seatTypes = array();

        try {
            $query = "SELECT idSeatType 
                    FROM " . $this->tableName." 
                    WHERE idEventByDate = :".key($parameters)." 
                    AND enabled = 1";

                    $resultSet = $this->connection->Execute($query, $parameters);
        
            foreach ($resultSet as $row) {
                array_push($seatTypes, $row["idSeatType"]);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatTypes list error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatTypes list error: " . $ex->getMessage());
        }

        return $seatTypes;
    }
}
