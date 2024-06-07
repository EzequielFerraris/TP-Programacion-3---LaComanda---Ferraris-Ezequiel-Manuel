<?php

require_once './models/trabajadores/Trabajador.php';
require_once './models/productos/producto.php';
require_once './models/pedidos/pedido.php';
require_once './models/pedidos/pedido_productos.php';

class cocinerosController
{
    public function TraerPendientes($request, $response, $args)
    {

        $lista = Pedido_productos::obtenerPendientes("cocina"); 

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

}


?>