<?php namespace dao;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

  class userDao extends baseDao
  {
      private $name='users';
      
      public function __construct() {
          parent::__construct($this->name);
      }
      
       public static function get()
                {
                    static $inst = null;
                    if ($inst === null) {
                        $inst = new userDao();
                    }
                    return $inst;
                    
                }
 
      
        public  function agregar($value) 
        {    
         $this->CallProcedure("agregarGenero", $datos);    
        }
        
        public function autenticar($datos)
        {         
            $sql = "SELECT * FROM usuarios WHERE username =?  AND password= ?";
            $obj_pdo = new Conexion();
            $conexion = $obj_pdo->conectar();
  
            $sentencia = $conexion->prepare($sql);
            
            $sentencia->bindParam(1, $datos['username'], \PDO::PARAM_STR);
            $sentencia->bindParam(2, $datos['password'], \PDO::PARAM_STR);
            try 
            {
                $user = $sentencia->execute();
                $array = array();
                while ($row = $sentencia->fetch()) 
                {
                    $array[] = $row;
                }
            } 
            catch (PDOException $e) 
            {
                echo "DataBase Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            } 
            catch (Exception $e) 
            {
                echo "General Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            }
           
             return $array;
        }
        
        public function register($user)
        { 
        $admin = 'false';    
        if (array_key_exists("administrador",$user))
                $admin='true';
   
        
        $sql = "INSERT INTO  users (username, password, admin) VALUES (? ,?," . $admin . ")";
        var_dump($sql);
        $obj_pdo = new Conexion();
        $conexion = $obj_pdo->conectar();
        
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(1, $user['username'], \PDO::PARAM_STR);
        $sentencia->bindParam(2, $user['password'], \PDO::PARAM_STR);
        try {
            $sentencia->execute();
        } catch (PDOException $e) {
            echo "DataBase Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
             return;
        } catch (Exception $e) {
            echo "General Error: No se pudo agregar el usuario.<br>" . $e->getMessage();
            return;
        }
             if($sentencia == true)
                 \controllers\defaultController::registroCompletado();
             
             
        }
        
  }