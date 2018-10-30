<?php
namespace Models;

use Models\Client as Client;

class User
{
    private $idUser;
    private $username;
    private $password;
    private $email;
    private $role; //Enum Rol.

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

    public function getRole()
    {
        return $this->rol;
    }

    public function setRole($rol)
    {
        $this->role = $role;

        return $this;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
