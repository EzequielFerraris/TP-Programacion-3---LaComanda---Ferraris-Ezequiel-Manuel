<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

date_default_timezone_set("America/Argentina/Buenos_Aires");

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS
require_once './controllers/trabajadoresControllers/trabajadoresController.php';
require_once './controllers/productosControllers/productosController.php';
require_once './controllers/mesasControllers/mesasController.php';
require_once './controllers/sociosControllers/socioController.php';
require_once './controllers/pedidosControllers/pedidosController.php';
require_once './middleware/auth/authTrabajadorABM.php';
require_once './middleware/auth/authSocioABM.php';
require_once './middleware/auth/authProductoABM.php';
require_once './middleware/auth/authMesaABM.php';
require_once './middleware/auth/authPedidoABM.php';
require_once './middleware/paramsSet/paramsSetTrabajador.php';
require_once './middleware/paramsSet/paramsSetMesa.php';
require_once './middleware/paramsSet/paramsSetSocio.php';
require_once './middleware/paramsSet/paramsSetProducto.php';
require_once './middleware/paramsSet/paramsSetPedido.php';
require_once './middleware/users/validarSocio.php';
require_once './middleware/users/validarTrabajador.php';

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
    $group->post('/cargar', \socioController::class . ':CargarUno')->add(new AuthSocioABM()) //chequea tipos
                                                                    ->add(new ParamsSetSocio()) //chequea si se pasaron los campos
                                                                    ->add(new ValidarSocio()); //chequea permisos
});                     

$app->group('/trabajadores', function (RouteCollectorProxy $group) 
{
    //LISTAR
    $group->get('/listar', \trabajadoresController::class . ':TraerTodos');
    //CARGAR UN TRABAJADOR (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar', \trabajadoresController::class . ':CargarUno') ->add(new AuthTrabajadorABM()) //chequea tipos
                                                                        ->add(new ParamsSetTrabajador()) //chequea si se pasaron los campos
                                                                        ->add(new ValidarSocio()); //chequea permisos
});

$app->group('/productos', function (RouteCollectorProxy $group) 
{
    //LISTAR TODOS
    $group->get('/listar', \ProductosController::class . ':TraerTodos');
    //LISTAR POR SECTOR
    $group->get('/listar/{sector}', \ProductosController::class . ':TraerProductosPorSector');    
    //CARGAR UN PRODUCTO (REQUIERE PASSWORD ¿SOCIOS?)
    $group->post('/cargar', \ProductosController::class . ':CargarUno')->add(new AuthProductoABM()) //chequea tipos
                                                                        ->add(new ParamsSetProducto())
                                                                        ->add(new ValidarSocio()); //chequea permisos
});

$app->group('/mesas', function (RouteCollectorProxy $group) 
{
    //LISTAR TODAS
    $group->get('/listar', \MesasController::class . ':TraerTodos');
    $group->post('/cargar', \MesasController::class . ':CargarUno')->add(new AuthMesaABM()) //chequea tipos
                                                                    ->add(new ParamsSetMesa())  //chequea si se pasaron los campos
                                                                    ->add(new ValidarSocio()); //chequea permisos
});

$app->group('/pedidos', function (RouteCollectorProxy $group) 
{
    $group->get('/listar', \PedidosController::class . ':TraerTodos');
    $group->post('/cargar', \PedidosController::class . ':CargarUno')->add(new AuthPedidoABM()) //chequea tipos
                                                                        ->add(new ParamsSetPedido()) //chequea si se pasaron los campos
                                                                        ->add(new ValidarTrabajador("mozo")); //chequea permisos
});

$app->run();