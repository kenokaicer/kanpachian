<?php //namespace controllers;

    //use modelo;
    //use dao;
    //use config;
    
  
class pedidosController
    {  
       public static function get() {
        static $inst = null;
        if ($inst === null) {
            $inst = new pedidosController();
        }
        return $inst;
}

	public function RegistroTitular($datos)
	{
               $titu = new \modelo\titular($datos['nombre'],$datos['apellido'],$datos['dni'],$datos['correo'],$datos['password']);
               dao\titularDao::get()->agregar($titu);
               // QRcode::png($titu['code'])');
               
              \Config\Redirect::adminpanel();
               QRcode::png(implode('/',$titu));
               
    }
    public function showView()
        {
            print("Pedido enviado");
        }
        public function login($datos)
        {
            $usuario = dao\titularDao::get()->checkLogin($datos['dni'],$datos['password']);;
            if($usuario!=null)
            {
                 \config\Session::setSession('UsuarioId', $usuario[0]['UsuarioId']);
                 \config\Session::setSession('Nombre', $usuario[0]['Nombre']);
                 \config\Session::setSession('Apellido', $usuario[0]['Apellido']);
                 \config\Session::setSession('Administrador', $usuario[0]['Administrador']);
                 
                 if($usuario[0]['Administrador']!=1)
                  $this->userpanel();
                  else
                  $this->adminpanel();
                  
            }
            else
                print("usuario no encontrado");
        }
        
      
       public function obtenerPedidos()
       {

       	$pedidos = dao\pedidosDao::get()->traerTodos(); 
        $arrayGustos = array(
 
        );
      
        foreach($pedidos as $x => $x_value) 
        {
            //print($x_value["post_title"]); //Chocolatechocolate amargoFrutilla
            array_push($arrayGustos, $x_value["post_title"]);
        }

        return $arrayGustos;

       }

       public function todos()
       {
        print("LLego al todos");
       	$pedidos = \src\dao\pedidosDao::get()->traerTodosPost();  
        return $pedidos;
       }

       

       public function doFilter($array)
    {
        return array_filter($array, array($this, 'callbackMethodName'));
    }

    protected function callbackMethodName($element)
    {
        return $element  == "post_title";
    }

        public function multa($datos)
        {
            //ControladoraSemaforo::luzroja($datos);
            //dao\multasDao::get()->agregar($titu);
           dao\multasDao::get()->agregar($datos);
        }
        
          public function AgregarVehiculo($datos)
        {
            ControladoraAgregarVehiculo::cargar($datos);
        }

        public function Index()
	{       
           
          \Config\Redirect::login();
         
            
	}
         public function register()
	{       
           \Config\Redirect::register();
	}
        public function userpanel()
        {
            \Config\Redirect::userpanel();
        }
         public function adminpanel()
        {

     
    // reqired JS rendering lib 
    echo '<script type="text/javascript" src="../lib/js/qrcanvas.js"></script>'; 
     
    // Canvas and JS code output 
    echo $jsCanvasCode; 


            
        }
 
         public function showCars()
        {
            \Config\Redirect::showvehicles();
        }
        public function addCars()
        {
            \Config\Redirect::addvehicle();  
        }
         public function semaforo()
        {
            \Config\Redirect::semaforo();  
        }
        
        
}
?>