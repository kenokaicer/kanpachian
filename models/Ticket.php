<?php
namespace Models;

class Ticket
{
    private $idTicket;
    private $ticketNumber;
    private $qr;
    private $date; //Date of purchase.
    private $seatType; //Class SeatType;

    public function getIdTicket()
    {
        return $this->idTicket;
    }

    public function setIdTicket($idTicket)
    {
        $this->idTicket = $idTicket;

        return $this;
    }

    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    public function getQr()
    {
        return $this->qr;
    }

    public function setQr($qr)
    {
        $this->qr = $qr;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getSeatType()
    {
        return $this->seatType;
    }

    public function setSeatType($seatType)
    {
        $this->seatType = $seatType;

        return $this;
    }
}
