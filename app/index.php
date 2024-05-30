<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS
require_once './controllers/trabajadoresControllers/bartenderController.php';
require_once './controllers/trabajadoresControllers/mozoController.php';
require_once './controllers/trabajadoresControllers/cocineroController.php';
require_once './controllers/trabajadoresControllers/cerveceroController.php';


$app = AppFactory::create();

// Set base path
$app->setBasePath('/lacomanda/app');

//DEFAULT RAIZ
$app->get('/', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write("Bienvenido a nuestra APP!");
    return $response;
});

$app->group('/abm', function (RouteCollectorProxy $group) 
{
    //BARTENDERS
    $group->get('/bartenders', \bartenderController::class . ':TraerTodos');
    $group->post('/bartenders', \bartenderController::class . ':CargarUno');
    
    //COCINEROS
    $group->get('/cocineros', \cocineroController::class . ':TraerTodos');
    $group->post('/cocineros', \cocineroController::class . ':CargarUno');

    //CERVECEROS
    $group->get('/ceverceros', \cerveceroController::class . ':TraerTodos');
    $group->post('/ceverceros', \cerveceroController::class . ':CargarUno');

    //MOZOS
    $group->get('/mozos', \mozoController::class . ':TraerTodos');
    $group->post('/mozos', \mozoController::class . ':CargarUno');
});


$app->run();