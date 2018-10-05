<?php

namespace Models;

use SplEnum as SplEnum;

class SeatType extends SplEnum //Tipo_plaza
{
    const platea = "Platea Fila 1 a Fila 15";
    const superpullman = "Super Pullman Fila 15 a Fila 30";
    const pullman = "Pullman Fila 31 a Fila 60";
    const campo = "Campo";
    const palco = "Palco";
}