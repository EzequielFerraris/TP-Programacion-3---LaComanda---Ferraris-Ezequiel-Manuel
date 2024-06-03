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
    $group->get('[/]', \socioController::class . ':TraerTodos');
    $group->post('[/]', \socioController::class . ':CargarUno');

});

$app->group('/trabajadores', function (RouteCollectorProxy $group) 
{
    //BARTENDERS
    $group->get('/abm/bartenders', \bartenderController::class . ':TraerTodos');
    $group->post('/abm/bartenders', \bartenderController::class . ':CargarUno');
    
    //COCINEROS
    $group->get('/abm/cocineros', \cocineroController::class . ':TraerTodos');
    $group->post('/cocineros', \cocineroController::class . ':CargarUno');

    //CERVECEROS
    $group->get('/abm/ceverceros', \cerveceroController::class . ':TraerTodos');
    $group->post('/abm/ceverceros', \cerveceroController::class . ':CargarUno');

    //MOZOS
    $group->get('/abm/mozos', \mozoController::class . ':TraerTodos');
    $group->post('/abm/mozos', \mozoController::class . ':CargarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductosController::class . ':TraerTodos');
    $group->get('/{sector}', \ProductosController::class . ':TraerProductosPorSector');    
    $group->post('/', \ProductosController::class . ':CargarUno');

});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \MesasController::class . ':TraerTodos');
    $group->post('[/]', \MesasController::class . ':CargarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \PedidosController::class . ':TraerTodos');
    $group->post('/cargar', \PedidosController::class . ':CargarUno');
});

$app->run();