<?php

namespace Models;

class Purchase// Compra - Carrito
{
    private $idPurchase;
    private $date;
    private $purchaseLines = array(); //Array of Class PurcahseLine

    public function getIdPurchase()
    {
        return $this->idPurchase;
    }

    public function setIdPurchase($idPurchase)
    {
        $this->idPurchase = $idPurchase;

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

    public function getPurchaseLines()
    {
        return $this->purchaseLines;
    }

    public function addPurchaseLines($purchaseLine)
    {
        $this->purchaseLines[] = $purchaseLine;
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
