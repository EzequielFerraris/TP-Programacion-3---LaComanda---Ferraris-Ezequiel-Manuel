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
require_once './controllers/trabajadoresControllers/mozosController.php';
require_once './controllers/trabajadoresControllers/bartendersController.php';
require_once './controllers/trabajadoresControllers/cervecerosController.php';
require_once './controllers/trabajadoresControllers/cocinerosController.php';

require_once './middleware/auth/authTrabajadorABM.php';
require_once './middleware/auth/authSocioABM.php';
require_once './middleware/auth/authProductoABM.php';
require_once './middleware/auth/authMesaABM.php';
require_once './middleware/auth/authPedidoABM.php';
require_once './middleware/auth/authPedidoProductoABM.php';

require_once './middleware/paramsSet/paramsSetTrabajador.php';
require_once './middleware/paramsSet/paramsSetMesa.php';
require_once './middleware/paramsSet/paramsSetSocio.php';
require_once './middleware/paramsSet/paramsSetProducto.php';
require_once './middleware/paramsSet/paramsSetPedido.php';
require_once './middleware/paramsSet/paramsSetPedidoProducto.php';

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

//RUTAS SOCIOS
$app->group('/socios', function (RouteCollectorProxy $group) 
{
    $group->get('/listar/socios', \socioController::class . ':TraerTodos');
    $group->get('/listar/trabajadores', \trabajadoresController::class . ':TraerTodos');
    $group->get('/listar/mesas', \MesasController::class . ':TraerTodos');
    $group->get('/listar/productos', \ProductosController::class . ':TraerTodos');
    $group->get('/listar/productos/{sector}', \ProductosController::class . ':TraerProductosPorSector');
    $group->get('/listar/pedidos', \PedidosController::class . ':TraerTodos');

    //CARGAR UN SOCIO (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/socio', \socioController::class . ':CargarUno')->add(new AuthSocioABM()) //chequea tipos
                                                                    ->add(new ParamsSetSocio()) //chequea si se pasaron los campos
                                                                    ->add(new ValidarSocio()); //chequea permisos

    //CARGAR UN TRABAJADOR (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/trabajador', \trabajadoresController::class . ':CargarUno') ->add(new AuthTrabajadorABM()) //chequea tipos
                                                                            ->add(new ParamsSetTrabajador()) //chequea si se pasaron los campos
                                                                            ->add(new ValidarSocio()); //chequea permisos

    //CARGAR UN PRODUCTO (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/producto', \ProductosController::class . ':CargarUno')->add(new AuthProductoABM()) //chequea tipos
                                                                        ->add(new ParamsSetProducto())
                                                                        ->add(new ValidarSocio()); //chequea permisos
    //CARGAR UNA MESA (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/mesa', \MesasController::class . ':CargarUno')->add(new AuthMesaABM()) //chequea tipos
                                                                    ->add(new ParamsSetMesa())  //chequea si se pasaron los campos
                                                                    ->add(new ValidarSocio()); //chequea permisos
});                     

//RUTAS MOZOS
$app->group('/mozo', function (RouteCollectorProxy $group) 
{
    $group->get('/listar/pedidos', \mozosController::class . ':TraerPorEstado');
    $group->get('/listar/pedidos/productos', \mozosController::class . ':TraerPorPedido');

    //CARGAR UN PEDIDO (REQUIERE PASSWORD MOZO)
    $group->post('/cargar/pedido', \PedidosController::class . ':CargarUno')->add(new AuthPedidoABM()) //chequea tipos
                                                                        ->add(new ParamsSetPedido()) //chequea si se pasaron los campos
                                                                        ->add(new ValidarTrabajador("mozo")); //chequea permisos
    //CARGAR UN PRODUCTO A UN PEDIDO (REQUIERE PASSWORD MOZO)
    $group->post('/cargar/pedidoProducto', \mozosController::class . ':cargarProductoEnPedido')->add(new AuthPedidoProductoABM()) //chequea tipos
                                                                                            ->add(new ParamsSetPedidoProducto()) //chequea si se pasaron los campos
                                                                                            ->add(new ValidarTrabajador("mozo"));
});

//RUTAS CERVECEROS
$app->group('/cerveceros', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \cervecerosController::class . ':TraerPendientes');
});

//RUTAS BARTENDERS
$app->group('/bartenders', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \bartendersController::class . ':TraerPendientes');
});

//RUTAS COCINEROS
$app->group('/cocineros', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \cocinerosController::class . ':TraerPendientes');
});

$app->run();