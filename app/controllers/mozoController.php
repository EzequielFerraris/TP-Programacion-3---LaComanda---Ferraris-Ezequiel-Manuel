<?php
require_once './models/Mozo.php';
require_once './interfaces/abm.php';


class mozoController extends Mozo implements ABM
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
        $mozo = new Mozo();
        $mozo->apellido = $apellido;
        $mozo->nombre = $nombre;
        $mozo->fechaIngreso = $fecha;
        $mozo->estado = $estado;
        $mozo->crear();

        $payload = json_encode(array("mensaje" => "Mozo agregado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function TraerTodos($request, $response, $args)
    {
        $lista = Mozo::obtenerTodos();
        $payload = json_encode(array("listaMozos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre y apellido
        $apellido = $args['apellido'];
        $nombre = $args['nombre'];
        $mozo = Mozo::buscar($apellido, $nombre);
        $payload = json_encode($mozo);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Mozo::buscarPorId($id);
        $trabajador->estado = "baja";
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Mozo dado de baja con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Mozo::buscarPorId($id);

        if(isset($request['apellido'])) {$trabajador->apellido = $request['apellido'];}
        if(isset($request['nombre'])) {$trabajador->nombre = $request['nombre'];}
        if(isset($request['estado'])) {$trabajador->estado = $request['estado'];}
        if(isset($request['fechaInicio'])) {$trabajador->fechaInicio = $request['fechaInicio'];}
         
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Mozo modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          
    }

    public function HardDeleteUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        Mozo::hardDelete($id);

        $payload = json_encode(array("mensaje" => "Mozo eliminado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}

