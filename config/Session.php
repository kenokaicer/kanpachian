<?php 
namespace config;
Class Session
{
    

    //Obtenemos el valor de uno de los indices de sesion
	static function getSession($name)
	{
            if (session_status() == PHP_SESSION_NONE)
            session_start();

            if(!empty($_SESSION[$name]))
                return $_SESSION[$name];
            else
                return null;
    }

    //Inicializamos un valor
        static function setSession($name, $data) 
        {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION[$name] = $data;
          
	}

	static function unsetUser()
        {
            unset($_SESSION['id']);
        }
	static function destroy()
	{
            session_destroy();
           
	}
}