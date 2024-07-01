<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

date_default_timezone_set("America/Argentina/Buenos_Aires");

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS

//CONTROLLERS
require_once './controllers/trabajadoresControllers/trabajadoresController.php';
require_once './controllers/productosControllers/productosController.php';
require_once './controllers/mesasControllers/mesasController.php';
require_once './controllers/sociosControllers/socioController.php';
require_once './controllers/pedidosControllers/pedidosController.php';
require_once './controllers/trabajadoresControllers/mozosController.php';
require_once './controllers/trabajadoresControllers/atenderPedidoController.php';
require_once './controllers/filesControllers/csvController.php';
require_once './controllers/login/loginController.php';
require_once './controllers/filesControllers/imagesController.php';
require_once './controllers/encuestaController/encuestaController.php';
require_once './controllers/filesControllers/pdfController.php';
require_once './controllers/trabajadoresControllers/pedidos_productosController.php';
require_once './controllers/auditControllers/auditController.php';


//MIDDLEWARE CHECK TODOS LOS PARAMETROS INCLUIDOS 
require_once './middleware/paramsSet/paramsSetCargarCSV.php';
require_once './middleware/paramsSet/paramsSetDescargarCSV.php';
require_once './middleware/paramsSet/paramsSetCargarImagen.php';
require_once './middleware/paramsSet/paramsSet.php';

//MIDDLEWARE CHECK PARAMETROS VALIDOS
require_once './middleware/auth/authTrabajadorABM.php';
require_once './middleware/auth/authSocioABM.php';
require_once './middleware/auth/authProductoABM.php';
require_once './middleware/auth/authMesaABM.php';
require_once './middleware/auth/authPedidoABM.php';
require_once './middleware/auth/authPedidoProductoABM.php';
require_once './middleware/auth/authLogin.php';
require_once './middleware/auth/authDescargarCSV.php';
require_once './middleware/auth/authCargarCSV.php';
require_once './middleware/auth/authCargarImagen.php';
require_once './middleware/auth/authEncuesta.php';
require_once './middleware/auth/authCategoriaEncuesta.php';

require_once './middleware/auditLog/auditLogMiddleware.php';

//MIDDLEWARE CHECK PERMISOS CON TOKEN JWT
require_once './middleware/users/validarJWT.php';

$app = AppFactory::create();

// Set base path
$app->setBasePath('/lacomanda/app');

//DEFAULT LOGIN
$app->post('/login', \LoginController::class . ':login')->add(new AuthLogin()) //chequea tipos
                                                        ->add(new ParamsSet(["mail", "password", "puesto"])) //chequea si se pasaron los campos 
                                                        ->add(new AuditLogMiddleware());
//RUTAS SOCIOS
$app->group('/socios', function (RouteCollectorProxy $group) 
{
    //PETICIONES GET
    $group->get('/listar/socios', \socioController::class . ':TraerTodos');
    $group->get('/listar/trabajadores', \trabajadoresController::class . ':TraerTodos');
    $group->get('/listar/mesas', \MesasController::class . ':TraerTodos');
    $group->get('/listar/productos', \ProductosController::class . ':TraerTodos');
    $group->get('/listar/productos/{sector}', \ProductosController::class . ':TraerProductosPorSector');
    $group->get('/listar/pedidos', \PedidosController::class . ':TraerTodos');
    $group->get('/listar/demoraPedido', \PedidosController::class . ':TraerDemoraPedido')->add(new ParamsSet(["codigo"]));
    $group->get('/listar/mejoresComentarios', \EncuestaController::class . ':TraerMejoresValoraciones')->add(new AuthCategoriaEncuesta())
                                                                                                        ->add(new ParamsSet(["categoria"]));
    $group->get('/listar/mesaMasUsada', \MesasController::class . ':mesaMasUsada');
    $group->get('/listar/pedidosFueraDeTiempo', \PedidosController::class . ':fueraDeTiempo');
    $group->get('/listar/productosFueraDeTiempo', \pedidos_productosController::class . ':fueraDeTiempo');
    $group->get('/descargar/pdf', \PdfController::class . ':descargarPDF');
    $group->get('/listar/accionesPorSector', \AuditController::class . ':operacionesPorSector');
    $group->get('/listar/trabajadorSector', \AuditController::class . ':operacionesPorTrabajadorSector');
    $group->get('/listar/masAmenosVendido', \pedidos_productosController::class . ':MasVendidoAMenos');
    $group->get('/listar/loginsTrabajador', \AuditController::class . ':ingresosPorDiaTrabajador')->add(new ParamsSet(["mail"]));
    $group->get('/listar/mesasPorFactura', \pedidosController::class . ':listarMesasPorFactura');
    $group->get('/listar/ingresoMesaEntreFechas', \pedidos_productosController::class . ':mesaEntreFechas')->add(new ParamsSet(["fecha1", "fecha2"]));

    //PETICIOS POST
    //CARGAR UN SOCIO 
    $group->post('/cargar/socio', \socioController::class . ':CargarUno')->add(new AuthSocioABM()) 
                                                                        ->add(new ParamsSet(["apellido", "nombre", "clave", "mail"])); 
    //CARGAR UN TRABAJADOR 
    $group->post('/cargar/trabajador', \trabajadoresController::class . ':CargarUno')->add(new AuthTrabajadorABM()) 
                                                                                ->add(new ParamsSet(["apellido", "nombre", "puesto", "fechaIngreso", "clave"])); 
    //CARGAR UN PRODUCTO 
    $group->post('/cargar/producto', \ProductosController::class . ':CargarUno')->add(new AuthProductoABM()) 
                                                                                ->add(new ParamsSet(["nombre", "precio", "sector", "hayStock"])); 
    //CARGAR UNA MESA 
    $group->post('/cargar/mesa', \MesasController::class . ':CargarUno')->add(new AuthMesaABM()) 
                                                                    ->add(new ParamsSet(["codigo", "estado"]));  
    //CAMBIAR ESTADO MESA
    $group->post('/cerrarMesa', \MesasController::class . ':cambiarEstadoMesa')->add(new AuthMesaABM()) 
                                                                            ->add(new ParamsSet(["codigo", "estado"]));  
    //CARGAR CSV
    $group->post('/cargar/csv', \CsvController::class . ':guardarCSV')->add(new AuthCargarCSV()) 
                                                                    ->add(new ParamsSetCargarCSV());  
    //DESCARGAR CSV 
    $group->post('/descargar/csv', \CsvController::class . ':descargarCSV')->add(new AuthDescargarCSV()) 
                                                                        ->add(new ParamsSetDescargarCSV());                                              
})->add(new AuditLogMiddleware())
->add(new validarJWT("socio"));                     

//RUTAS MOZOS
$app->group('/mozo', function (RouteCollectorProxy $group) 
{
    //PETICIONES GET
    //LISTAR PEDIDOS POR SU ESTADO
    $group->get('/listar/pedidos', \mozosController::class . ':TraerPorEstado')->add(new ParamsSet(["estado"]));
    //LISTAR PRODUCTOS POR PEDIDO
    $group->get('/listar/pedidos/productos', \mozosController::class . ':TraerPorPedido')->add(new ParamsSet(["pedido"]));

    //PETICIONES POST
    //CAMBIAR ESTADO MESA
    $group->post('/estadoMesa', \MesasController::class . ':cambiarEstadoMesa')->add(new AuthMesaABM()) 
                                                                            ->add(new ParamsSet(["codigo", "estado"]));                                                                   
    //CARGAR UN PEDIDO 
    $group->post('/cargar/pedido', \PedidosController::class . ':CargarUno')->add(new AuthPedidoABM()) 
                                                                            ->add(new ParamsSet(["codigo", "cliente", "mesa"]));                                                      
    //CARGAR UN PRODUCTO A UN PEDIDO 
    $group->post('/cargar/pedidoProducto', \mozosController::class . ':cargarProductoEnPedido')->add(new AuthPedidoProductoABM()) 
                                                                                            ->add(new ParamsSet(["pedido", "producto"]));                                                                
    //ASOCIAR IMAGEN A PEDIDO Y GUARDARLA
    $group->post('/cargar/imagen', \ImagesController::class . ':guardarImagen')->add(new AuthCargarImagen())
                                                                            ->add(new ParamsSetCargarImagen());                                                                       
    //CARGAR UN PRODUCTO A UN PEDIDO                                                                     
    $group->post('/entregarPedido', \mozosController::class . ':MarcarPedidoEntregado')->add(new ParamsSet(["codigo"]));
    //COBRAR UN PEDIDO 
    $group->post('/cobrarPedido', \mozosController::class . ':CobrarPedido')->add(new ParamsSet(["codigo"]));
})->add(new AuditLogMiddleware())
->add(new validarJWT("mozo"));

//RUTAS COCINEROS, BARTENDERS, CERVECEROS, CANDYBAR
$app->group('/gestionPedido', function (RouteCollectorProxy $group) 
{
    //PETICIONES GET
    //LISTA PRODUCTOS DE PEDIDOS PENDIENTES DEL AREA
    $group->get('/pendientes', \atenderPedidoController::class . ':TraerPendientes');
    $group->get('/enPreparacion', \atenderPedidoController::class . ':TraerEnPreparacion');
    //PETICIONES POST
    //TOMAR PRODUCTO DE PEDIDO PARA COMPLETAR
    $group->post('/tomarProducto', \atenderPedidoController::class . ':TomarProductoPendiente')->add(new ParamsSet(["id_pendiente", "tiempoEstimado"])); 
    //MARCAR PRODUCTO COMO LISTO
    $group->post('/marcarListo', \atenderPedidoController::class . ':MarcarProductoListo')->add(new ParamsSet(["id_pendiente"])); 
    
})->add(new AuditLogMiddleware())
->add(new validarJWT("trabajador"));

//RUTAS CLIENTES
$app->group('/clientes', function (RouteCollectorProxy $group) 
{
    //PETICIONES GET
    $group->get('/demoraPedido', \PedidosController::class . ':TraerDemoraPedido')->add(new ParamsSet(["codigo"]));

    //PETICIONES POST
    $group->post('/cargarEncuesta', \EncuestaController::class . ':guardarEncuesta')->add(new AuthEncuesta())
                ->add(new ParamsSet(["nombre_cliente", "mesa", "pedido", "cocinero_rating", "restaurante_rating", "mozo_rating", "mesa_rating", "comentario"]));
});

$app->run();