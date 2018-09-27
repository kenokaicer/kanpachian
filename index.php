<?php
//require __DIR__.'/vendor/autoload.php';
// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';
require_once 'config/Config.php';
//require_once 'src/controllers/pedidosController.php';
require_once 'controllers/pedidosController.php';
require_once 'dao/pedidosDao.php';


//use controllers;

if (extension_loaded('pdo')) 
{
    print("Existe pdo");
}

if (class_exists('PDO', false)) 
{ 
    print("Existe pdo2");

}

function CorroborarPDO()
{

        if (extension_loaded('pdo')) 
    {
        print("Existe pdo");
    }

    if (class_exists('PDO', false)) 
    { 
        print("Existe pdo2");

    }

    if (!defined('PDO::ATTR_DRIVER_NAME')) {
    echo 'PDO unavailable';
    }

    try{
    $dbh = new pdo( 'mysql:host=;dbname=basededatos',
                    'root',
                    '',
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

                    die(json_encode(array('outcome' => true)));
        }
        catch(PDOException $ex){
            die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
        }
}

//CorroborarPDO();
//require "Ticket.php";

/* Router */
$router = new \Bramus\Router\Router();

/*
$loader = new Twig_Loader_Filesystem(ROOT . 'views');
$twig = new Twig_Environment($loader, array(
    'cache' => ROOT . 'cache',
));
$template = $twig->load('home.php');
*/

//$bar = new Ticket ("1", "evento");
//$bar->nombreMetodo();


$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

// Before Router Middleware
$router->before('GET', '/.*', function () {
    
   //$template = $twig->load('home.php');
});

$router->before('GET', '/.*', function () {
    //require_once ROOT . 'views/home.php';
   //$template = $twig->load('home.php');
});

// Static route: / (homepage)
$router->get('/', function () {
require_once ROOT . 'views/home.php';
});

/* Sections */
$router->get('/demo', function () {
    require_once ROOT . 'views/demo.php';
});



// Static route: /hello
$router->get('/hello', function () {
    echo '<h1>bramus/router</h1><p>Visit <code>/hello/<em>name</em></code> to get your Hello World mojo on!</p>';
});

// Dynamic route: /hello/name
$router->get('/hello/(\w+)', function ($name) {
    echo 'Hello ' . htmlentities($name);
});

// Dynamic route: /ohai/name/in/parts
$router->get('/ohai/(.*)', function ($url) {
    echo 'Ohai ' . htmlentities($url);
});

    function kappa()
    {
        //To call the namespace-enabled version of either function or variable, prepend (::) the namespace name as follows −
        $x= new controllers\pedidosController();
        $peds = $x->obtenerPedidos(); 
        //var_dump($peds);
        return $peds;

       // $pedidos = pedidosController::get()->todos();
       //$pedidos = src\controllers\pedidosController::get()->todos();
       //var_dump($x);
    }

    function Route($callback)
    {
        print("Routeando");
        $res = explode("/", $callback);
        $folder = $res[0];
        $controller = $res[1];
        $method = $res[2];
        if(isset($res[3]))
        {
         $param = $res[3];
        }

 
        call_user_func(array("\\".$folder."\\".$controller, $method)); 
        //call_user_func_array(array($controller, $method), array($param));
        
    }

    function obtenerDaoPeds()
    {
       
        $pedidos = pedidosDao::get()->traerTodosPost();
        return $pedidos;
       // var_dump($pedidos);
    }

// Dynamic route with (successive) optional subpatterns: /blog(/year(/month(/day(/slug))))
$router->get('/blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', function ($year = null, $month = null, $day = null, $slug = null) {
    if (!$year) {
        echo 'Blog overview';
        return;
    }
    if (!$month) {
        echo 'Blog year overview (' . $year . ')';
        return;
    }
    if (!$day) {
        echo 'Blog month overview (' . $year . '-' . $month . ')';
        return;
    }
    if (!$slug) {
        echo 'Blog day overview (' . $year . '-' . $month . '-' . $day . ')';
        return;
    }
    echo 'Blogpost ' . htmlentities($slug) . ' detail (' . $year . '-' . $month . '-' . $day . ')';
});

// Subrouting
$router->mount('/movies', function () use ($router) {

    // will result in '/movies'
    $router->get('/', function () {
        echo 'movies overview';
    });

    // will result in '/movies'
    $router->post('/', function () 
    {
        echo 'POSTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT';
    });

    // will result in '/movies/id'
    $router->get('/(\d+)', function ($id) {
        echo 'movie id ' . htmlentities($id);
    });

    // will result in '/movies/id'
    $router->put('/(\d+)', function ($id) {
        echo 'Update movie id ' . htmlentities($id);
    });

});

$router->run();




/*
 * http://php.net/manual/es/function.htmlentities.php
*/
?>