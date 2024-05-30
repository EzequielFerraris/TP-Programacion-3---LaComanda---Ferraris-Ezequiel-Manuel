<?php
require_once './models/trabajadores/Bartender.php';
require_once './interfaces/abm.php';


class bartenderController extends Bartender implements ABM
{
    
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $estado = "alta";
        if(isset($parametros['fechaIngreso']))
        {
            $fechaIngreso = $parametros['fechaIngreso'];
        }
        else
        {
            $fechaIngreso = (new DateTime())->format("Y-m-d");
        }

        // Creamos el cocinero
        $bartender = new Bartender();
        $bartender->apellido = $apellido;
        $bartender->nombre = $nombre;
        $bartender->fechaIngreso = $fechaIngreso;
        $bartender->estado = $estado;
        $bartender->crear();

        $payload = json_encode(array("mensaje" => "Bartender agregado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
 
	public function TraerTodos($request, $response, $args)
    {
        $lista = Bartender::obtenerTodos();
        $payload = json_encode(array("listaBartenders" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
  
	public function TraerUno($request, $response, $args)
    {
        
        $apellido = $args['apellido'];
        $nombre = $args['nombre'];
        $bartender = Bartender::buscar($apellido, $nombre);
        $payload = json_encode($bartender);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Bartender::buscarPorId($id);
        $trabajador->estado = "baja";
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Bartender dado de baja con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Bartender::buscarPorId($id);

        if(isset($request['apellido'])) {$trabajador->apellido = $request['apellido'];}
        if(isset($request['nombre'])) {$trabajador->nombre = $request['nombre'];}
        if(isset($request['estado'])) {$trabajador->estado = $request['estado'];}
        if(isset($request['fechaIngreso'])) {$trabajador->fechaIngreso = $request['fechaIngreso'];}
         
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Bartender modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
          
    }

    public function HardDeleteUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        Bartender::hardDelete($id);

        $payload = json_encode(array("mensaje" => "Bartender eliminado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
}

