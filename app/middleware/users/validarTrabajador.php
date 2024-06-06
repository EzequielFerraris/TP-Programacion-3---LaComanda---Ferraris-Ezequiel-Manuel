<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
include_once "middleware/validaciones.php";
class ValidarTrabajador
{
    public  $puesto1;
    public  $puesto2;
    public  $puesto3;
    public  $puesto4;

    public function __construct($puesto1, $puesto2=null, $puesto3=null, $puesto4=null) {
        $this->puesto1 = $puesto1;
        $this->puesto2 = $puesto2;
        $this->puesto3 = $puesto3;
        $this->puesto4 = $puesto4;
    }
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        
        if(isset($parametros["mailTrabajador"]) && isset($parametros["passTrabajador"]))
        {
            $mail = $parametros["mailTrabajador"];
            $password = $parametros["passTrabajador"];
            if(Validaciones::es_mail_valido($mail) && Validaciones::pass_valido($password))
            {
                $trabajador = trabajadoresController::buscarPorMail($mail);
                
                if($trabajador  instanceof Trabajador)
                {
                    if($trabajador->puesto == $this->puesto1 || $trabajador->puesto == $this->puesto2
                        || $trabajador->puesto == $this->puesto3 || $trabajador->puesto == $this->puesto4)
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
                        $payload = json_encode(array('mensaje' => 'Su puesto no cuenta con los permisos para esta acción.'));
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