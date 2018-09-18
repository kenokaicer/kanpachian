<?php //namespace Dao;

    class Conexion {
        
        # Métodos
        
        public function conectar() 
        {
            try
            {
                /*
                $p = new \PDO('mysql:host=;dbname=ilpeccato_wp172',
                    'ilpeccato_kenokaicer',
                    '156047794swag',
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                */
             $p = new PDO('mysql:host=;dbname=ilpeccato_wp172;charset=utf8','ilpeccato_kenokaicer','156047794swag');
             $p->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
             
                 return $p; 
            }
             catch (PDOException $e) 
            {
                print("OCURRIO UN ERROR");
			echo $this->$e->getMessage();
	        }
        }
    }
?>