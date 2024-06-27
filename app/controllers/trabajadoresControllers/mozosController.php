<?php

require_once './models/trabajadores/Trabajador.php';
require_once './models/productos/producto.php';
require_once './models/pedidos/pedido.php';
require_once './models/pedidos/pedido_productos.php';

class mozosController
{
    public function cargarProductoEnPedido($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $idPedido = $params["pedido"];
        $idProducto = $params["producto"];
        $nombre_producto = Producto::buscarPorId($params["producto"])->nombre;
        $estado = "Sin asignar";

        $relacion = new Pedido_productos();
        $relacion->id_pedido = $idPedido;
        $relacion->nombre_producto = $nombre_producto;
        $relacion->id_producto = $idProducto;
        $relacion->estado = $estado;
        $relacion->id_trabajador = null; 
        $relacion->tiempo_est_minutos = 0;
        $relacion->hora_tomado = null;
        $relacion->hora_completado = null;
        $relacion->tiempo_tardado = 0;

        $resultadoQuery = $relacion->agregar();
        
        if($resultadoQuery)
        {
            $payload = json_encode(array('Mensaje'=> 'Producto asignado a pedido correctamente.', 
                                        'resultado' => true,
                                        'accion'=>'Asignar producto a pedido'));

            $response->getBody()->write($payload);
            
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> 'No se pudo agregar el producto.', 
                                        'resultado' => true,
                                        'accion'=>'Asignar producto a pedido'));

            $response->getBody()->write($payload);
            
        }

        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorEstado($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $parametros = $request->getQueryParams();

        $lista = Pedido::filtrarMozoEstado(AutentificadorJWT::ObtenerID($token), $parametros["estado"]); 

        if(!$lista === true)
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran pedidos con ese estado.', 
                                        'resultado' => true,
                                        'accion'=>'Buscar pedidos por estado'));
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> $lista, 
                                        'resultado' => true,
                                        'accion'=>'Buscar pedidos por estado'));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorPedido($request, $response, $args)
    {
        $parametros = $request->getQueryParams();

        $lista = Pedido_productos::obtenerTodosPorPedido($parametros["pedido"]); 

        if(!$lista === true)
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran productos para ese pedido.', 
                                        'resultado' => true,
                                        'accion'=>'Buscar productos por pedido'));
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> $lista, 
                                        'resultado' => true,
                                        'accion'=>'Buscar productos por pedido'));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MarcarPedidoEntregado($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigo = $params["codigo"];

        //MARCAR COMO LISTO EL PEDIDO_PRODUCTO

        $pedido = Pedido::buscar($codigo);
        $pedido->estado = "Entregado";
        $pedido->entrega = date("Y-m-d H:i:s");

        //CALCULAR CUÁNTO SE TARDÓ EN ENTREGAR EL PEDIDO
        $diferencia = abs(strtotime($pedido->alta) - (new \DateTime)->getTimestamp()) / 60;
        $pedido->tiempoFinal = $diferencia;
        $pedido->update();

        $payload = json_encode(array('Mensaje'=> 'Pedido entregado', 
                                        'resultado' => true,
                                        'accion'=>'Entregar pedido'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CobrarPedido($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigo = $params["codigo"];

        $pedido = Pedido::buscar($codigo);
        
        //CALCULAR EL MONTO FINAL DEL PEDIDO
        $monto = Pedido_productos::obtenerMonto($pedido->codigo);
        $pedido->monto = (float)$monto;
        $pedido->update();

        $payload = json_encode(array('Mensaje'=> 'Monto a pagar: $' . $pedido->monto, 
                                        'resultado' => true,
                                        'accion'=>'Entregar pedido'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}


?>
