<?php
class Ticket {
    private $id = "";
    private $nombreEspectaculo = "";
    
    public function __construct($_id, $_nombreEspectaculo){
        $this->id = $_id;
        $this->nombreEspectaculo = $_nombreEspectaculo;
    }
    
    public function nombreMetodo(){
        print "La id es: " . $this->id . "<br>" . "El espectaculo es: " . $this->nombreEspectaculo;
        echo "nuevo cambio";
    }
}

?>