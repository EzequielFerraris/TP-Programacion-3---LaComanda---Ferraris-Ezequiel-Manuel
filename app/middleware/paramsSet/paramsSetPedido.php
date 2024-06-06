<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ParamsSetPedido
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        if(isset($parametros['codigo']) && isset($parametros['cliente']) 
            && isset($parametros['idMozo']) && isset($parametros['mesa']) && isset($parametros['estado'])
            && isset($parametros['monto']) && isset($parametros['fechaAlta']) && isset($parametros['tiempoEstimado'])
            && isset($parametros['tiempoFinal'])) 
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Uno o más de los parámetros no ha sido enviado.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>