<?php
namespace Models;

class Ticket extends Attributes
{
    protected $idTicket;
    protected $ticketNumber;
    protected $qrCode;
    protected $date; //Date of purchase. Timestamp
    protected $seatType; //Class SeatType;

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

    public function setSeatType(SeatType $seatType)
    {
        $this->seatType = $seatType;

        return $this;
    }

    public function getQrCode()
    {
        return $this->qrCode;
    }

    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;

        return $this;
    }
}
