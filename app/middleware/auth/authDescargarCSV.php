<?php

include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthDescargarCSV
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $objeto = $parametros['objeto'];
        
        if(Validaciones::es_objeto_valido($objeto))
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