<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\Interfaces\IEventByDateDao as IEventByDateDao;
use Dao\SingletonDao as SingletonDao;
use Exception as Exception;
use Models\Artist as Artist;
use Models\Category as Category;
use Models\Event as Event;
use Models\EventByDate as EventByDate;
use Models\SeatType as SeatType;
use Models\Theater as Theater;
use PDOException as PDOException;

class EventByDateDao extends SingletonDao implements IEventByDateDao
{
    private $connection;
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

        try {
            $addedRows = $this->connection->executeNonQuery($query, $parameters);
            if ($addedRows != 1) {
                throw new Exception("Number of rows added " . $addedRows . ", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        }

        //---Get ID of the EventByDate inserted---//

        $idEventByDate = $this->lastInsertId();

        //---Insert each Artist in a separate querry, N:N Table ---//

        foreach ($eventByDate->getArtists() as $artistItem) {
            $query = "INSERT INTO " . $this->tableNameArtistEventByDate . " (idArtist, idEventByDate) VALUES (:idArtist,:idEventByDate);";

            $parameters = array();
            $parameters["idArtist"] = $artistItem->getIdArtist();
            $parameters["idEventByDate"] = $idEventByDate;

            try {
                $addedRows = $this->connection->executeNonQuery($query, $parameters);
                if ($addedRows != 1) {
                    throw new Exception("Number of rows added " . $addedRows . ", expected 1, in SeatType");
                }
            } catch (PDOException $ex) {
                throw new Exception(__METHOD__ . ", Error inserting Artist. " . $ex->getMessage());
                return;
            } catch (Exception $ex) {
                throw new Exception(__METHOD__ . ", Error inserting Artist. " . $ex->getMessage());
                return;
            }
        }
    }

    public function getByID($id)
    {
        $eventByDate = new EventByDate();
        $category = new Category();
        $event = new Event();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes); //delete object variables

        $eventAttributes = array_keys($event->getAll());
        array_pop($eventAttributes);

        $categoryAttributes = array_keys($category->getAll());

        $query = "SELECT * FROM " . $this->tableName . " ED
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

    public function getByEventID($idEvent)
    {
        $eventByDateList = array();
        $eventByDate = new EventByDate();
        $category = new Category();
        $event = new Event();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes); //delete object variables

        $eventAttributes = array_keys($event->getAll());
        array_pop($eventAttributes);

        $categoryAttributes = array_keys($category->getAll());

        $query = "SELECT * FROM " . $this->tableName . " ED
         INNER JOIN " . $this->tableNameEvent . " E
         ON ED.idEvent = E.idEvent
         INNER JOIN " . $this->tableNameCatergory . " C
         ON E.idCategory = C.idCategory
         WHERE ED.enabled = 1
         AND ED.idEvent = " . $idEvent;

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $eventByDate = new EventByDate();
            $event = new Event();
            $category = new Category();

            foreach ($eventByDateAttributes as $value) {
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

            //---Get theater and its SeatTypes---//

            $theater = $this->getTheaterByID($row["idTheater"]);
            
            $eventByDate->setTheater($theater);

            //---Get Artists---//

            $artistsList = $this->getArtistsByEventByDateID($row["idEventByDate"]);

            $eventByDate->setArtists($artistsList);

            array_push($eventByDateList, $eventByDate);
        }

        return $eventByDateList;
    }

    public function getAll()
    {
        $eventByDateList = array();
        $eventByDate = new EventByDate();
        $category = new Category();
        $event = new Event();

        $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes);
        array_pop($eventByDateAttributes); //delete object variables

        $eventAttributes = array_keys($event->getAll());
        array_pop($eventAttributes);

        $categoryAttributes = array_keys($category->getAll());

        $query = "SELECT * FROM " . $this->tableName . " ED
         INNER JOIN " . $this->tableNameEvent . " E
         ON ED.idEvent = E.idEvent
         INNER JOIN " . $this->tableNameCatergory . " C
         ON E.idCategory = C.idCategory
         WHERE ED.enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $eventByDate = new EventByDate();
            $event = new Event();
            $category = new Category();

            foreach ($eventByDateAttributes as $value) {
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

            //---Get theater and its SeatTypes---//

            $theater = $this->getTheaterByID($row["idTheater"]);
            
            $eventByDate->setTheater($theater);

            //---Get Artists---//

            $artistsList = $this->getArtistsByEventByDateID($row["idEventByDate"]);

            $eventByDate->setArtists($artistsList);

            array_push($eventByDateList, $eventByDate);
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
        $query = "UPDATE " . $this->tableName . " SET enabled = 0 WHERE idEventByDate = " . $eventByDate->getIdEventByDate();

        try {
            $modifiedRows = $this->connection->executeNonQuery($query, array());
            if ($modifiedRows != 1) {
                throw new Exception("Number of rows added " . $modifiedRows . ", expected 1");
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . " error: " . $ex->getMessage());
        }
    }

    public function lastInsertId()
    {
        $query = "SELECT LAST_INSERT_ID()";

        try {
            $resultSet = $this->connection->Execute($query);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ", Error getting last insert id. " . $ex->getMessage());
            return;
        }
        $row = reset($resultSet); //gives first object of array
        $id = reset($row); //get value of previous first object

        return $id;
    }

    public function getTheaterByID($idTheater)
    {
        $theater = new Theater();
        $seatType = new SeatType();

        $theaterAttributes = array_keys($theater->getAll()); //get attribute names from object for use in __set
        array_pop($theaterAttributes); //delete object variables

        $seatTypeAttributes = array_keys($seatType->getAll());

        $parameters["idTheater"] = $idTheater;

        $query = "SELECT * FROM " . $this->tableNameTheater . " T
            INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
            ON T.idTheater = STT.idTheater
            INNER JOIN " . $this->tableNameSeatType . " ST
            ON STT.idSeatType = ST.idSeatType
            WHERE T.enabled = 1 AND T.idTheater = :idTheater";

        try {
            $resultSet = $this->connection->Execute($query, $parameters);
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

        $parameters["idEventByDate"] = $idEventByDate;

        $query = "SELECT * FROM " . $this->tableNameArtist . " A
        INNER JOIN " . $this->tableNameArtistEventByDate . " AED
        ON A.idArtist = AED.idArtist
        WHERE A.enabled = 1 AND AED.idEventByDate = :idEventByDate";

        try {
            $resultSet = $this->connection->Execute($query, $parameters);
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
