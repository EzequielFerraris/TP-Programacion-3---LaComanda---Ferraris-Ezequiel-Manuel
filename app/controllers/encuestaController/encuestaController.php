<?php

include_once "models/encuesta/encuesta.php";

class EncuestaController
{
    public function guardarEncuesta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $instancia = new Encuesta();  
        $instancia->nombre_cliente = $parametros['nombre_cliente'];
        $instancia->mesa = $parametros['mesa'];
        $instancia->pedido = $parametros['pedido'];
        $instancia->cocinero_rating = $parametros['cocinero_rating'];
        $instancia->restaurante_rating = $parametros['restaurante_rating'];
        $instancia->mozo_rating = $parametros['mozo_rating'];
        $instancia->mesa_rating = $parametros['mesa_rating'];
        $instancia->comentario = $parametros['comentario'];
        $instancia->fecha = date("Y-m-d H:i:s");

        $instancia->crear();

        $payload = json_encode(array('Mensaje'=> 'Encuesta agregada con éxito'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresValoraciones($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $categoria = $params['categoria'];
        
        $instancia = Encuesta::buscarDiezMejoresPorCategoria($categoria);

        if(empty($instancia)) 
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran opiniones.', 
                                        'resultado' => true,
                                        'accion'=>'Obtener valoraciones encuesta'));
        }
        else
        {
            
            $respuesta = array();
            
            foreach($instancia as $e)
            {
                $r = array();
                $r["categoria"] = $categoria;
                switch($categoria)
                {
                    case "cocinero":
                        $r["rating"] = $e->cocinero_rating; 
                    break;
                    case "restaurante":
                        $r["rating"] = $e->restaurante_rating; 
                    break;
                    case "mozo":
                        $r["rating"] = $e->mozo_rating; 
                    break;
                    case "mesa":
                        $r["rating"] = $e->mesa_rating; 
                    break;
                }
                $r["pedido"] = $e->pedido;
                $r["fecha"] = $e->fecha;
                $r["comentario"] = $e->comentario; 
                array_push($respuesta, $r);
            }
            $payload = json_encode(array('Mensaje'=> $respuesta, 
                                        'resultado' => true,
                                        'accion'=>'Obtener valoraciones encuesta'));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}

?>