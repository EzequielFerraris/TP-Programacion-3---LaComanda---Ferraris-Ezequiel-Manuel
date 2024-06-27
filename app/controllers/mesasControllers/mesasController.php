<?php

require_once './models/mesas/mesa.php';
require_once './interfaces/abm.php';

class MesasController extends Mesa implements ABM
{

    public static function CheckMesa($codigo) : bool
    {
        $resultado = false;
        try
        {
            $mesa = Mesa::buscar($codigo);
        
            if($mesa instanceof Mesa)
            {
                $resultado = true;
            }
        }
        catch(Exception $e){}

        return $resultado;
    }
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

        $payload = json_encode(array('Mensaje'=> "Mesa agregada con éxito", 
                                        'resultado' => true,
                                        'accion'=>'Agregar Mesa'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function cambiarEstadoMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $estado = $parametros['estado'];
        
        // Creamos la mesa
        $instancia = Mesa::buscar($codigo);      
        $instancia->estado = $estado;
        $instancia->update();

        $payload = json_encode(array('Mensaje'=> "Mesa actualizada con éxito.", 
                                        'resultado' => true,
                                        'accion'=>'Cambiar estado Mesa'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
	public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodas();

        $payload = json_encode(array('Mensaje'=> $lista, 
                                        'resultado' => true,
                                        'accion'=>'Listar mesas'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerUno($request, $response, $args) : Mesa
    {
        $codigo = $args['codigo'];
        
        $instancia = Mesa::buscar($codigo);

        $payload = json_encode(array('Mensaje'=> json_encode($instancia), 
                                    'resultado' => true,
                                    'accion'=>'Traer una mesa'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function mesaMasUsada($request, $response, $args) 
    {
        $mesa = Mesa::mesaMasPedidos();
        
        $payload = json_encode(array('Mensaje'=> json_encode($mesa), 
                                    'resultado' => true,
                                    'accion'=>'Consulta mesa más usada'));


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

        $payload = json_encode(array('Mensaje'=> "Mesa modificada con exito", 
                                    'resultado' => true,
                                    'accion'=>'Modificar mesa'));
  
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPorID($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $instancia = Mesa::buscarPorId($id);

        if(isset($request['codigo'])) {$instancia->estado = $request['codigo'];}
        if(isset($request['estado'])) {$instancia->estado = $request['estado'];}
                 
        $instancia->update();

        $payload = json_encode(array('Mensaje'=> "Mesa modificada con exito", 
                                    'resultado' => true,
                                    'accion'=>'Modificar mesa'));

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

        $payload = json_encode(array('Mensaje'=> "Mesa dada de baja con éxito", 
                                    'resultado' => true,
                                    'accion'=>'Dar de baja mesa'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}

?>