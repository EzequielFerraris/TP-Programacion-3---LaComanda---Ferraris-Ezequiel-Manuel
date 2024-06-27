<?php

include_once 'middleware/validaciones.php';
include_once 'models/auditLog/auditLog.php';
include_once 'utils/Autentificador.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuditLogMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $response = $handler->handle($request);

        
        $paramsDevueltos = (string) $response->getBody();
        $paramsDevueltos = json_decode($paramsDevueltos);

        if($paramsDevueltos->resultado)
        {
            $nueva_entrada = new AuditLog();
            
            if(isset($paramsDevueltos->jwt))
            {
                $token = $paramsDevueltos->jwt;
            }
            else
            {
                $header = $request->getHeaderLine('Authorization');
                $token = trim(explode("Bearer", $header)[1]);
            }
            
            $params = AutentificadorJWT::ObtenerPayLoad($token);

            $nueva_entrada->id_usuario = $params->id;
            $nueva_entrada->mail = $params->mail;
            $nueva_entrada->puesto = $params->puesto;
            $nueva_entrada->accion = $paramsDevueltos->accion;
            $nueva_entrada->fecha = date("Y-m-d H:i:s");
            $nueva_entrada->crear();
        }
        
        $payload = json_encode(array('Mensaje' => $paramsDevueltos->Mensaje));
        $response = new Response();
        $response->getBody()->write($payload);

        return $response;
    }

}

?>