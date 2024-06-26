<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ParamsSet
{
    public array $parametros;

    public function __construct(array $parametros)
    {
        $this->parametros = $parametros;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $todos = true;
        $metodo = $request->getMethod();
        if($metodo == "GET")
        {
            $pasados = $request->getQueryParams();
        }
        else 
        {
            $pasados = $request->getParsedBody();
        } 

        foreach($this->parametros as $p)
        {
            if(!isset($pasados[$p]))
            {
                $todos = false;
                break;
            }
        }

        if($todos) 
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