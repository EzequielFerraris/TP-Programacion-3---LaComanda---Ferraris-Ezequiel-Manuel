<?php

include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthSocioABM
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $mail = $parametros['mail'];
        $clave = $parametros['clave'];

        if(Validaciones::es_letras($apellido) && Validaciones::es_letras($nombre)
        && Validaciones::es_mail_valido($mail) && Validaciones::pass_valido($clave)) 
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