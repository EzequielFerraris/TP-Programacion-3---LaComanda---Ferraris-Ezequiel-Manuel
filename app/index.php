<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS
require_once './controllers/trabajadoresControllers/trabajadoresController.php';
require_once './controllers/productosControllers/productosController.php';
require_once './controllers/mesasControllers/mesasController.php';
require_once './controllers/sociosControllers/socioController.php';
require_once './controllers/pedidosControllers/pedidosController.php';

$app = AppFactory::create();

// Set base path
$app->setBasePath('/lacomanda/app');

//DEFAULT RAIZ
$app->get('/', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write("Bienvenido a nuestra APP!");
    return $response;
});

$app->group('/socios', function (RouteCollectorProxy $group) 
{
    $group->get('/listar', \socioController::class . ':TraerTodos');
    $group->post('/cargar', \socioController::class . ':CargarUno');

});

$app->group('/trabajadores', function (RouteCollectorProxy $group) 
{
    
    $group->get('/listar', \trabajadoresController::class . ':TraerTodos');
    $group->post('/cargar', \trabajadoresController::class . ':CargarUno');
    
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    $group->get('/listar', \ProductosController::class . ':TraerTodos');
    $group->get('/{sector}', \ProductosController::class . ':TraerProductosPorSector');    
    $group->post('/cargar', \ProductosController::class . ':CargarUno');

});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    $group->get('/listar', \MesasController::class . ':TraerTodos');
    $group->post('/cargar', \MesasController::class . ':CargarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->get('/listar', \PedidosController::class . ':TraerTodos');
    $group->post('/cargar', \PedidosController::class . ':CargarUno');
});

$app->run();