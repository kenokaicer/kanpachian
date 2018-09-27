<?php //namespace Dao;

    class Conexion {
        
        # Métodos
        
        public function conectar() 
        {
            try
            {
             
                 return new \PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME, DB_USER, DB_PASS); // devuelve nuevo objeto pdo.
            }
             catch (PDOException $e) 
            {
			echo $this->$e->getMessage();
			die();
	    }
        }
    }
?>