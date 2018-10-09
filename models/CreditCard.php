<?php

namespace Models;

class CreditCard
{
    private $idCreditCard;
    private $creditCardNumber;
    private $expirationDate;
    private $cardHolder;

	public function getIdCreditCard()
	{
		return $this->idCreditCard;
	}

    public function setIdCreditCard($idCreditCard)
    {
        $this->idCreditCard = $idCreditCard;

        return $this;
    }

	public function getCreditCardNumber()
	{
		return $this->creditCardNumber;
	}

    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

	public function getExpirationDate()
	{
		return $this->expirationDate;
	}

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

	public function getCardHolder()
	{
		return $this->cardHolder;
	}

    public function setCardHolder($cardHolder)
    {
        $this->cardHolder = $cardHolder;

        return $this;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }
}