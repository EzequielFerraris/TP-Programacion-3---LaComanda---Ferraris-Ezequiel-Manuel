<?php

include_once "controllers/mesasControllers/mesasController.php";
include_once "controllers/pedidosControllers/pedidosController.php";
include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class AuthEncuesta
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $nombre_cliente = $parametros['nombre_cliente'];
        $mesa = $parametros['mesa'];
        $pedido = $parametros['pedido'];
        $cocinero_rating = $parametros['cocinero_rating'];
        $restaurante_rating = $parametros['restaurante_rating'];
        $mozo_rating = $parametros['mozo_rating'];
        $mesa_rating = $parametros['mesa_rating'];
        $comentario = $parametros['comentario'];
        
        if(Validaciones::es_letras($nombre_cliente) && MesasController::CheckMesa($mesa)
            && PedidosController::CheckPedido($pedido) && Validaciones::es_rating_valido($cocinero_rating)
            && Validaciones::es_rating_valido($restaurante_rating) && Validaciones::es_rating_valido($mozo_rating)
            && Validaciones::es_rating_valido($mesa_rating) && Validaciones::es_string($comentario))
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Uno o más de los parámetros es inválido.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


}

?>