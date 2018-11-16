<?php
namespace Models;

class Ticket extends Attributes
{
    protected $idTicket;
    protected $ticketCode; // uniqid()
    protected $qrCode;
    private $theater; // class
    private $date;

    public __construct($_idTicket,$_ticketCode,$_qrCode,$_theater,$_date)
    {
        $this->idTicket = $_idTicket;
        $this->ticketCode = $_ticketCode;
        $this->qrCode = $_qrCode;
        $this->theater = $_theater;
        $this->date = $_date;
    }   

    public function getIdTicket()
    {
        return $this->idTicket;
    }

    public function setIdTicket($idTicket)
    {
        $this->idTicket = $idTicket;

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

	public function getTicketCode()
	{
		return $this->ticketCode;
	}

    public function setTicketCode($ticketCode)
    {
        $this->ticketCode = $ticketCode;

        return $this;
    }
}
