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
        $estado = "Sin asignar";

        $relacion = new Pedido_productos();
        $relacion->id_pedido = $idPedido;
        $relacion->id_producto = $idProducto;
        $relacion->estado = $estado;
        $relacion->id_trabajador = null; 

        $resultadoQuery = $relacion->agregar();
        
        if($resultadoQuery)
        {
            $payload = json_encode(array("mensaje" => "Producto agregado con éxito"));

            $response->getBody()->write($payload);
            
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se pudo agregar el producto."));

            $response->getBody()->write($payload);
            
        }

        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorEstado($request, $response, $args)
    {
        $parametros = $request->getQueryParams();

        $lista = Pedido::filtrarMozoEstado($parametros["mozo"], $parametros["estado"]); 

        if(!$lista === true)
        {
            $payload = json_encode(array("listaProductos" => "No se registran pedidos con ese estado."));
        }
        else
        {
            $payload = json_encode(array("listaProductos" => $lista));
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
            $payload = json_encode(array("listaProductos" => "No se registran pedidos con ese estado."));
        }
        else
        {
            $payload = json_encode(array("listaProductos" => $lista));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}


?>
