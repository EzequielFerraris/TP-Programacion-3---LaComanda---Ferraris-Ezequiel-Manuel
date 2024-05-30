<?php
require_once './models/trabajadores/Cocinero.php';
require_once './interfaces/abm.php';

class cocineroController extends Cocinero implements ABM
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $estado = "alta";
        if(isset($parametros['fechaIngreso']))
        {
            $fecha = $parametros['fechaIngreso'];
        }
        else
        {
            $fecha = (new DateTime())->format("Y-m-d");
        }

        // Creamos el cocinero
        $cocinero = new Cocinero();
        $cocinero->apellido = $apellido;
        $cocinero->nombre = $nombre;
        $cocinero->fechaIngreso = $fecha;
        $cocinero->estado = $estado;
        $cocinero->crear();

        $payload = json_encode(array("mensaje" => "Cocinero agregado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerTodos($request, $response, $args)
    {
        $lista = Cocinero::obtenerTodos();
        $payload = json_encode(array("listaCocineros" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre y apellido
        $apellido = $args['apellido'];
        $nombre = $args['nombre'];
        $cocinero = Cocinero::buscar($apellido, $nombre);
        $payload = json_encode($cocinero);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Cocinero::buscarPorId($id);
        $trabajador->estado = "baja";
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Cocinero dado de baja con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Cocinero::buscarPorId($id);

        if(isset($request['apellido'])) {$trabajador->apellido = $request['apellido'];}
        if(isset($request['nombre'])) {$trabajador->nombre = $request['nombre'];}
        if(isset($request['estado'])) {$trabajador->estado = $request['estado'];}
        if(isset($request['fechaIngreso'])) {$trabajador->fechaIngreso = $request['fechaIngreso'];}
         
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Cocinero modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          
    }

    public function HardDeleteUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        Cocinero::hardDelete($id);

        $payload = json_encode(array("mensaje" => "Cocinero eliminado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}


