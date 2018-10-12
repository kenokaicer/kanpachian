<?php
namespace dao\BD;

use Dao\BD\Connection as Connection;
use Dao\SingletonDao as SingletonDao;
use PDO as PDO;
use PDOException as PDOException;
use Exception as Exception;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Models\Theater as Theater;

class TheatersDao extends SingletonDao implements ITheaterDao
{
    private $connection;
    private $tableName = 'Theaters';
    private $tableName2 = 'SeatTypes_x_Theater';

    //*-------------------------------------------------------------------------------------*//
    //When using this for copying other daos change Theater to primary object
    //and SeatType to secondary object, if more than one secondary object, 
    //code will need to be added.

    //Also code will need to be checked for secondary class as isn't inserting to a N:N table

    //Check array_pop if you need to unset a parameter that is an object or an array in primary object
    //*-------------------------------------------------------------------------------------*//

    protected function __construct(){
        $this->connection = Connection::getInstance();
        //See if having this here causes problems in the future, so far so good.
    }
    
    /**
     * Add Theater and all SeatTypes inide it to second table
     */
    public function Add(Theater $theater){

        $columns = "";
        $values = "";

        $parameters = array_filter($theater->getAll());
        array_pop($parameters); //unset SeatsType

        foreach ($parameters as $key => $value) {
            $columns .= $key.",";
            $values .= ":".$key.",";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        //---Add Theater---//

        $query = "INSERT INTO " . $this->tableName . " (".$columns.") VALUES (".$values.");";

        try {
            $addedRows = $this->connection->executeNonQuery($query, $parameters);
            if($addedRows!=1){
                throw new Exception("Number of rows added ".$addedRows.", expected 1");
            }
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo agregar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
            die();
            //throw $ex;
        } catch (Exception $ex) {
            die();
            echo "<script> alert('No se pudo agregar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
            //throw $ex;
        }
            
        //---Get ID of the Theater inserted---//

        $querry= "SELECT LAST_INSERT_ID()";

        try {
            $resultSet = $this->connection->Execute($query);
            $idTheater = array_shift($resultSet);
        } catch (PDOException $ex) {
            echo "<script> alert('Error getting last insert id, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
            throw $ex;
        } catch (Exception $ex) {
            echo "<script> alert('Error getting last insert id, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
            throw $ex;
        }   

        //---Insert each SeatType in a separate querry, N:N Table ---//

        foreach ($theater->getSeatTypes() as $value) {
            $querry = "INSERT INTO ".$this->tableName2." (idSeatType, idTheater) VALUES (:idSeatType,:idTheater);";
            
            $parameters["idSeatType"] = $value->getIdSeatType();
            $parameters["idTheater"] = $idTheater;

            try {
                $addedRows = $this->connection->executeNonQuery($query, $parameters);
                if($addedRows!=1){
                    throw new Exception("Number of rows added ".$addedRows.", expected 1, in SeatType");
                }
            } catch (PDOException $ex) {
                echo "<script> alert('Error inserting SeatType, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
                throw $ex;
            } catch (Exception $ex) {
                echo "<script> alert('Error inserting SeatType, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
                throw $ex;
            }
        }
        
        echo "<script> alert('Teatro agregado exitosamente');</script>";
    } //check if this is adding alrrigt withe select to the nn table

    public function Retrieve($var){
    }
    
    public function RetrieveAll(){
        $querry = "SELECT * FROM " . $this->table;
        $obj_pdo = new Connection_old();
        $conexion = $obj_pdo->connect();
        $sentence = $conexion->prepare($querry);

        try {
            $sentence->execute();
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo listar los teatros, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo listar los teatros, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
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
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo modificar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        } catch (Exception $ex) { 
            echo "<script> alert('No se pudo modificar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
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
        } catch (PDOException $ex) {
            echo "<script> alert('No se pudo eliminar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        } catch (Exception $ex) {
            echo "<script> alert('No se pudo eliminar el teatro, codigo de error: " . str_replace("'","",$ex->getMessage()) . "');</script>";
        }
    }
}