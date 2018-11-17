<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\BD\DaoBD as DaoBD;
use Dao\BD\LoadType as LoadType;
use Dao\Interfaces\IEventByDateDao as IEventByDateDao;
use Exception as Exception;
use Models\EventByDate as EventByDate;
use Models\Category as Category;
use Models\Event as Event;
use Models\Artist as Artist;
use Models\SeatType as SeatType;
use Models\Theater as Theater;
use PDOException as PDOException;


class EventByDateDao extends DaoBD implements IEventByDateDao
{
    protected $connection;
    private $tableName = 'EventByDates';
    private $tableNameArtist = 'Artists';
    private $tableNameTheater = 'Theaters';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameSeatTypesTheater = 'SeatTypes_x_Theater';
    private $tableNameArtistEventByDate = 'Artists_x_EventByDate';
    private $tableNameEvent = 'Events';
    private $tableNameCatergory = 'Categories';

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Add to tables EventByDates and Artists_x_EventByDate
     */
    public function Add(EventByDate $eventByDate)
    {
        $columns = "";
        $values = "";

        try {
            $parameters["date"] = $eventByDate->getDate();
            $parameters["idTheater"] = $eventByDate->getTheater()->getIdTheater();
            $parameters["idEvent"] = $eventByDate->getEvent()->getIdEvent();

            foreach ($parameters as $key => $value) {
                $columns .= $key . ",";
                $values .= ":" . $key . ",";
            }
            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");

            $query = "INSERT INTO " . $this->tableName . " (" . $columns . ") VALUES (" . $values . ");";
        
            $addedRows = $this->connection->executeNonQuery($query, $parameters);

            if ($addedRows != 1) {
                throw new Exception("Number of rows added " . $addedRows . ", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        }

        //---Get Id of the EventByDate inserted---//

        $idEventByDate = $this->lastInsertId();

        //---Insert each Artist in a separate querry, N:N Table ---//

        try {
            foreach ($eventByDate->getArtists() as $artistItem) {
                $query = "INSERT INTO " . $this->tableNameArtistEventByDate . " (idArtist, idEventByDate) 
                        VALUES (:idArtist,:idEventByDate);";

                $parameters = array();
                $parameters["idArtist"] = $artistItem->getIdArtist();
                $parameters["idEventByDate"] = $idEventByDate;

                $addedRows = $this->connection->executeNonQuery($query, $parameters);

                if ($addedRows != 1) {
                    throw new Exception("Number of rows added " . $addedRows . ", expected 1, in SeatType");
                } 
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error inserting EventByDate. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error inserting EventByDate. " . $ex->getMessage());
            return;
        }
    }

    public function getById($idEventByDate)
    {
        $parameters = get_defined_vars();
        $eventByDate = null;

        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes()); //get attribute names from object for use in __set

            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " ED
                    INNER JOIN " . $this->tableNameEvent . " E
                    ON ED.idEvent = E.idEvent
                    INNER JOIN " . $this->tableNameCatergory . " C
                    ON E.idCategory = C.idCategory
                    WHERE ED." . $eventByDateAttributes[0] . " = :".key($parameters)." 
                    AND ED.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);
    
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
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate, event, category query error: " . $ex->getMessage());
        }

        //---Get Theater---//

        $theater = $this->getTheaterById($row["idTheater"]);

        $eventByDate->setTheater($theater);

        //---Get Artists---//

        $artistsList = $this->getArtistsByEventByDateId($eventByDate->getIdEventByDate());

        $eventByDate->setArtists($artistsList);

        return $eventByDate;
    }

    public function getAll()
    {
        $eventByDateList = array();

        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes()); //get attribute names from object for use in __set

            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " ED
                    INNER JOIN " . $this->tableNameEvent . " E
                    ON ED.idEvent = E.idEvent
                    INNER JOIN " . $this->tableNameCatergory . " C
                    ON E.idCategory = C.idCategory
                    WHERE ED.enabled = 1";

            $resultSet = $this->connection->Execute($query);
        
            foreach ($resultSet as $row) {
                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
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

                //---Get theater and its SeatTypes---//

                $theater = $this->getTheaterById($row["idTheater"]);
                
                $eventByDate->setTheater($theater);

                //---Get Artists---//

                $artistsList = $this->getArtistsByEventByDateId($row["idEventByDate"]);

                $eventByDate->setArtists($artistsList);

                array_push($eventByDateList, $eventByDate);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        return $eventByDateList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object EventByDate
     */
    public function Update(EventByDate $oldEventByDate, EventByDate $newEventByDate)
    {}

    /**
     * Logical Delete
     */
    public function Delete(EventByDate $eventByDate)
    {
        //$query = "DELETE FROM " . $this->tableName . " WHERE ".$eventByDateAttributes[0]." = " . $eventByDate->getIdEventByDate();
        try {
            $parameters["idEventByDate"] = $eventByDate->getIdEventByDate();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idEventByDate = :idEventByDate";

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
     * lazy1: Omit Event and Category, and SeatTypes for Theater
     */
    public function getByEventId($idEvent, $load = LoadType::All)
    {
        $parameters = get_defined_vars();
        array_pop($parameters);
        $eventByDateList = array();

        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());

            if($load == LoadType::All){
                $eventAttributes = array_keys(Event::getAttributes());

                $categoryAttributes = array_keys(Category::getAttributes());
            }
            
            if($load == LoadType::All){
                $query = "SELECT * FROM " . $this->tableName . " ED
                        INNER JOIN " . $this->tableNameEvent . " E
                        ON ED.idEvent = E.idEvent
                        INNER JOIN " . $this->tableNameCatergory . " C
                        ON E.idCategory = C.idCategory
                        WHERE ED.".$eventAttributes[0]." = :".key($parameters)." 
                        AND ED.enabled = 1";
            }else{
                $query = "SELECT * FROM " . $this->tableName . " ED
                    WHERE ED.idEvent = :".key($parameters)." 
                    AND ED.enabled = 1";
            }

            $resultSet = $this->connection->Execute($query,$parameters);      

            foreach ($resultSet as $row) {
                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                if($load == LoadType::All){
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
                }
       
                //---Get theater and its SeatTypes---//

                if($load == LoadType::All){
                    $theater = $this->getTheaterById($row["idTheater"]);
                }else{
                    $theater = $this->getTheaterById($row["idTheater"], LoadType::Lazy1);
                }
                $eventByDate->setTheater($theater);

                //---Get Artists---//

                $artistsList = $this->getArtistsByEventByDateId($row["idEventByDate"]);

                $eventByDate->setArtists($artistsList);

                array_push($eventByDateList, $eventByDate);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        return $eventByDateList;
    }

    /**
     * Omit Event and Category, and Theater
     * no complete method need, so only this one was written
     */
    public function getByEventIdAndTheaterIdLazy($idEvent, $idTheater)
    {
        $parameters = get_defined_vars();
        $eventByDateList = array();

        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " ED
                    WHERE ED.idEvent = :idEvent
                    AND ED.idTheater = :idTheater 
                    AND ED.enabled = 1
                    ORDER BY ED.date";

            $resultSet = $this->connection->Execute($query,$parameters);      

            foreach ($resultSet as $row) {
                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                //---Get Artists---//

                $artistsList = $this->getArtistsByEventByDateId($row["idEventByDate"]);

                $eventByDate->setArtists($artistsList);

                array_push($eventByDateList, $eventByDate);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        return $eventByDateList;
    }

    /**
     * Lazy1: omit SeatTypes
     */
    public function getTheaterById($idTheater, $load = LoadType::All)
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

    public function getArtistsByEventByDateId($idEventByDate)
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

    public function getTeatherByEventByDateId($idEventByDate){
        $parameters = get_defined_vars();
        $theater = null;

        try{
            $query = "SELECT idTheater
                    FROM EventByDates
                    WHERE idEventByDate = :".key($parameters)." 
                    AND enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);

            $row = reset($resultSet); //gives first object of array
            $idTheater = reset($row); //get value of previous first object
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error getting Theater id. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error getting Theater id. " . $ex->getMessage());
            return;
        }

        try{
            $theater = $this->getTheaterById($idTheater);
        }catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error getting Theater. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error getting Theater. " . $ex->getMessage());
            return;
        }
        
        return $theater;
    }
    
}
