<?php
namespace Config;

class Router
{

    /**
     * Se encarga de direccionar a la pagina solicitada
     * @param Request
     */
    public function __construct()
    {

    }
    public static function direccionar(Request $request) //notar que es static

    {

        //$controlador = "Controladora".$request->getControladora(); //en español
        $controlador = $request->getControladora() . "Controller"; //en ingles
        $metodo = $request->getMetodo();

        $parametros = array();
        $parametros = $request->getParametros(); //ya deberia de meter un array aca, chequear

        $objeto = "controllers\\" . $controlador; //mostrar
        $controlador = new $objeto;

        /*echo "controlador:";
        var_dump($controlador);
        echo "metodo:";
        var_dump($metodo);
        echo "parametros:";
        var_dump($parametros);*/

        if (!isset($parametros)) {
            call_user_func(array($controlador, $metodo));
        } else {
            call_user_func_array(array($controlador, $metodo), $parametros);
        }
    }
}
