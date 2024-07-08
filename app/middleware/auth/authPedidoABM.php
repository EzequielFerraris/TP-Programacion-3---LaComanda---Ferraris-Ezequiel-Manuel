<?php

include_once 'middleware/validaciones.php';
include_once "./controllers/trabajadoresControllers/trabajadoresController.php";
include_once "./controllers/mesasControllers/mesasController.php";

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthPedidoABM
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $cliente = $parametros['cliente'];
        $mesa = $parametros['mesa'];
        
        $valido = false;
        $parametroInvalido = "";

        if(Validaciones::es_alfanumerico($codigo) && Validaciones::tiene_longitud_x($codigo, 5)) 
        {
            if(Validaciones::es_letras($cliente))
            {
                if(Validaciones::es_alfanumerico($mesa) && Validaciones::tiene_longitud_x($mesa, 5) 
                && mesasController::CheckMesa($mesa))
                {
                    $valido = true;
                    $response = $handler->handle($request); 
                }
                else
                {
                    $parametroInvalido = "mesa";
                }   
            }
            else
            {
                $parametroInvalido = "cliente";
            }
        } 
        else
        {
            $parametroInvalido = "código";
        }
        
        if(!$valido)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => "El parámentro " . $parametroInvalido . " es inválido.",
                                            'resultado' => false));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>