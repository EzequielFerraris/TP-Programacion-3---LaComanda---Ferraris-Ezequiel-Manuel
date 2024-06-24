<?php
include_once "utils/Autentificador.php";
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class validarJWT
{
    public string $puesto;

    public function __construct($puesto)
    {
        $this->puesto = $puesto;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();

        try 
        {
            AutentificadorJWT::VerificarToken($token);
            $puestoToken = AutentificadorJWT::ObtenerPuesto($token);
            if($puestoToken == $this->puesto) 
            {
                $response = $handler->handle($request);
            }
            else if($this->puesto == "trabajador" && Validaciones::es_puesto_valido($puestoToken)
                    && $puestoToken  != "mozo")
            {
                $response = $handler->handle($request);
            }
            else
            {
                $payload = json_encode(array('mensaje' => "No cuenta con los permisos necesarios."));
                $response->getBody()->write($payload);
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array('mensaje' => $e->getMessage()));
            $response->getBody()->write($payload);
        }

        
        return $response->withHeader('Content-Type', 'application/json');
    }

}