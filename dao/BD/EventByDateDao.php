<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\Interfaces\IEventByDateDao as IEventByDateDao;
use Dao\SingletonDao as SingletonDao;
use Exception as Exception;
use Models\EventByDate as EventByDate;
use Models\Category as Category;
use Models\Event as Event;
use Models\Artist as Artist;
use Models\SeatType as SeatType;
use Models\Theater as Theater;
use PDOException as PDOException;

class EventByDateDao extends SingletonDao implements IEventByDateDao
{
    private $connection;
    private $tableName = 'EventByDates';
    private $tableNameEventByDate = 'EventByDates';
    private $tableNameTheater = 'Theaters';
    private $tableNameSeatType = 'SeatTypes';
    private $tableNameSeatTypesTheater = 'SeatTypes_x_Theater';
    private $tableNameEventByDateEventByDate = 'EventByDates_x_EventByDate';
    private $tableNameEvent = 'Events';
    private $tableNameCatergory = 'Categories';

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Add to tables EventByDates and EventByDates_x_EventByDate
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

        //---Get ID of the EventByDate inserted---//

        $idEventByDate = $this->lastInsertId();

        //---Insert each EventByDate in a separate querry, N:N Table ---//

        try {
            foreach ($eventByDate->getEventByDates() as $eventByDateItem) {
                $query = "INSERT INTO " . $this->tableNameEventByDateEventByDate . " (idEventByDate, idEventByDate) 
                        VALUES (:idEventByDate,:idEventByDate);";

                $parameters = array();
                $parameters["idEventByDate"] = $eventByDateItem->getIdEventByDate();
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

    public function getByID($idEventByDate)
    {
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
                    WHERE ED." . $eventByDateAttributes[0] . " = " . $id . "
                    AND ED.enabled = 1";

            $resultSet = $this->connection->Execute($query);
    
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

        $theater = $this->getTheaterByID($row["idTheater"]);

        $eventByDate->setTheater($theater);

        //---Get EventByDates---//

        $eventByDatesList = $this->getEventByDatesByID($eventByDate->getIdEventByDate());

        $eventByDate->setEventByDates($eventByDatesList);

        return $eventByDate;
    }

    public function getByEventID($idEvent)
    {
        $parameters = get_defined_vars();
        $eventByDateList = array();

        try {
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());

            $eventAttributes = array_keys(Event::getAttributes());

            $categoryAttributes = array_keys(Category::getAttributes());

            $query = "SELECT * FROM " . $this->tableName . " ED
                    INNER JOIN " . $this->tableNameEvent . " E
                    ON ED.idEvent = E.idEvent
                    INNER JOIN " . $this->tableNameCatergory . " C
                    ON E.idCategory = C.idCategory
                    WHERE ED.".$eventByDateAttributes[0]." = :".key($parameters)." 
                    AND ED.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);      

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

                $theater = $this->getTheaterByID($row["idTheater"]);
                
                $eventByDate->setTheater($theater);

                //---Get EventByDates---//

                $eventByDatesList = $this->getEventByDatesByID($row["idEventByDate"]);

                $eventByDate->setEventByDates($eventByDatesList);

                array_push($eventByDateList, $eventByDate);
            }
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate query error: " . $ex->getMessage());
        }

        return $eventByDateList;
    }

    public function getAll()
    {
        $eventByDateList = array();

        try {
            $eventByDateAttributes = array_keys($eventByDate->getAll()); //get attribute names from object for use in __set

            $eventAttributes = array_keys($event->getAll());

            $categoryAttributes = array_keys($category->getAll());

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

                $theater = $this->getTheaterByID($row["idTheater"]);
                
                $eventByDate->setTheater($theater);

                //---Get EventByDates---//

                $eventByDatesList = $this->getEventByDatesByID($row["idEventByDate"]);

                $eventByDate->setEventByDates($eventByDatesList);

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

    public function lastInsertId()
    {
        try {
            $query = "SELECT LAST_INSERT_ID()";

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

    public function getTheaterByID($idTheater)
    {
        $parameters = get_defined_vars();
        $theater = null;

        try {
            $theaterAttributes = array_keys(Theater::getAttributes()); //get attribute names from object for use in __set

            $seatTypeAttributes = array_keys(SeatType::getAttributes());

            $query = "SELECT * FROM " . $this->tableNameTheater . " T
                    INNER JOIN " . $this->tableNameSeatTypesTheater . " STT
                    ON T.idTheater = STT.idTheater
                    INNER JOIN " . $this->tableNameSeatType . " ST
                    ON STT.idSeatType = ST.idSeatType
                    WHERE STT.".$theaterAttributes[0]." = :".key($parameters)." 
                    AND T.enabled = 1";
        
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $row) {
                if ($theater->getIdTheater() != $idTheater) { //load theater only on first loop
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

    public function getEventByDatesByID($idEventByDate)
    {
        $parameters = get_defined_vars();
        $eventByDatesList = array();

        $eventByDateAttributes = array_keys(EventByDate::getAttributes());

        $parameters["idEventByDate"] = $idEventByDate;

        $query = "SELECT * FROM " . $this->tableNameEventByDate . " A
        INNER JOIN " . $this->tableNameEventByDateEventByDate . " AED
        ON A.idEventByDate = AED.idEventByDate
        WHERE AED".$eventByDateAttributes[0]." = :".key($parameters)." 
        AND A.enabled = 1";

        try {
            $resultSet = $this->connection->Execute($query, $parameters);
        } catch (PDOException $ex) {
            throw new Exception(__METHOD__ . ",eventByDate list query error: " . $ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception(__METHOD__ . ",eventByDate list query error: " . $ex->getMessage());
        }

        foreach ($resultSet as $row) {
            $eventByDate = new EventByDate();

            foreach ($eventByDateAttributes as $value) {
                $eventByDate->__set($value, $row[$value]);
            }

            array_push($eventByDatesList, $eventByDate);
        }

        return $eventByDatesList;
    }
}
