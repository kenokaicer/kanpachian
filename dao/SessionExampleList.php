<?php namespace Dao;

class SessionExampleList
{
    private $arrayList = array();

    public function getArrayList()
    {
        if (!isset($_SESSION['arrayList'])) {
            $_SESSION['arrayList'] = array();
        }
        return $_SESSION['arrayList'];
    }

    public function setArrayList($listaArray)
    {
        $_SESSION['arrayList'] = $listaArray;
    }

}
