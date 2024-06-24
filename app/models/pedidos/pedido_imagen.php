<?php
include_once "./db/AccesoDatos.php";
class pedido_imagen
{
    public $id_pedido;
    public $imagen_path;
    
    /*
    public function agregar() :mixed
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

    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE pedidos_productos SET estado = :estado, 
                                                        id_trabajador = :id_trabajador, 
                                                        tiempo_est_minutos = :tiempo_est_minutos 
                                                        WHERE id = :id");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id_trabajador', $this->id_trabajador, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_est_minutos', $this->tiempo_est_minutos, PDO::PARAM_INT);
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
        */
}