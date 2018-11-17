<?php
namespace Models;

class Ticket extends Attributes
{
    protected $idTicket;
    protected $ticketCode; // uniqid()
    protected $qrCode;
    private $theater; // class
    private $date;

	public function getIdTicket()
	{
		return $this->idTicket;
	}

    public function setIdTicket($idTicket)
    {
        $this->idTicket = $idTicket;

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

	public function getQrCode()
	{
		return $this->qrCode;
	}

    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;

        return $this;
    }

	public function getTheater()
	{
		return $this->theater;
	}

    public function setTheater($theater)
    {
        $this->theater = $theater;

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
}
