<?php
include_once "./db/AccesoDatos.php";
class pedido_imagen
{
    public $id_pedido;
    public $imagen_path;
    
    public function agregar() :mixed
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO pedido_imagen (id_pedido, imagen_path) 
                                                        VALUES (:id_pedido, :imagen_path)");
        
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':imagen_path', $this->imagen_path, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public function update($nueva_imagen_path)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE pedido_imagen SET imagen_path = :nuevo_path, 
                                                        WHERE id_pedido = :id_pedido AND imagen_path = :imagen_path");

        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':imagen_path', $this->imagen_path, PDO::PARAM_STR);
        $consulta->bindValue(':nuevo_path', $nueva_imagen_path, PDO::PARAM_STR);
        $consulta->execute();

        $this->imagen_path = $nueva_imagen_path;
    }

    public static function buscarImagenesPorPedido($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedido_imagen 
                                                        WHERE id_pedido = :id_pedido");

        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject("Pedido_productos");
    }

    public static function delete_imagen($id_pedido, $path_imagen)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("DELETE FROM pedido_imagen 
                                                        WHERE id_pedido = :id_pedido
                                                        AND imagen_path = :imagen_path");

        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':imagen_path', $path_imagen, PDO::PARAM_STR);
        $consulta->execute();

    }

}