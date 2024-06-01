<?php

require_once './models/pedidos/pedido.php';
class PedidosController
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $cliente = $parametros['cliente'];
        $idMozo = $parametros['idMozo'];
        $mesa = $parametros['mesa'];
        $estado = $parametros['estado'];
        $monto = $parametros['monto'];
        $fechaAlta = $parametros['fechaAlta'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $tiempoFinal = $parametros['tiempoFinal'];

        // Creamos el pedido
        $instancia = new Pedido();  
        $instancia->codigo = $codigo;    
        $instancia->cliente = $cliente;
        $instancia->idMozo = $idMozo;
        $instancia->mesa = $mesa;
        $instancia->estado = $estado;
        $instancia->monto = $monto;
        $instancia->fechaAlta = $fechaAlta;
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
        $codigo = $args['codigo'];
        
        $instancia = Pedido::buscar($codigo);
        $payload = json_encode($instancia);

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
        if(isset($request['fechaAlta'])) {$instancia->fechaAlta = $request['fechaAlta'];}
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

    public function HardDeleteUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];

        Producto::hardDelete($codigo);

        $payload = json_encode(array("mensaje" => "Pedido eliminado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}

?>