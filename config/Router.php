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

            /**
             * Checks if there is a controller or method with that name, otherwise go to 404 page
             * @ is to suppress the autoload warning
             */
            if(@class_exists($controllerClassName))
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