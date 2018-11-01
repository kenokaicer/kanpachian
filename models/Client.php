<?php

namespace Models;

use Models\CreditCard as CreditCard;
use Models\User as User;

class Client extends Attributes
{
    protected $idClient;
    protected $name;
    protected $lastname;
    protected $dni;
    private $creditCard; //CreditCard Class.
    private $user; //User Class

    public function getIdClient()
    {
        return $this->idClient;
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    public function getCreditCard()
    {
        return $this->creditCard;
    }

    public function setCreditCard(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    public function getUser()
	{
		return $this->user;
	}

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
