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

        $payload = json_encode(array("mensaje" => "Pedido agregado con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $codigo = $params['codigo'];
        
        $instancia = Pedido::buscar($codigo);
        $payload = json_encode($instancia);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerDemoraPedido($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $codigo = $params['codigo'];
        
        $instancia = Pedido::buscar($codigo);
        $payload = json_encode(array("Pedido " . $instancia->codigo => "Tiempo estimado demora: " . $instancia->tiempoEstimado));

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

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

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

        $payload = json_encode(array("mensaje" => "Pedido cancelado con éxito"));

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