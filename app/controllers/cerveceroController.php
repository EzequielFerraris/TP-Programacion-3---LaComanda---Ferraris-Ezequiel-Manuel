<?php
require_once './models/Cervecero.php';
require_once './interfaces/abm.php';


class cerveceroController extends Cervecero implements ABM
{
    
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $estado = "alta";
        if(isset($parametros['fechaInicio']))
        {
            $fecha = $parametros['fecha'];
        }
        else
        {
            $fecha = new DateTime();
        }

        // Creamos el cocinero
        $cervecero = new Cervecero();
        $cervecero->apellido = $apellido;
        $cervecero->nombre = $nombre;
        $cervecero->fechaIngreso = $fecha;
        $cervecero->estado = $estado;
        $cervecero->crear();

        $payload = json_encode(array("mensaje" => "Cervecero agregado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function TraerTodos($request, $response, $args)
    {
        $lista = Cervecero::obtenerTodos();
        $payload = json_encode(array("listaCerveceros" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre y apellido
        $apellido = $args['apellido'];
        $nombre = $args['nombre'];
        $cervecero = Cervecero::buscar($apellido, $nombre);
        $payload = json_encode($cervecero);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Cervecero::buscarPorId($id);
        $trabajador->estado = "baja";
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Cervecero dado de baja con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Cervecero::buscarPorId($id);

        if(isset($request['apellido'])) {$trabajador->apellido = $request['apellido'];}
        if(isset($request['nombre'])) {$trabajador->nombre = $request['nombre'];}
        if(isset($request['estado'])) {$trabajador->estado = $request['estado'];}
        if(isset($request['fechaInicio'])) {$trabajador->fechaInicio = $request['fechaInicio'];}
         
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Cervecero modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          
    }

    public function HardDeleteUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        Cervecero::hardDelete($id);

        $payload = json_encode(array("mensaje" => "Cervecero eliminado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
   
}

