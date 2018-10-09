<?php
namespace dao\BD;

use Dao\SingletonDao as SingletonDao;
use Dao\BD\Connection as Connection;
use Models\SeatsType as SeatsType;
use PDOException as PDOException;
use PDO as PDO;

class SeatsTypesDao extends SingletonDao
{
    private $message;
    private $table = 'SeatTypes';

    public function RetrieveAll(){
        $sql = "SELECT * FROM " . $this->table;
        $obj_pdo = new Connection();
        $conexion = $obj_pdo->connect();
        $sentence = $conexion->prepare($sql);

        try {
            $sentence->execute();
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo listar los teatros, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            
        }

        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $row;
        }
        if (!empty($array))
            return $array;
    }
}