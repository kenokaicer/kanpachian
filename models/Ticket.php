<?php
namespace Models;

use Models\PurchaseLine as PurchaseLine;

class Ticket extends Attributes
{
    protected $idTicket;
    protected $ticketCode; // uniqid()
    protected $qrCode;
    private $purchaseLine; //Class PurchaseLine

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

	public function getPurchaseLine()
	{
		return $this->purchaseLine;
	}

    public function setPurchaseLine(PurchaseLine $purchaseLine)
    {
        $this->purchaseLine = $purchaseLine;

        return $this;
    }
}
