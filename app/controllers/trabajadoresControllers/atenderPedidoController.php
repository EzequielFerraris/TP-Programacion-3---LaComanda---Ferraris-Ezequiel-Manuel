<?php

require_once './models/trabajadores/Trabajador.php';
require_once './models/productos/producto.php';
require_once './models/pedidos/pedido.php';
require_once './models/pedidos/pedido_productos.php';

class atenderPedidoController
{
    public function TraerPendientes($request, $response, $args)
    {
        $url = $request->getUri()->getPath();

        if(str_contains($url, "bartenders"))
        {
            $sector = "barra";
        }
        else if (str_contains($url, "cerveceros"))
        {
            $sector = "choperas";
        }
        else if (str_contains($url, "cocineros"))
        {
            $sector = "cocina";
        }
        else if (str_contains($url, "cocinerosCandybar"))
        {
            $sector = "candybar";
        }

        $lista = false;
        if(isset($sector)){$lista = Pedido_productos::obtenerPendientes($sector);} 

        if(!$lista === true)
        {
            $payload = json_encode(array("listaProductos" => "No se registran pendientes."));
        }
        else
        {
            $payload = json_encode(array("listaProductos" => $lista));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TomarProductoPendiente($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $trabajador = Trabajador::buscarPorMail($params["mailTrabajador"]);
        $id_pedido_producto = $params["id_pendiente"];
        $tiempo_est_minutos = $params["tiempoEstimado"];

        //MODIFICAR EL PEDIDO_PRODUCTO
        $pedido_producto = Pedido_productos::buscarPorId($id_pedido_producto);
        $pedido_producto->estado = "En preparación";
        $pedido_producto->id_trabajador = $trabajador->id;
        $pedido_producto->tiempo_est_minutos = $tiempo_est_minutos;

        $tiempos = Pedido_productos::obtenerTiemposPorTrabajador();
        $tiempoEstimado = $tiempos[0]["tiempo"];
        $pedido = Pedido::buscar($pedido_producto->id_pedido);
        $pedido->tiempo_est_minutos = (int)($tiempoEstimado);
        $pedido->update();

        $pedido_producto->update();

        $payload = json_encode(array("listaProductos" => $pedido));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function MarcarProductoListo($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $id_pedido_producto = $params["id_pendiente"];

        //MARCAR COMO LISTO EL PEDIDO_PRODUCTO

        $pedido_producto = Pedido_productos::buscarPorId($id_pedido_producto);
        $pedido_producto->estado = "Listo";
        $pedido_producto->update();

        //CHECKEA SI TODOS LOS PRODUCTOS DEL PEDIDO ESTÁN LISTOS
        $estadosProductos = Pedido_productos::checkEstadosProductos($pedido_producto->id_pedido);
        if($estadosProductos)
        {
            $producto = Pedido::buscar($pedido_producto->id_pedido);
            $producto->estado = "Listo";
            $producto->update();
        }

        $payload = json_encode(array("listaProductos" => "Producto marcado como listo."));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


}

?>

