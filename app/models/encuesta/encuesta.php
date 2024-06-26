<?php
include_once "./db/AccesoDatos.php";
class Encuesta
{
    public int $id;
    public string $nombre_cliente;
    public string $mesa;
    public string $pedido;
    public int $cocinero_rating;
    public int $restaurante_rating;
    public int $mozo_rating;
    public int $mesa_rating;
    public string $comentario;
    public string $fecha;

    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO encuesta 
                                                        (nombre_cliente, mesa, pedido, 
                                                        cocinero_rating, restaurante_rating, mozo_rating,
                                                        mesa_rating, comentario, fecha) 
                                                        VALUES (:nombre_cliente, :mesa, :pedido, 
                                                        :cocinero_rating, :restaurante_rating, :mozo_rating,
                                                        :mesa_rating, :comentario, :fecha)");
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
        $consulta->bindValue(':cocinero_rating', $this->cocinero_rating, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante_rating', $this->restaurante_rating, PDO::PARAM_INT);
        $consulta->bindValue(':mozo_rating', $this->mozo_rating, PDO::PARAM_INT);
        $consulta->bindValue(':mesa_rating', $this->mesa_rating, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function buscarPorDia($fecha)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM encuesta 
                                                        WHERE fecha = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function buscarPorMesa($mesa)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM encuesta 
                                                        WHERE mesa = :mesa");
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function buscarDiezMejoresPorCategoria($categoria)
    {
        switch($categoria)
        {
            case "cocinero":
                $query = "SELECT * FROM encuesta ORDER BY cocinero_rating DESC LIMIT 10";
            break;
            case "restaurante":
                $query = "SELECT * FROM encuesta ORDER BY restaurante_rating DESC LIMIT 10";
            break;
            case "mozo":
                $query = "SELECT * FROM encuesta ORDER BY mozo_rating DESC LIMIT 10";
            break;
            case "mesa":
                $query = "SELECT * FROM encuesta ORDER BY mesa_rating DESC LIMIT 10";
            break;
        }

        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta($query);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE encuesta SET nombre_cliente = :nombre_cliente, mesa = :mesa, 
                                                    pedido = :pedido, cocinero_rating = :cocinero_rating, 
                                                    restaurante_rating = :restaurante_rating, mozo_rating = :mozo_rating, 
                                                    mesa_rating = :mesa_rating, comentario = :comentario, fecha = :fecha
                                                    WHERE id = :id");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT); 
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
        $consulta->bindValue(':cocinero_rating', $this->cocinero_rating, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante_rating', $this->restaurante_rating, PDO::PARAM_INT);
        $consulta->bindValue(':mozo_rating', $this->mozo_rating, PDO::PARAM_INT);
        $consulta->bindValue(':mesa_rating', $this->mesa_rating, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);                                              
        
        $consulta->execute();
    }
}
?>