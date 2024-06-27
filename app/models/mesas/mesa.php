<?php
include_once "./db/AccesoDatos.php";
class Mesa
{
    public int $id;
    public string $codigo;
    public string $estado;
    
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO mesas 
                                                        (codigo, estado) 
                                                        VALUES (:codigo, :estado)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', strval($this->estado), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function buscar($codigo)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM mesas 
                                                        WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function buscarPorId($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM mesas 
                                                        WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE mesas SET estado = :estado
                                                    WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);                                               
        $consulta->bindValue(':estado', strval($this->estado), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function mesaMasPedidos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT mesa, COUNT(codigo) as pedidos FROM pedidos 
                                                        GROUP BY mesa DESC LIMIT 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>