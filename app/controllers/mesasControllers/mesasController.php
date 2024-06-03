<?php

require_once './models/mesas/mesa.php';
require_once './interfaces/abm.php';

class MesasController extends Mesa implements ABM
{
    public function cargarUno($request, $response, $args)
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
    
	public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodas();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUno($request, $response, $args)
    {
        $codigo = $args['codigo'];
        
        $instancia = Mesa::buscar($codigo);
        $payload = json_encode($instancia);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function ModificarUno($request, $response, $args)
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

    public function DarBajaUno($request, $response, $args)
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

    public function HardDeleteUno($request, $response, $args)
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