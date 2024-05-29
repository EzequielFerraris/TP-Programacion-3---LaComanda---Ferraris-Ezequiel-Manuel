<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS
require_once './controllers/bartenderController.php';
require_once './controllers/mozoController.php';
require_once './controllers/cocineroController.php';
require_once './controllers/cerveceroController.php';


$app = AppFactory::create();

// Set base path
$app->setBasePath('/lacomanda/app');

$app->get('/', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write("Funciona!");
    return $response;
});

$app->group('/bartenders', function (RouteCollectorProxy $group) {
    $group->get('[/]', \bartenderController::class . ':TraerTodos');
    //$group->post('[/]', \bartenderController::class . ':CargarUno');
  });


$app->run();