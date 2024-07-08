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

        if(!empty($paramsDevueltos->resultado) || isset($paramsDevueltos->resultado))
        {
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

                $payload = json_encode(array('Mensaje' => $paramsDevueltos->Mensaje));
                $response = new Response();
                $response->getBody()->write($payload);
            }

        }
        else
        {
            $tipoDescarga = $response->getHeader("Content-Type");

            if(!empty($tipoDescarga))
            {
                $header = $request->getHeaderLine('Authorization');
                $token = trim(explode("Bearer", $header)[1]);
                $params = AutentificadorJWT::ObtenerPayLoad($token);

                $nueva_entrada = new AuditLog();
                $nueva_entrada->id_usuario = $params->id;
                $nueva_entrada->mail = $params->mail;
                $nueva_entrada->puesto = $params->puesto;

                switch($tipoDescarga[0])
                {
                    case 'text/csv':
                        $nueva_entrada->accion = "Descarga CSV";
                    break;
                    case 'application/pdf':
                        $nueva_entrada->accion = "Descarga PDF";
                    break;
                    default:
                        $nueva_entrada->accion = "Descarga Archivo";
                    break;
                }
                $nueva_entrada->fecha = date("Y-m-d H:i:s");
                $nueva_entrada->crear();
            }
            else
            {
                $payload = json_encode(array('Mensaje' => 'Acción desconocida'));
                $response = new Response();
                $response->getBody()->write($payload);
            }
        }
        return $response;
    }

}

?>