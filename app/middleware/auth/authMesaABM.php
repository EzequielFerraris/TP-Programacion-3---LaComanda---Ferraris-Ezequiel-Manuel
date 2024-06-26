<?php

include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthMesaABM
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $puesto = AutentificadorJWT::ObtenerPuesto($token);

        $codigo = $parametros['codigo'];
        $estado = $parametros['estado'];

        if(Validaciones::es_alfanumerico($codigo) && Validaciones::es_mesa_estado($estado))
        {
            if($estado == "cerrada" && $puesto == "mozo")
            {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Los mozos no pueden cerrar mesas.'));
                $response->getBody()->write($payload);
            }
            else
            {
                $response = $handler->handle($request);
            }
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