<?php
//require __DIR__.'/vendor/autoload.php';
// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';




//$uri = //Get uri???
$uri = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader, array(
    // cache disabled, since this is just a testing project
    'cache' => false,
    'debug' => true,
    'strict_variables' => true
));
$twig->addExtension(new Twig_Extension_Debug());
// 3) create a few different "pages"
switch ($uri) {
    // The Homepage! (/)
    case '/':
        echo $twig->render('index.html', array(
            'pageTitle' => 'Suit Up!',
            'products' => array(
                'Serious Businessman',
                'Penguin Dress',
                'Sportstar Penguin',
                'Angel Costume',
                'Penguin Accessories',
                'Super Cool Penguin',
            ),
        ));
        break;
    // All other pages
    default:
        // if we have anything else, render the URL + .twig (e.g. /about -> about.twig)
        $template = substr($uri, 1).'index.html';
        echo $twig->render($template, array(
            'title' => 'Some random page!',
        ));
}


/* AREA DE TESTING */

require "Ticket.php";

// Create Router instance
$router = new \Bramus\Router\Router();

$bar = new Ticket ("1", "evento");
$bar->nombreMetodo();

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

// Before Router Middleware
$router->before('GET', '/.*', function () {
    header('X-Powered-By: bramus/router');
});

// Static route: / (homepage)
$router->get('/', function () {
    echo '<h1>bramus/router</h1><p>Try these routes:<p><ul><li>/hello/<em>name</em></li><li>/blog</li><li>/blog/<em>year</em></li><li>/blog/<em>year</em>/<em>month</em></li><li>/blog/<em>year</em>/<em>month</em>/<em>day</em></li><li>/movies</li><li>/movies/<em>id</em></li></ul>';
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
    $router->post('/', function () {
        echo 'add movie';
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