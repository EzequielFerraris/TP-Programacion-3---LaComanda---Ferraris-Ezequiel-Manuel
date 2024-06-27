<?php

require_once './models/productos/producto.php';
require_once './interfaces/abm.php';

class ProductosController extends Producto implements ABM
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $sector = $parametros['sector'];
        $hayStock = $parametros['hayStock'];

        // Creamos el producto
        $instancia = new Producto();      
        $instancia->nombre = $nombre;
        $instancia->precio = $precio;
        $instancia->sector = $sector;
        $instancia->hayStock = $hayStock;
        $instancia->crear();

        $payload = json_encode(array('Mensaje'=> 'Producto agregado con éxito', 
                                    'resultado' => true,
                                    'accion'=>'Agregar producto'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
  
	public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();

        $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar productos'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerProductosPorSector($request, $response, $args)
    {
        $sector = $args['sector'];
        $lista = Producto::obtenerProductosPorSector($sector);
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
  
	public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        
        $instancia = Producto::buscarPorId($id);

        $payload = json_encode(array('Mensaje'=> json_encode($instancia), 
                                    'resultado' => true,
                                    'accion'=>'Buscar un producto'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $instancia = Producto::buscarPorId($id);

        if(isset($request['nombre'])) {$instancia->nombre = $request['nombre'];}
        if(isset($request['precio'])) {$instancia->precio = $request['precio'];}
        if(isset($request['sector'])) {$instancia->sector = $request['sector'];}
         
        $instancia->update();

        $payload = json_encode(array('Mensaje'=> 'Producto modificado con exito', 
                                    'resultado' => true,
                                    'accion'=>'Modificar un producto'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DarBajaUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        $trabajador = Producto::buscarPorId($id);
        $trabajador->hayStock = false;
        $trabajador->update();

        $payload = json_encode(array('Mensaje'=> 'Producto marcado como sin stock', 
                                    'resultado' => true,
                                    'accion'=>'Marcar producto sin stock'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function CheckProducto($id) : bool
    {
        $resultado = false;
        try
        {
            $producto = Producto::buscarPorId($id);
        
            if($producto instanceof Producto)
            {
                $resultado = true;
            }
        }
        catch(Exception $e){}

        return $resultado;
    }

}

?>