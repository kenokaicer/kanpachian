<?php
namespace Models;

use Models\Client as Client;

class User
{
    private $idUser;
    private $client; //Class Client.
    private $username;
    private $password;
    private $rol; //Enum Rol.

	public function getClient()
	{
		return $this->client;
	}

    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

	public function getUsername()
	{
		return $this->username;
	}

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

	public function getPassword()
	{
		return $this->password;
	}

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

	public function getRol()
	{
		return $this->rol;
	}

    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

	public function getId()
	{
		return $this->id;
	}
}