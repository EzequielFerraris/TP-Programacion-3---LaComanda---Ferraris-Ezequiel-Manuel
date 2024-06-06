<?php
require_once './models/trabajadores/Trabajador.php';
require_once './interfaces/abm.php';


class trabajadoresController extends Trabajador implements ABM
{
    
    public static function ChequearUnoPorID($id, $puesto) : bool
    {
        $resultado = false;
        try
        {
            $trabajador = Trabajador::buscarPorId($id);
        
            if($trabajador instanceof Trabajador && $trabajador->puesto == $puesto)
            {
                $resultado = true;
            }
        }
        catch(Exception $e){}
        return $resultado;
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $puesto = $parametros['puesto'];
        $estado = "alta";
        $clave = $parametros['clave'];
        $fechaIngreso = $parametros['fechaIngreso'];
        $mail = $parametros['mail'];

        // Creamos el trabajador
        $trabajador = new Trabajador();
        $trabajador->apellido = $apellido;
        $trabajador->nombre = $nombre;
        $trabajador->fechaIngreso = $fechaIngreso;
        $trabajador->estado = $estado;
        $trabajador->puesto = $puesto;
        $trabajador->mail = $mail;
        $trabajador->setPassword($clave);
        $trabajador->encriptar();
        $trabajador->crear();

        $payload = json_encode(array("mensaje" => "Trabajador agregado con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
 
	public function TraerTodos($request, $response, $args)
    {
        $lista = Trabajador::obtenerTodos();
        $payload = json_encode(array("listaTrabajadores" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
  
	public function TraerUno($request, $response, $args)
    {
        
        $apellido = $args['apellido'];
        $nombre = $args['nombre'];
        $trabajador = Trabajador::buscar($apellido, $nombre);
        $payload = json_encode($trabajador);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
	public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Trabajador::buscarPorId($id);
        $trabajador->estado = "baja";
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Trabajador dado de baja con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Trabajador::buscarPorId($id);

        if(isset($request['apellido'])) {$trabajador->apellido = $request['apellido'];}
        if(isset($request['nombre'])) {$trabajador->nombre = $request['nombre'];}
        if(isset($request['estado'])) {$trabajador->estado = $request['estado'];}
        if(isset($request['puesto'])) {$trabajador->puesto = $request['puesto'];}
        if(isset($request['fechaIngreso'])) {$trabajador->fechaIngreso = $request['fechaIngreso'];}
         
        $trabajador->update();

        $payload = json_encode(array("mensaje" => "Trabajador modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public static function buscarPorMail($mail) : mixed
    {
        return Trabajador::buscarPorMail($mail);
    }
}

