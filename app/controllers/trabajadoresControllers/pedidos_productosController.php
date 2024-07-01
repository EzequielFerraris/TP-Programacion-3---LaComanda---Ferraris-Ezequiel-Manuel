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

    public function MasVendidoAMenos($request, $response, $args)
    {
        
        $lista = pedido_productos::obtenerMasVendidoAMenos(); 

        if(empty($lista))
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran productos vendidos.', 
                                    'resultado' => true,
                                    'accion'=>'Listar productos más a menos vendidos'));
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar productos más a menos vendidos'));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
   
    public function mesaEntreFechas($request, $response, $args)
    {
        $params = $request->getQueryParams();

        $lista = Pedido::facturacionEntreDosFechasMesa($params['fecha1'], $params['fecha2']); 

        if(empty($lista))
        {
            $payload = json_encode(array('Mensaje'=> 'No se registran ventas entre esas fechas.', 
                                    'resultado' => true,
                                    'accion'=>'Listar facturación mesa entre dos fechas'));
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar facturación mesa entre dos fechas'));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
?>