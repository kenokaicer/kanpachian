<?php namespace Dao\BD;

use Dao\BD\Connection as Connection;
use Dao\BD\DaoBD as DaoBD;
use Dao\BD\LoadType as LoadType;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITicketDao as ITicketDao;
use Models\Ticket as Ticket;
use Models\PurchaseLine as PurchaseLine;
use Models\SeatType as SeatType;
use Models\SeatsByEvent as SeatsByEvent;
use Models\EventByDate as EventByDate;
use Models\Event as Event;
use Models\Theater as Theater;

class TicketDao extends DaoBD implements ITicketDao
{
    protected $connection;
    private $tableName = 'Tickets';
    private $tableNamePurchases = 'Purchases';
    private $tableNameClients = 'Clients';
    private $tableNamePurchaseLines = 'PurchaseLines';
    private $tableNameSeatsByEvents = 'SeatsByEvents';
    private $tableNameEventByDates = 'EventByDates';
    private $tableNameEvents = 'Events';
    private $tableNameTheaters = 'Theaters';
    private $tableNameSeatsTypes = 'SeatTypes';

    public function __construct(){
        try{
            $this->connection = Connection::getInstance();
        }catch(Exception $ex){
            throw $ex;
        }    
    }
    
    public function Add(Ticket $ticket)
    {
        $columns = "";
        $values = "";
        
        try {
            $parameters = array_filter($ticket->getAll()); //get object attribute names 
            $parameters["idPurchaseLine"] = $ticket->getPurchaseLine()->getIdPurchaseLine();

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

        $idTicket = $this->lastInsertId();

        return $idTicket;
    }

    /**
     * used in old version of qrCode
     */
    public function getById($idTicket)
    {
        $parameters = get_defined_vars();
        $ticket = null;  
        
        try {
            $ticketAttributes = array_keys(Ticket::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." ti 
                    INNER JOIN ".$this->tableNamePurchaseLines." pl 
                    ON ti.idPurchaseLine = pl.idPurchaseLine 
                    INNER JOIN ".$this->tableNameSeatsByEvents." se 
                    ON pl.idSeatsByEvent = se.idSeatsByEvent 
                    INNER JOIN ".$this->tableNameSeatsTypes." st 
                    ON se.idSeatType = st.idSeatType 
                    INNER JOIN ".$this->tableNameEventByDates." ed 
                    ON se.idEventByDate = ed.idEventByDate 
                    INNER JOIN ".$this->tableNameEvents." e 
                    ON ed.idEvent = e.idEvent 
                    INNER JOIN ".$this->tableNameTheaters." t 
                    ON ed.idTheater = t.idTheater 
                    WHERE ".$ticketAttributes[0]." = :".key($parameters)." 
                    AND ti.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }

            $theaterAttributes = array_keys(Theater::getAttributes());
            $eventAttributes = array_keys(Event::getAttributes());
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());
            
            foreach ($resultSet as $row)
            {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                $eventByDate->setEvent($event);
                $eventByDate->setTheater($theater);

                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);

                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) {
                    $purchaseLine->__set($value, $row[$value]);
                }

                $purchaseLine->setSeatsByEvent($seatsByEvent);

                $ticket = new Ticket();    
                foreach ($ticketAttributes as $value) {
                    $ticket->__set($value, $row[$value]);
                }

                $ticket->setPurchaseLine($purchaseLine);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $ticket;
    }

    /**
     * Used for qrCode
     */
    public function getByTicketCode($ticketCode)
    {
        $parameters = get_defined_vars();
        $ticket = null;  
        
        try {
            $ticketAttributes = array_keys(Ticket::getAttributes());

            $query = "SELECT *
                    FROM " . $this->tableName ." ti 
                    INNER JOIN ".$this->tableNamePurchaseLines." pl 
                    ON ti.idPurchaseLine = pl.idPurchaseLine 
                    INNER JOIN ".$this->tableNameSeatsByEvents." se 
                    ON pl.idSeatsByEvent = se.idSeatsByEvent 
                    INNER JOIN ".$this->tableNameSeatsTypes." st 
                    ON se.idSeatType = st.idSeatType 
                    INNER JOIN ".$this->tableNameEventByDates." ed 
                    ON se.idEventByDate = ed.idEventByDate 
                    INNER JOIN ".$this->tableNameEvents." e 
                    ON ed.idEvent = e.idEvent 
                    INNER JOIN ".$this->tableNameTheaters." t 
                    ON ed.idTheater = t.idTheater 
                    WHERE ticketCode = :".key($parameters)." 
                    AND ti.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);

            if(sizeof($resultSet)>1){
                throw new Exception(__METHOD__." error: Query returned ".sizeof($resultSet)." result/s, expected 1");
            }

            $theaterAttributes = array_keys(Theater::getAttributes());
            $eventAttributes = array_keys(Event::getAttributes());
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());
            
            foreach ($resultSet as $row)
            {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                $eventByDate->setEvent($event);
                $eventByDate->setTheater($theater);

                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);

                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) {
                    $purchaseLine->__set($value, $row[$value]);
                }

                $purchaseLine->setSeatsByEvent($seatsByEvent);

                $ticket = new Ticket();    
                foreach ($ticketAttributes as $value) {
                    $ticket->__set($value, $row[$value]);
                }

                $ticket->setPurchaseLine($purchaseLine);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }

        return $ticket;
    }

    public function getAll()
    {
        $ticketList = array();
        
        try {
            $query = "SELECT *
                    FROM " . $this->tableName ." ti 
                    INNER JOIN ".$this->tableNamePurchaseLines." pl 
                    ON ti.idPurchaseLine = pl.idPurchaseLine 
                    INNER JOIN ".$this->tableNameSeatsByEvents." se 
                    ON pl.idSeatsByEvent = se.idSeatsByEvent 
                    INNER JOIN ".$this->tableNameSeatsTypes." st 
                    ON se.idSeatType = st.idSeatType 
                    INNER JOIN ".$this->tableNameEventByDates." ed 
                    ON se.idEventByDate = ed.idEventByDate 
                    INNER JOIN ".$this->tableNameEvents." e 
                    ON ed.idEvent = e.idEvent 
                    INNER JOIN ".$this->tableNameTheaters." t 
                    ON ed.idTheater = t.idTheater 
                    WHERE ti.enabled = 1";

            $resultSet = $this->connection->Execute($query);

            $theaterAttributes = array_keys(Theater::getAttributes());
            $eventAttributes = array_keys(Event::getAttributes());
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());
            $ticketAttributes = array_keys(Ticket::getAttributes());
            

            foreach ($resultSet as $row)
            {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                $eventByDate->setEvent($event);
                $eventByDate->setTheater($theater);

                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);

                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) {
                    $purchaseLine->__set($value, $row[$value]);
                }

                $purchaseLine->setSeatsByEvent($seatsByEvent);

                $ticket = new Ticket();    
                foreach ($ticketAttributes as $value) {
                    $ticket->__set($value, $row[$value]);
                }

                $ticket->setPurchaseLine($purchaseLine);

                array_push($ticketList, $ticket);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $ticketList;
    }

    public function getAllByClient($idClient)
    {
        $parameters = get_defined_vars();
        $ticketList = array();
        
        try {
            $query = "SELECT *
                    FROM " . $this->tableName ." ti 
                    INNER JOIN ".$this->tableNamePurchaseLines." pl 
                    ON ti.idPurchaseLine = pl.idPurchaseLine 
                    INNER JOIN ".$this->tableNamePurchases." pu
                    ON pl.idPurchase = pu.idPurchase
                    INNER JOIN ".$this->tableNameClients." cl
                    ON pu.idClient = cl.idClient
                    INNER JOIN ".$this->tableNameSeatsByEvents." se 
                    ON pl.idSeatsByEvent = se.idSeatsByEvent 
                    INNER JOIN ".$this->tableNameSeatsTypes." st 
                    ON se.idSeatType = st.idSeatType 
                    INNER JOIN ".$this->tableNameEventByDates." ed 
                    ON se.idEventByDate = ed.idEventByDate 
                    INNER JOIN ".$this->tableNameEvents." e 
                    ON ed.idEvent = e.idEvent 
                    INNER JOIN ".$this->tableNameTheaters." t 
                    ON ed.idTheater = t.idTheater 
                    WHERE cl.idClient = :".key($parameters)." 
                    AND ti.enabled = 1
                    AND pu.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);

            $theaterAttributes = array_keys(Theater::getAttributes());
            $eventAttributes = array_keys(Event::getAttributes());
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());
            $ticketAttributes = array_keys(Ticket::getAttributes());
            

            foreach ($resultSet as $row)
            {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                $eventByDate->setEvent($event);
                $eventByDate->setTheater($theater);

                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);

                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) {
                    $purchaseLine->__set($value, $row[$value]);
                }

                $purchaseLine->setSeatsByEvent($seatsByEvent);

                $ticket = new Ticket();    
                foreach ($ticketAttributes as $value) {
                    $ticket->__set($value, $row[$value]);
                }

                $ticket->setPurchaseLine($purchaseLine);

                array_push($ticketList, $ticket);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $ticketList;
    }

    public function getAllByPurchase($idPurchase)
    {
        $parameters = get_defined_vars();
        $ticketList = array();
        
        try {
            $query = "SELECT *
                    FROM " . $this->tableName ." ti 
                    INNER JOIN ".$this->tableNamePurchaseLines." pl 
                    ON ti.idPurchaseLine = pl.idPurchaseLine 
                    INNER JOIN ".$this->tableNamePurchases." pu
                    ON pl.idPurchase = pu.idPurchase
                    INNER JOIN ".$this->tableNameSeatsByEvents." se 
                    ON pl.idSeatsByEvent = se.idSeatsByEvent 
                    INNER JOIN ".$this->tableNameSeatsTypes." st 
                    ON se.idSeatType = st.idSeatType 
                    INNER JOIN ".$this->tableNameEventByDates." ed 
                    ON se.idEventByDate = ed.idEventByDate 
                    INNER JOIN ".$this->tableNameEvents." e 
                    ON ed.idEvent = e.idEvent 
                    INNER JOIN ".$this->tableNameTheaters." t 
                    ON ed.idTheater = t.idTheater 
                    WHERE pu.idPurchase = :".key($parameters)." 
                    AND ti.enabled = 1
                    AND pu.enabled = 1";

            $resultSet = $this->connection->Execute($query,$parameters);

            $theaterAttributes = array_keys(Theater::getAttributes());
            $eventAttributes = array_keys(Event::getAttributes());
            $eventByDateAttributes = array_keys(EventByDate::getAttributes());
            $seatsByEventAttributes = array_keys(SeatsByEvent::getAttributes());
            $seatTypeAttributes = array_keys(SeatType::getAttributes());
            $purchaseLineAttributes = array_keys(PurchaseLine::getAttributes());
            $ticketAttributes = array_keys(Ticket::getAttributes());
            

            foreach ($resultSet as $row)
            {
                $theater = new Theater();
                foreach ($theaterAttributes as $value) {
                    $theater->__set($value, $row[$value]);
                }

                $event = new Event();
                foreach ($eventAttributes as $value) {
                    $event->__set($value, $row[$value]);
                }

                $eventByDate = new EventByDate();
                foreach ($eventByDateAttributes as $value) {
                    $eventByDate->__set($value, $row[$value]);
                }

                $eventByDate->setEvent($event);
                $eventByDate->setTheater($theater);

                $seatsByEvent = new SeatsByEvent();
                foreach ($seatsByEventAttributes as $value) {
                    $seatsByEvent->__set($value, $row[$value]);
                }

                $seatType = new SeatType();
                foreach ($seatTypeAttributes as $value) {
                    $seatType->__set($value, $row[$value]);
                }

                $seatsByEvent->setEventByDate($eventByDate);
                $seatsByEvent->setSeatType($seatType);

                $purchaseLine = new PurchaseLine();
                foreach ($purchaseLineAttributes as $value) {
                    $purchaseLine->__set($value, $row[$value]);
                }

                $purchaseLine->setSeatsByEvent($seatsByEvent);

                $ticket = new Ticket();    
                foreach ($ticketAttributes as $value) {
                    $ticket->__set($value, $row[$value]);
                }

                $ticket->setPurchaseLine($purchaseLine);

                array_push($ticketList, $ticket);
            }
        } catch (PDOException $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        } catch (Exception $ex) {
            throw new Exception (__METHOD__." error: ".$ex->getMessage());
        }
        
        return $ticketList;
    }

    /**
     * Updates values that are diferent from the ones recieved in the object Ticket
     */
    public function Update(Ticket $oldTicket, Ticket $newTicket){
        $valuesToModify = "";
       
        try {
            $oldTicketArray = $oldTicket->getAll(); //convert object to array of values
            $oldTicketArray["idPurchaseLine"] = $oldTicket->getPurchaseLine()->getIdPurchaseLine();
            $ticketArray = $newTicket->getAll();
            $ticketArray["idPurchaseLine"] = $newTicket->getPurchaseLine()->getIdPurchaseLine();
            $parameters["idTicket"] = $oldTicket->getIdTicket();

            /**
             * Check if a value is different from the one on the database, if different, sets the column and
             * value for the SET query
             */
            foreach ($oldTicketArray as $key => $value) {
                if ($key != "idTicket") {
                    if ($oldTicketArray[$key] != $ticketArray[$key]) {
                        $valuesToModify .= $key . " = " . ":".$key.", ";
                        $parameters[$key] = $ticketArray[$key];
                    }
                }
            }

            if($valuesToModify != '')
            {
                $valuesToModify = rtrim($valuesToModify, ", "); //strip ", " from last character

                $query = "UPDATE ".$this->tableName." 
                    SET ".$valuesToModify." 
                    WHERE idTicket = :idTicket";
            
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
    public function Delete(Ticket $ticket)
    {
        try {
            $parameters["idTicket"] = $ticket->getIdTicket();

            $query = "UPDATE ".$this->tableName." 
                SET enabled = 0 
                WHERE idTicket = :idTicket";

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
}
