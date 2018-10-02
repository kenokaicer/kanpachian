<?php namespace dao;

class listaSessionExample
{
    private $array = array();

    public function getListaArray()
    {
        if (!isset($_SESSION['listaArray'])) {
            $_SESSION['listaArray'] = array();
        }
        return $_SESSION['listaArray'];
    }

    public function setListaArray($listaArray)
    {
        $_SESSION['listaArray'] = $listaArray;
    }

}
