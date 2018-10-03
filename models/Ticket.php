<?php
class Ticket 
{
    private $id = "";
    private $nameEspectaculo = "";
    
    public function __construct($_id, $_nameEspectaculo){
        $this->id = $_id;
        $this->nameEspectaculo = $_nameEspectaculo;
    }
    
    public function nameMetodo()
    {
    	print($this->$id);
    }
}

?>