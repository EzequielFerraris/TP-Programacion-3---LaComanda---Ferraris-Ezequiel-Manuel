<?php
include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthTrabajadorABM
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $puesto = $parametros['puesto'];
        $fechaIngreso = $parametros['fechaIngreso'];
        $clave = $parametros['clave'];
        $mail = $parametros['mail'];

        if(Validaciones::es_letras($apellido) && Validaciones::es_letras($nombre)
        && Validaciones::es_puesto_valido($puesto) && Validaciones::puede_ser_date($fechaIngreso)
        && Validaciones::pass_valido($clave) && Validaciones::es_mail_valido($mail)) 
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Uno o más de los parámetros es inválido.',
                                            'resultado' => false));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>