<?php

require_once './models/trabajadores/Trabajador.php';
require_once './models/productos/producto.php';
require_once './models/pedidos/pedido.php';
require_once './models/pedidos/pedido_productos.php';

class pedidos_productosController
{
    public function fueraDeTiempo($request, $response, $args)
    {
        
        $lista = pedido_productos::obtenerEntregadosTarde(); 

        if(empty($lista))
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran productos entregados tarde.', 
                                    'resultado' => true,
                                    'accion'=>'Listar productos entregados tarde'));
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar productos entregados tarde'));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}


?>