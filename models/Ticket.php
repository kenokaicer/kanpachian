<?php
namespace Models;

class Ticket extends Attributes
{
    protected $idTicket;
    protected $ticketCode; // uniqid()
    protected $qrCode;

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
