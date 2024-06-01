<?php

require_once './models/mesas/mesa.php';

class MesasController extends Mesa
{
    public function CargarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $estado = $parametros['estado'];
        
        // Creamos la mesa
        $instancia = new Mesa();      
        $instancia->codigo = $codigo;
        $instancia->estado = $estado;
        $instancia->crear();

        $payload = json_encode(array("mensaje" => "Mesa agregada con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function TraerTodas($request, $response, $args)
    {
        $lista = Mesa::obtenerTodas();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUna($request, $response, $args)
    {
        $codigo = $args['codigo'];
        
        $instancia = Mesa::buscar($codigo);
        $payload = json_encode($instancia);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function ModificarUna($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        $instancia = Mesa::buscar($codigo);

        if(isset($request['estado'])) {$instancia->estado = $request['estado'];}
                 
        $instancia->update();

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DarBajaUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        $instancia = Mesa::buscar($codigo);

        $instancia->estado = "baja";
        $instancia->update();

        $payload = json_encode(array("mensaje" => "Mesa dada de baja con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function HardDeleteUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        Mesa::hardDelete($codigo);

        $payload = json_encode(array("mensaje" => "Mesa eliminada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}

?>