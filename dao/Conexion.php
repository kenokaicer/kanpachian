<?php //namespace Dao;

    class Conexion {
        
        # Métodos
        
        public function conectar() 
        {
             try
            {
             
                 //return new \PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME, DB_USER, DB_PASS);
              $p = new \PDO('mysql:host=www.neonlab.com.ar;dbname=neonlab1_gotoevent;charset=utf8','neonlab1_termo','lluviadehamburguesas');
              $p->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
             
                 return $p; 
            }
             catch (PDOException $e) 
            {
            echo $this->e->getMessage();
            die();
            }
        }
    }
?>