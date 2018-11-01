<?php

namespace Models;

use Models\Client as Client;
use Models\PurchaseLine as PruchaseLine;

class Purchase extends Attributes// Compra - Carrito

{
    protected $idPurchase;
    protected $date;
    protected $client; //Class Client
    protected $purchaseLines = array(); //Array of Class PurcahseLine

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

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    public function getPurchaseLines()
    {
        return $this->purchaseLines;
    }

    public function setPurchaseLines($purchaseLines)
    {
        $this->purchaseLines = $purchaseLines;

        return $this;
    }

    public function addPurchaseLines(PurchaseLine $purchaseLine)
    {
        $this->purchaseLines[] = $purchaseLine;
    }
}
