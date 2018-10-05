<?php

namespace Models;

use Models\SeatType as SeatType;

/**
 * Type of seat of the event, prices and availability.
 */
class SeatsByEvent //Plaza_Evento
{
    private $id;
    private $seatType; // This is an enum.
    private $quantity;
    private $price;
    private $remnants; // This is a self calulated value.
}