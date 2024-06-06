<?php

include_once 'middleware/validaciones.php';
include_once "./controllers/trabajadoresControllers/trabajadoresController.php";
include_once "./controllers/mesasControllers/mesasController.php";

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthPedidoABM
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $cliente = $parametros['cliente'];
        $idMozo = $parametros['idMozo'];
        $mesa = $parametros['mesa'];
        $estado = $parametros['estado'];
        $monto = $parametros['monto'];
        $fechaAlta = $parametros['fechaAlta'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $tiempoFinal = $parametros['tiempoFinal'];

        $valido = false;
        $parametroInvalido = "";

        if(Validaciones::es_alfanumerico($codigo) && Validaciones::tiene_longitud_x($codigo, 5)) 
        {
            if(Validaciones::es_letras($cliente))
            {
                if(Validaciones::es_int($idMozo) && 
                    trabajadoresController::ChequearUnoPorID($idMozo, "mozo"))
                {
                    if(Validaciones::es_alfanumerico($mesa) && Validaciones::tiene_longitud_x($mesa, 5) 
                        && mesasController::CheckMesa($mesa))
                    {
                        if(Validaciones::es_letras($estado))
                        {
                            if(Validaciones::es_float_positivo($monto))
                            {
                                if(Validaciones::puede_ser_date($fechaAlta))
                                {
                                    if(Validaciones::int_positivo($tiempoEstimado))
                                    {
                                        if(Validaciones::es_int($tiempoFinal))
                                        {
                                            $valido = true;
                                            $response = $handler->handle($request);
                                        }
                                        else
                                        {
                                            $parametroInvalido = "tiempoFinal";
                                        }
                                    }
                                    else
                                    {
                                        $parametroInvalido = "tiempoEstimado";
                                    }
                                }
                                else
                                {
                                    $parametroInvalido = "fechaAlta";
                                }
                            }
                            else
                            {
                                $parametroInvalido = "monto";
                            }
                        }
                        else
                        {
                            $parametroInvalido = "estado";
                        }
                    }
                    else
                    {
                        $parametroInvalido = "mesa";
                    }
                }
                else
                {
                    $parametroInvalido = "idMozo";
                }
            }
            else
            {
                $parametroInvalido = "cliente";
            }
        } 
        else
        {
            $parametroInvalido = "código";
        }
        
        if(!$valido)
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => "El parámentro " . $parametroInvalido . " es inválido."));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>