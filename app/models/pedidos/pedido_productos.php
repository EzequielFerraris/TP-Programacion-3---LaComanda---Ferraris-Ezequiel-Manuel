<?php
include_once "./db/AccesoDatos.php";
class Pedido_productos
{
    public int $id;
    public string $id_pedido;
    public string $nombre_producto;
    public int $id_producto;
    public string $estado;
    public $id_trabajador;
    public int $tiempo_est_minutos;
    public $hora_tomado;
    public $hora_completado;
    public int $tiempo_tardado;

    public function agregar() :mixed
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO pedidos_productos (id_pedido, nombre_producto,
                                                        id_producto, estado, id_trabajador, tiempo_est_minutos, 
                                                        hora_tomado, hora_completado, tiempo_tardado) 
                                                        VALUES (:id_pedido, :nombre_producto,
                                                        :id_producto, :estado, :id_trabajador, :tiempo_est_minutos, 
                                                        :hora_tomado, :hora_completado, :tiempo_tardado)");
        
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_producto', $this->nombre_producto, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_trabajador', $this->id_trabajador, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_est_minutos', $this->tiempo_est_minutos, PDO::PARAM_INT);
        $consulta->bindValue(':hora_tomado', $this->tiempo_est_minutos, PDO::PARAM_STR);
        $consulta->bindValue(':hora_completado', $this->tiempo_est_minutos, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_tardado', $this->tiempo_est_minutos, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodosPorPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT id_pedido, nombre_producto, estado, id_trabajador,
                                                        tiempo_est_minutos
                                                        FROM pedidos_productos 
                                                        WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':id_pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPendientes($sector) : mixed
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT pp.id, pp.id_pedido, pr.nombre, pp.estado FROM pedidos_productos 
                                                        as pp INNER JOIN productos as pr
                                                        ON pp.id_producto = pr.id
                                                        WHERE pr.sector = :sector AND pp.estado = 'Sin asignar'");

        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE pedidos_productos SET estado = :estado, 
                                                        id_trabajador = :id_trabajador, 
                                                        tiempo_est_minutos = :tiempo_est_minutos,
                                                        hora_tomado = :hora_tomado, 
                                                        hora_completado = :hora_completado, 
                                                        tiempo_tardado = :tiempo_tardado
                                                        WHERE id = :id");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_trabajador', $this->id_trabajador, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_est_minutos', $this->tiempo_est_minutos, PDO::PARAM_INT);
        $consulta->bindValue(':hora_tomado', $this->hora_tomado, PDO::PARAM_STR);
        $consulta->bindValue(':hora_completado', $this->hora_completado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_tardado', $this->tiempo_tardado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function buscarPorId($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos_productos 
                                                        WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);;
        $consulta->execute();

        return $consulta->fetchObject("Pedido_productos");
    }

    public static function checkTiemposEstimados($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos_productos 
                                                        WHERE id_pedido = :id_pedido");

        $consulta->bindValue(':id_pedido', $codigoPedido, PDO::PARAM_STR);;
        $consulta->execute();

        $consulta = $consulta->fetchAll("Pedido_productos");

        $resultado = true;

        foreach($consulta as $p)
        {
            if(is_null($p->tiempo_est_minutos))
            {
                $resultado = false;
                break;
            }
        }

        return $resultado;
    }

    public static function checkEstadosProductos($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos_productos 
                                                        WHERE id_pedido = :id_pedido");

        $consulta->bindValue(':id_pedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        $consulta = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido_productos");

        $resultado = true;

        foreach($consulta as $p)
        {
            if($p->estado != "Listo")
            {
                $resultado = false;
                break;
            }
        }

        return $resultado;
    }

    public static function obtenerTiemposPorTrabajador($codigoPedido) 
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT id_trabajador, SUM(tiempo_est_minutos) as tiempo 
                                                        FROM pedidos_productos
                                                        WHERE id_pedido = :id_pedido
                                                        GROUP BY id_trabajador 
                                                        ORDER BY tiempo
                                                        DESC");
                                  
        $consulta->bindValue(':id_pedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function obtenerMonto($pedido) 
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT SUM(pr.precio) AS monto FROM pedidos_productos 
                                                        as pp INNER JOIN productos as pr
                                                        ON pp.id_producto = pr.id
                                                        WHERE pp.id_pedido = :pedido AND pp.estado = 'listo'");

        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();
        $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
        return $result[0]["monto"];
    }

    public static function obtenerEntregadosTarde()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT nombre_producto, id_pedido,
                                                        id_trabajador, tiempo_est_minutos as minutos_estimados,
                                                        tiempo_tardado as minutos_tardados
                                                        FROM pedidos_productos 
                                                        WHERE tiempo_est_minutos < tiempo_tardado");

        $consulta->execute();
        $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
}