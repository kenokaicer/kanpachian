<?php 
    namespace Config;

    use Config\Request as Request;

    class Router
    {
        public static function Route(Request $request)
        {
            
                $controllerName = $request->getcontroller() . 'Controller';

                $methodName = $request->getmethod();

                $methodParameters = $request->getparameters();          

                $controllerClassName = "Controllers\\". $controllerName;      

            if(class_exists($controllerClassName)) //Checks if there is a controller or method with that name //dispaying php autoload warning, that should go away in a server
            {
                $controller = new $controllerClassName;

                if(method_exists($controller,$methodName)) //Checks if method of that controller exists
                {
                    if(!isset($methodParameters)){            
                    call_user_func(array($controller, $methodName));
                    }else{
                        call_user_func_array(array($controller, $methodName), $methodParameters);
                    }
                }else{
                    $controllerClassName = "Controllers\\HomeController";   
                    $controller = new $controllerClassName;
                    call_user_func(array($controller, "notFound"));
                }
  
            }else{
                $controllerClassName = "Controllers\\HomeController";   
                $controller = new $controllerClassName;
                call_user_func(array($controller, "notFound"));
            }
        }
    }

?>