<?php

require_once './models/pedidos/pedido.php';
require_once './interfaces/abm.php';
class PedidosController extends Pedido implements ABM
{
    public function CargarUno($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $trabajador = Trabajador::buscarPorId(AutentificadorJWT::ObtenerID($token));

        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $cliente = $parametros['cliente'];
        $idMozo = $trabajador->id;
        $mesa = $parametros['mesa'];
        $estado = "En preparación";
        $monto = 0;
        $alta = date("Y-m-d H:i:s");
        $entrega = "00-00-00 00:00:00";
        $tiempoEstimado = 0;
        $tiempoFinal = 0;

        // Creamos el pedido
        $instancia = new Pedido();  

        $instancia->codigo = $codigo;    
        $instancia->cliente = $cliente;
        $instancia->idMozo = $idMozo;
        $instancia->mesa = $mesa;
        $instancia->estado = $estado;
        $instancia->monto = $monto;
        $instancia->alta = $alta;
        $instancia->entrega = $entrega;
        $instancia->tiempoEstimado = $tiempoEstimado;
        $instancia->tiempoFinal = $tiempoFinal;
        
        $instancia->crear();

        $payload = json_encode(array('Mensaje'=> "Pedido agregado con éxito", 
                                    'resultado' => true,
                                    'accion'=>'Crear pedido'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();

        $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar todos los pedidos'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $codigo = $params['codigo'];
        
        $instancia = Pedido::buscar($codigo);
        $payload = json_encode(array('Mensaje'=> json_encode($instancia), 
                                    'resultado' => true,
                                    'accion'=>'Buscar un pedido'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerDemoraPedido($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $codigo = $params['codigo'];
        
        $instancia = Pedido::buscar($codigo);

        if(empty($instancia->tiempoEstimado)) {$instancia->tiempoEstimado = "En proceso de estimar tiempo de entrega.";}

        $payload = json_encode(array('Mensaje'=> array("Pedido" => $instancia->codigo, 
                                                    "Mesa" => $instancia->mesa,
                                                    "Tiempo estimado demora" => $instancia->tiempoEstimado), 
                                    'resultado' => true,
                                    'accion'=>'Consultar demora pedido'));


        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        $instancia = Pedido::buscar($codigo);

        if(isset($request['cliente'])) {$instancia->cliente = $request['cliente'];}
        if(isset($request['idMozo'])) {$instancia->idMozo = $request['idMozo'];}
        if(isset($request['mesa'])) {$instancia->mesa = $request['mesa'];}
        if(isset($request['estado'])) {$instancia->estado = $request['estado'];}
        if(isset($request['monto'])) {$instancia->monto = $request['monto'];}
        if(isset($request['alta'])) {$instancia->alta = $request['alta'];}
        if(isset($request['entrega'])) {$instancia->alta = $request['entrega'];}
        if(isset($request['tiempoEstimado'])) {$instancia->tiempoEstimado = $request['tiempoEstimado'];}
        if(isset($request['tiempoFinal'])) {$instancia->tiempoFinal = $request['tiempoFinal'];}
        
         
        $instancia->update();


        $payload = json_encode(array('Mensaje'=> "Pedido modificado con exito", 
                                    'resultado' => true,
                                    'accion'=>'Modificar pedido'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        $Pedido = Pedido::buscar($codigo);
        $Pedido->estado = "cancelado";
        $Pedido->update();

        $payload = json_encode(array('Mensaje'=> "Pedido cancelado con éxito", 
                                    'resultado' => true,
                                    'accion'=>'Cancelar pedido'));
                                    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function CheckPedido($codigo) : bool
    {
        $resultado = false;
        try
        {
            $pedido = Pedido::buscar($codigo);
        
            if($pedido instanceof Pedido)
            {
                $resultado = true;
            }
        }
        catch(Exception $e){}

        return $resultado;
    }
}

?>