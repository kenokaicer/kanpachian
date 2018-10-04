<?php
namespace dao\BD;

use Dao\SingletonDao as SingletonDao;
use Dao\Interfaces\ITheaterDao as ITheaterDao;
use Dao\BD\Connection as Connection;
use Models\Theater as Theater;
use PDOException as PDOException; //this works?

class TheaterDao extends SingletonDao implements ITheaterDao
{
    private $table = 'Theaters';

    /**
     * Adds Theater objects into BD
     * check if $theater->getName() works intead of $theater['name']
     * missing require for returning to adecuate view?
     */
    public function Add(Theater $theater){
        $sql = "INSERT INTO ".$table." (name, location, image, maxCapacity) VALUES (?,?,?,?);";
        $obj_pdo = new Connection();
        $pdo_connection = $obj_pdo->connect();
        $sentence = $pdo_connection->prepare($sql);
        $sentence->bindParam(1, $theater->getName(), \PDO::PARAM_STR);
        $sentence->bindParam(2, $theater->location(), \PDO::PARAM_STR);
        $sentence->bindParam(3, $theater->getImage(), \PDO::PARAM_STR);
        $sentence->bindParam(4, $theater->getMaxCapacity(), \PDO::PARAM_STR);

        try{
            $sentence->excecute();
        }catch(PDOException $e){ //chequear de mostrar bien este error
            echo "DataBase Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            return;
        } catch (Exception $e) {
            echo "General Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            return;
        }
    }

    public function Retrieve($var){ //retrieve by what???
        /*$sql = "SELECT * FROM ".$table." 
        WHERE ".$var." == ;*/
    }
    
    public function RetrieveAll(){
        $sql = "SELECT * FROM " . $this->table;
        $obj_pdo = new Connection();
        $conexion = $obj_pdo->connect();
        $sentence = $conexion->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch()) {
            $array[] = $row;
        }
        if (!empty($array))
            return $array;
    }

    public function Update(Theater $theater){ //add button update and delete in a view list in the views
        $sql = "UPDATE ".$this->table." SET name=? , location=? ,image=?, max_capacity=? 
        WHERE name = " .$theater->getName(); //should this use and id??
        $obj_pdo = new Connection();
        $conexion = $obj_pdo->connect();
        $sentence = $conexion->prepare($sql);
        $sentence->bindParam(1, $theater->getName(), \PDO::PARAM_STR);
        $sentence->bindParam(2, $theater->location(), \PDO::PARAM_STR);
        $sentence->bindParam(3, $theater->getImage(), \PDO::PARAM_STR);
        $sentence->bindParam(4, $theater->getMaxCapacity(), \PDO::PARAM_STR);

        try {
            $sentence->execute();
        } catch (PDOException $e) {
            echo "DataBase Error.<br>" . $e->getMessage();
             return;
        } catch (Exception $e) {
            echo "General Error.<br>" . $e->getMessage();
            return;
        }
        
        if($sentence == true){
        //   \controllers\defaultController::registroCompletado();
        //redireccionar a donde corresponde     
        }
    }

    public function Delete($var){} //delete by what?? id? should I know id of the BD?
}