<?php namespace Config;


 class Autoload
{
	public static function Start ()
	{
        spl_autoload_register(function($instancia)
        {
              $ruta=ROOT.str_replace("\\","/",$instancia).".php";
              include_once($ruta);
        });
     }
 }


 ?>