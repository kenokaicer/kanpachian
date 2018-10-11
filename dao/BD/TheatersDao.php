<?php
namespace dao\BD;

use Dao\SingletonDao as SingletonDao;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Dao\BD\Connection as Connection;
use Models\Theater as Theater;
use PDOException as PDOException;
use PDO as PDO;

class TheatersDao extends SingletonDao implements ITheaterDao
{
    private $message;
    private $table = 'Theaters';
    private $table2 = 'SeatTypes_x_Theater';

    public function Add(Theater $theater){
        $querry = "INSERT INTO ".$this->table." (name, location, image, maxCapacity) VALUES (?,?,?,?);";
        $obj_pdo = new Connection();
        $pdo_connection = $obj_pdo->connect();
        $sentence = $pdo_connection->prepare($querry);
        $sentence->bindValue(1, $theater->getName(), \PDO::PARAM_STR);
        $sentence->bindValue(2, $theater->getLocation(), \PDO::PARAM_STR);
        $sentence->bindValue(3, $theater->getImage(), \PDO::PARAM_STR);
        $sentence->bindValue(4, $theater->getMaxCapacity(), \PDO::PARAM_INT);

        try {
            $sentence->execute();
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo agregar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo agregar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
        	
        $querry= "SELECT LAST_INSERT_ID()";
        $sentence = $pdo_connection->prepare($querry);

        try {
            $sentence->execute();
        } catch (PDOException $e) {
            echo "<script> alert('Error getting last insert id, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('Error getting last insert id, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }

        $row = $sentence->fetch(PDO::FETCH_ASSOC);
        $idTheater = array_shift($row);

        foreach ($theater->getSeatTypes() as $value) {
            $querry = "INSERT INTO ".$this->table2." (pfkSeatType, pfkTheater) VALUES (?,?);"; //error pfkSeatTpye column doesn't exist??
            $sentence = $pdo_connection->prepare($querry);
            $sentence->bindValue(1, $value->getIdSeatType(), \PDO::PARAM_STR);
            $sentence->bindValue(2, $idTheater, \PDO::PARAM_STR);
            try {
                $sentence->execute();
            } catch (PDOException $e) {
                echo "<script> alert('Error inserting SeatType, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
            } catch (Exception $e) {
                echo "<script> alert('Error inserting SeatType, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
            }
        }
        
        echo "<script> alert('Teatro agregado exitosamente');</script>";
    }

    public function Retrieve($var){
    }
    
    public function RetrieveAll(){
        $querry = "SELECT * FROM " . $this->table;
        $obj_pdo = new Connection_old();
        $conexion = $obj_pdo->connect();
        $sentence = $conexion->prepare($querry);

        try {
            $sentence->execute();
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo listar los teatros, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo listar los teatros, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }

        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $row;
        }
        if (!empty($array))
            return $array;
    }

    public function Update(Theater $oldTheater, Theater $newTheater){
        $valuesToModify = "";
        $oldTheaterArray = $oldTheater->getAll();  //convert object to array of values
        $theaterArray = $newTheater->getAll();

        foreach ($oldTheaterArray as $key => $value) { 
            if($key != "idTheater"){
                if($oldTheaterArray[$key] != $theaterArray[$key]){
                    $valuesToModify .= $key." = "."'".$theaterArray[$key]."', ";
                }
            }
        }

        $valuesToModify = rtrim($valuesToModify,", "); //strip ", " from last character

        $query = "UPDATE ".$this->table." SET ".$valuesToModify." WHERE idTheater = ".$oldTheater->getIdTheater();
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            echo "<script> alert('Teatro modificado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo modificar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) { 
            echo "<script> alert('No se pudo modificar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
    }

    public function Delete($id){
        $query = "DELETE FROM " . $this->table . " WHERE idTheater = " . $id;
        $obj_pdo = new Connection();
        $pdoConexion = $obj_pdo->connect();
        $sentence = $pdoConexion->prepare($query);

        try {
            $sentence->execute();
            echo "<script> alert('Teatro eliminado exitosamente');</script>";
        } catch (PDOException $e) {
            echo "<script> alert('No se pudo eliminar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        } catch (Exception $e) {
            echo "<script> alert('No se pudo eliminar el teatro, codigo de error: " . str_replace("'","",$e->getMessage()) . "');</script>";
        }
    }
}