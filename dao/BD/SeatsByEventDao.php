<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
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

class SeatsByEventDao extends SingletonDao implements ISeatsByEventDao
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
    private $tableNameCatergory = 'Categories';

    public function __construct(){
        $this->connection = Connection::getInstance();
    }

    public function Add(SeatsByEvent $seatsByEvent)
    {
        $columns = "";
        $values = "";
        
        $parameters = array_filter($seatsByEvent->getAll()); //get object attribute names
        $parameters["idSeatType"] = $seatsByEvent->getSeatType()->getIdSeatType();
        $parameters["idEventByDate"] = $seatsByEvent->getEventByDate()->getIdEventByDate();

        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO " . $this->tableName . " (".$columns.",idSeatType,idEventByDate) 
        VALUES (".$values.",:idSeatType,:idEventByDate);";

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
        $seatsByEvent = new SeatsByEvent();
        $seatType = new SeatType();

        $seatsByEventAttributes = array_keys($seatsByEvent->getAll()); //get attribute names from object for use in __set

        $seatTypeAttributes = array_keys($seatType->getAll());

        $query = "SELECT * FROM " . $this->tableName." SE 
                INNER JOIN ".$this->tableNameSeatType." ST
                ON SE.idSeatType = ST.idSeatType
                WHERE SE.Enabled = 1
                AND ".$seatsByEventAttributes[0]." = ".$id;
        
        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        $row = reset($resultSet);

        foreach ($seatsByEventAttributes as $value) { //auto fill object with magic function __set
            $seatsByEvent->__set($value, $row[$value]);
        }

        foreach ($seatTypeAttributes as $value) {
            $seatType->__set($value, $row[$value]);
        }

        $seatsByEvent->setSeatType($seatType);

        //Get EventByDate

        $eventByDate = $this->getEventByDateByID($row["idEventByDate"]);

        $seatsByEvent->setEventByDate($eventByDate);

        return $seatsByEvent;
    }

    public function getByEventByDateID($idEventByDate)
    {
        $seatByEventList = array();
        $seatsByEvent = new SeatsByEvent();
        $seatType = new SeatType();

        $seatsByEventAttributes = array_keys($seatsByEvent->getAll()); //get attribute names from object for use in __set

        $seatTypeAttributes = array_keys($seatType->getAll());

        $query = "SELECT * FROM " . $this->tableName." SE 
                INNER JOIN ".$this->tableNameSeatType." ST
                ON SE.idSeatType = ST.idSeatType
                WHERE SE.Enabled = 1
                AND SE.idEventByDate = " . $idEventByDate;
        
        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $seatsByEvent = new SeatsByEvent();
            $seatType = new SeatType();

            foreach ($seatsByEventAttributes as $value) { //auto fill object with magic function __set
                $seatsByEvent->__set($value, $row[$value]);
            }

            foreach ($seatTypeAttributes as $value) {
                $seatType->__set($value, $row[$value]);
            }

            $seatsByEvent->setSeatType($seatType);

            //Get EventByDate

            $eventByDate = $this->getEventByDateByID($row["idEventByDate"]);

            $seatsByEvent->setEventByDate($eventByDate);

            array_push($seatByEventList, $seatsByEvent);
        }

        return $seatByEventList;
    }

    public function getAll()
    {
        $seatByEventList = array();
        $seatsByEvent = new SeatsByEvent();
        $seatType = new SeatType();

        $seatsByEventAttributes = array_keys($seatsByEvent->getAll()); //get attribute names from object for use in __set

        $seatTypeAttributes = array_keys($seatType->getAll());

        $query = "SELECT * FROM " . $this->tableName." SE 
                INNER JOIN ".$this->tableNameSeatType." ST
                ON SE.idSeatType = ST.idSeatType
                WHERE SE.Enabled = 1";
        
        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",seatsByEvent, seatType query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $seatsByEvent = new SeatsByEvent();
            $seatType = new SeatType();

            foreach ($seatsByEventAttributes as $value) { //auto fill object with magic function __set
                $seatsByEvent->__set($value, $row[$value]);
            }

            foreach ($seatTypeAttributes as $value) {
                $seatType->__set($value, $row[$value]);
            }

            $seatsByEvent->setSeatType($seatType);

            //Get EventByDate

            $eventByDate = $this->getEventByDateByID($row["idEventByDate"]);

            $seatsByEvent->setEventByDate($eventByDate);

            array_push($seatByEventList, $seatsByEvent);
        }

        return $seatByEventList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object SeatsByEvent
     */
    public function Update(SeatsByEvent $oldSeatsByEvent, SeatsByEvent $newSeatsByEvent)
    {
        $valuesToModify = "";
        $oldSeatsByEventArray = $oldSeatsByEvent->getAll(); //convert object to array of values
        $seatsByEventArray = $newSeatsByEvent->getAll();

        /**
         * Check if a value is different from the one on the database, if different, sets the column and
         * value for the SET query
         */
        foreach ($oldSeatsByEventArray as $key => $value) {
            if ($key != "idSeatsByEvent") {
                if ($oldSeatsByEventArray[$key] != $seatsByEventArray[$key]) {
                    $valuesToModify .= $key . " = " . "'" . $seatsByEventArray[$key] . "', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

        $query = "UPDATE " . $this->tableName . " SET " . $valuesToModify . " WHERE idSeatsByEvent = " . $oldSeatsByEvent->getIdSeatsByEvent();
        
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
    public function Delete(SeatsByEvent $seatsByEvent)
    {
        $query = "UPDATE ".$this->tableName." SET enabled = 0 WHERE idSeatsByEvent = ".$seatsByEvent->getIdSeatsByEvent();

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

    public function getEventByDateByID($id)
    {
        $eventByDate = new EventByDate();
        $category = new Category();
        $event = new Event();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set

        $eventAttributes = array_keys($event->getAll());

        $categoryAttributes = array_keys($category->getAll());

        $query = "SELECT * FROM " . $this->tableNameEventByDate . " ED
                INNER JOIN " . $this->tableNameEvent . " E
                ON ED.idEvent = E.idEvent
                INNER JOIN " . $this->tableNameCatergory . " C
                ON E.idCategory = C.idCategory
                WHERE ED." . $eventByDateAttributes[0] . " = " . $id . "
                AND ED.enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        }

        $row = reset($resultSet);

        foreach ($eventByDateAttributes as $value) { //auto fill object with magic function __set
            $eventByDate->__set($value, $row[$value]);
        }

        foreach ($categoryAttributes as $value) {
            $category->__set($value, $row[$value]);
        }

        foreach ($eventAttributes as $value) {
            $event->__set($value, $row[$value]);
        }

        $event->setCategory($category);
        $eventByDate->setEvent($event);

        //---Get Theater---//

        $theater = $this->getTheaterByID($row["idTheater"]);

        $eventByDate->setTheater($theater);

        //---Get Artists---//

        $artistsList = $this->getArtistsByEventByDateID($eventByDate->getIdEventByDate());

        $eventByDate->setArtists($artistsList);

        return $eventByDate;
    }

    public function getTheaterByID($idTheater)
    {
        $theater = new Theater();
        $seatType = new SeatType();

        $theaterAttributes = array_keys($theater->getAll()); //get attribute names from object for use in __set

        $seatTypeAttributes = array_keys($seatType->getAll());

        $query = "SELECT * FROM " . $this->tableNameTheater . " T
            INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
            ON T.idTheater = STT.idTheater
            INNER JOIN " . $this->tableNameSeatType . " ST
            ON STT.idSeatType = ST.idSeatType
            WHERE T.enabled = 1 AND T.idTheater = " . $idTheater;

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",theater query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            if ($theater->getIdTheater() != $idTheater) { //load theater only on first loop
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

        return $theater;
    }

    public function getArtistsByEventByDateID($idEventByDate)
    {
        $artist = new Artist();
        $artistsList = array();

        $artistAttributes = array_keys($artist->getAll());

        $query = "SELECT * FROM " . $this->tableNameArtist . " A
        INNER JOIN " . $this->tableNameArtistEventByDate . " AED
        ON A.idArtist = AED.idArtist
        WHERE A.enabled = 1 AND AED.idEventByDate = " . $idEventByDate;

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",artist list query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",artist list query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $artist = new Artist();

            foreach ($artistAttributes as $value) {
                $artist->__set($value, $row[$value]);
            }

            array_push($artistsList, $artist);
        }

        return $artistsList;
    }
}
