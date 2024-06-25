<?php

require_once './models/trabajadores/Trabajador.php';
require_once './models/productos/producto.php';
require_once './models/pedidos/pedido.php';
require_once './models/pedidos/pedido_productos.php';

class atenderPedidoController
{
    public function TraerPendientes($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $puesto = AutentificadorJWT::ObtenerPuesto($token);

        switch($puesto)
        {
            case "bartender":
                $sector = "barra";
            break;
            case "cervecero":
                $sector = "choperas";
            break;
            case "cocinero":
                $sector = "cocina";
            break;
            case "cocineroCandybar":
                $sector = "candybar";
            break;
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
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $trabajador = Trabajador::buscarPorId(AutentificadorJWT::ObtenerID($token));

        $id_pedido_producto = $params["id_pendiente"];
        $tiempo_est_minutos = $params["tiempoEstimado"];

        //MODIFICAR EL PEDIDO_PRODUCTO A "EN PREPARACIÓN"
        $pedido_producto = Pedido_productos::buscarPorId($id_pedido_producto);
        $pedido_producto->estado = "En preparación";
        $pedido_producto->id_trabajador = $trabajador->id;
        $pedido_producto->tiempo_est_minutos = $tiempo_est_minutos;
        $pedido_producto->hora_tomado = date("Y-m-d H:i:s");

        //MODIFICAR EL TIEMPO ESTIMADO DE TODO EL PEDIDO EN BASE AL NUEVO ESTIMADO
        $tiempos = Pedido_productos::obtenerTiemposPorTrabajador($pedido_producto->id_pedido);
        $tiempoEstimado = $tiempos[0]["tiempo"];

        $pedido = Pedido::buscar($pedido_producto->id_pedido);
        $pedido->tiempoEstimado = (int)($tiempoEstimado);
        $pedido->update();

        $pedido_producto->update();

        //RESPONSE
        $payload = json_encode(array("RESULTADO:" => "Producto seleccionado asignado. Tiempo estimado actualizado."));
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
        $pedido_producto->hora_completado = date("Y-m-d H:i:s");
        $diferencia = abs(strtotime($pedido_producto->hora_tomado) - (new \DateTime)->getTimestamp()) / 60;
        $pedido_producto->tiempo_tardado = $diferencia;
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

