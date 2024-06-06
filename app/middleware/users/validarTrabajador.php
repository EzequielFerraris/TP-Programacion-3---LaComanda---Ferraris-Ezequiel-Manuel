<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
include_once "middleware/validaciones.php";
class ValidarTrabajador
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $mail = $parametros["mail"];
        $password = $parametros["passTrabajador"];

        if(isset($mail) && isset($password))
        {
            $campoIncorrecto = "";

            if(Validaciones::es_mail_valido($mail) && Validaciones::pass_valido($password))
            {
                $trabajador = trabajadoresController::buscarPorMail($mail);
                
                if($trabajador  instanceof Trabajador)
                {
                    if($trabajador->login($password)) 
                    {
                        $response = $handler->handle($request);
                    } 
                    else 
                    {
                        $response = new Response();
                        $payload = json_encode(array('mensaje' => 'Mail o contraseña incorrecto.'));
                        $response->getBody()->write($payload);
                    }
                }
                else
                {
                    $response = new Response();
                    $payload = json_encode(array('mensaje' => 'Usuario inexistente.'));
                    $response->getBody()->write($payload);
                }
            }
            else
            {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Mail o contraseña son de tipo incorrecto.'));
                $response->getBody()->write($payload);
            }
            
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No se especificó mail o contraseña para el login.'));
            $response->getBody()->write($payload);
        }
        

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>