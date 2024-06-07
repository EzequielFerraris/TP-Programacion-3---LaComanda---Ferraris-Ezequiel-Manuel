<?php
include_once "./db/AccesoDatos.php";
class Pedido_productos
{
    public int $id;
    public string $id_pedido;
    public int $id_producto;
    public string $estado;
    public $id_trabajador;
    public int $tiempo_est_minutos;

    public function agregar()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO pedidos_productos (id_pedido, id_producto,
                                                        estado, id_trabajador, tiempo_est_minutos) 
                                                        VALUES (:id_pedido, :id_producto, :estado, 
                                                        :id_trabajador, :tiempo_est_minutos)");
        
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_trabajador', $this->id_trabajador, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_est_minutos', $this->tiempo_est_minutos, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodosPorPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos_productos 
                                                        WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':id_pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido_productos');
    }

    public static function obtenerPendientes($sector)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT pp.id, pp.id_pedido, pr.nombre FROM pedidos_productos 
                                                        as pp INNER JOIN productos as pr
                                                        ON pp.id_producto = pr.id
                                                        WHERE pr.sector = :sector AND pp.estado = 'Sin asignar'");

        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}