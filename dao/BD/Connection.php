<?php namespace Dao\BD;

    class Connection {
        
        # Métodos
        /**
         * TO DO 
         * check if the Config file works with this
         */
        public function connect() 
        {
             try
            {  
              //$p = new \PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME.";charset=utf8"., DB_USER, DB_PASS);
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