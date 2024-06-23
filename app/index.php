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
require_once './controllers/trabajadoresControllers/atenderPedidoController.php';
require_once './controllers/filesControllers/csvController.php';
require_once './controllers/login/loginController.php';

require_once './middleware/auth/authTrabajadorABM.php';
require_once './middleware/auth/authSocioABM.php';
require_once './middleware/auth/authProductoABM.php';
require_once './middleware/auth/authMesaABM.php';
require_once './middleware/auth/authPedidoABM.php';
require_once './middleware/auth/authPedidoProductoABM.php';
require_once './middleware/auth/authLogin.php';
require_once './middleware/auth/authDescargarCSV.php';
require_once './middleware/auth/authCargarCSV.php';



require_once './middleware/paramsSet/paramsSetTrabajador.php';
require_once './middleware/paramsSet/paramsSetMesa.php';
require_once './middleware/paramsSet/paramsSetSocio.php';
require_once './middleware/paramsSet/paramsSetProducto.php';
require_once './middleware/paramsSet/paramsSetPedido.php';
require_once './middleware/paramsSet/paramsSetPedidoProducto.php';
require_once './middleware/paramsSet/paramsSetLogin.php';
require_once './middleware/paramsSet/paramsSetCargarCSV.php';
require_once './middleware/paramsSet/paramsSetDescargarCSV.php';

require_once './middleware/users/validarJWT.php';

$app = AppFactory::create();

// Set base path
$app->setBasePath('/lacomanda/app');

//DEFAULT LOGIN
$app->post('/login', \LoginController::class . ':login')->add(new AuthLogin()) //chequea tipos
                                                        ->add(new ParamsSetLogin()); //chequea si se pasaron los campos 
//RUTAS SOCIOS
$app->group('/socios', function (RouteCollectorProxy $group) 
{
    $group->get('/listar/socios', \socioController::class . ':TraerTodos')->add(new validarJWT("socio"));
    $group->get('/listar/trabajadores', \trabajadoresController::class . ':TraerTodos')->add(new validarJWT("socio"));
    $group->get('/listar/mesas', \MesasController::class . ':TraerTodos')->add(new validarJWT("socio"));
    $group->get('/listar/productos', \ProductosController::class . ':TraerTodos')->add(new validarJWT("socio"));
    $group->get('/listar/productos/{sector}', \ProductosController::class . ':TraerProductosPorSector')->add(new validarJWT("socio"));
    $group->get('/listar/pedidos', \PedidosController::class . ':TraerTodos')->add(new validarJWT("socio"));

    //CARGAR UN SOCIO (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/socio', \socioController::class . ':CargarUno')->add(new AuthSocioABM()) //chequea tipos
                                                                    ->add(new ParamsSetSocio()) //chequea si se pasaron los campos
                                                                    ->add(new validarJWT("socio")); //chequea permisos

    //CARGAR UN TRABAJADOR (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/trabajador', \trabajadoresController::class . ':CargarUno') ->add(new AuthTrabajadorABM()) //chequea tipos
                                                                            ->add(new ParamsSetTrabajador()) //chequea si se pasaron los campos
                                                                            ->add(new validarJWT("socio")); //chequea permisos

    //CARGAR UN PRODUCTO (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/producto', \ProductosController::class . ':CargarUno')->add(new AuthProductoABM()) //chequea tipos
                                                                        ->add(new ParamsSetProducto())
                                                                        ->add(new validarJWT("socio")); //chequea permisos
    //CARGAR UNA MESA (REQUIERE PASSWORD SOCIOS)
    $group->post('/cargar/mesa', \MesasController::class . ':CargarUno')->add(new AuthMesaABM()) //chequea tipos
                                                                    ->add(new ParamsSetMesa())  //chequea si se pasaron los campos
                                                                    ->add(new validarJWT("socio")); //chequea permisos

    $group->post('/cargar/csv', \CsvController::class . ':guardarCSV')->add(new AuthCargarCSV()) //chequea tipos
                                                                    ->add(new ParamsSetCargarCSV())  //chequea si se pasaron los campos
                                                                    ->add(new validarJWT("socio")); //chequea permisos
    
    $group->post('/descargar/csv', \CsvController::class . ':descargarCSV')->add(new AuthDescargarCSV()) //chequea tipos
                                                                        ->add(new ParamsSetDescargarCSV())  //chequea si se pasaron los campos
                                                                        ->add(new validarJWT("socio")); //chequea permisos
});                     

//RUTAS MOZOS
$app->group('/mozo', function (RouteCollectorProxy $group) 
{
    $group->get('/listar/pedidos', \mozosController::class . ':TraerPorEstado')->add(new validarJWT("mozo"));
    $group->get('/listar/pedidos/productos', \mozosController::class . ':TraerPorPedido')->add(new validarJWT("mozo"));

    //CARGAR UN PEDIDO 
    $group->post('/cargar/pedido', \PedidosController::class . ':CargarUno')->add(new AuthPedidoABM()) //chequea tipos
                                                                        ->add(new ParamsSetPedido()) //chequea si se pasaron los campos
                                                                        ->add(new validarJWT("mozo")); //chequea permisos
    //CARGAR UN PRODUCTO A UN PEDIDO 
    $group->post('/cargar/pedidoProducto', \mozosController::class . ':cargarProductoEnPedido')->add(new AuthPedidoProductoABM()) //chequea tipos
                                                                                            ->add(new ParamsSetPedidoProducto()) //chequea si se pasaron los campos
                                                                                            ->add(new validarJWT("mozo"));
    //ENTREGAR UN PEDIDO 
    $group->post('/entregarPedido', \mozosController::class . ':MarcarPedidoEntregado')->add(new validarJWT("mozo"));
    //COBRAR UN PEDIDO 
    $group->post('/cobrarPedido', \mozosController::class . ':CobrarPedido')->add(new validarJWT("mozo"));
});

//RUTAS CERVECEROS
$app->group('/cerveceros', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \atenderPedidoController::class . ':TraerPendientes')->add(new validarJWT("cervecero"));
    
    //TOMAR PRODUCTO DE PEDIDO PARA COMPLETAR
    $group->post('/tomarProducto', \atenderPedidoController::class . ':TomarProductoPendiente')->add(new validarJWT("cervecero"));
    //MARCAR PRODUCTO COMO LISTO
    $group->post('/productoListo', \atenderPedidoController::class . ':MarcarProductoListo')->add(new validarJWT("cervecero"));

});

//RUTAS BARTENDERS
$app->group('/bartenders', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \atenderPedidoController::class . ':TraerPendientes')->add(new validarJWT("bartender"));
    //TOMAR PRODUCTO DE PEDIDO PARA COMPLETAR
    $group->post('/tomarProducto', \atenderPedidoController::class . ':TomarProductoPendiente')->add(new validarJWT("bartender"));
    //MARCAR PRODUCTO COMO LISTO
    $group->post('/productoListo', \atenderPedidoController::class . ':MarcarProductoListo')->add(new validarJWT("bartender"));
});

//RUTAS COCINEROS
$app->group('/cocineros', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \atenderPedidoController::class . ':TraerPendientes')->add(new validarJWT("cocinero"));
    //TOMAR PRODUCTO DE PEDIDO PARA COMPLETAR
    $group->post('/tomarProducto', \atenderPedidoController::class . ':TomarProductoPendiente')->add(new validarJWT("cocinero"));
    //MARCAR PRODUCTO COMO LISTO
    $group->post('/productoListo', \atenderPedidoController::class . ':MarcarProductoListo')->add(new validarJWT("cocinero"));
});

//RUTAS COCINEROS CANDYBAR
$app->group('/cocinerosCandybar', function (RouteCollectorProxy $group) 
{
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/listar/pendientes', \atenderPedidoController::class . ':TraerPendientes')->add(new validarJWT("cocineroCandybar"));
    //TOMAR PRODUCTO DE PEDIDO PARA COMPLETAR
    $group->post('/tomarProducto', \atenderPedidoController::class . ':TomarProductoPendiente')->add(new validarJWT("cocineroCandybar"));
    //MARCAR PRODUCTO COMO LISTO
    $group->post('/productoListo', \atenderPedidoController::class . ':MarcarProductoListo')->add(new validarJWT("cocineroCandybar"));
});

$app->run();