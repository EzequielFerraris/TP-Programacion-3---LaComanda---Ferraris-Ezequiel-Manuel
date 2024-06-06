<?php
include_once "./db/AccesoDatos.php";
class Pedido
{
    public string $codigo;
    public string $cliente;
    public int $idMozo;
    public string $mesa;
    public string $estado;
    public float $monto;
    public string $alta;
    public string $entrega;
    public int $tiempoEstimado;
    public int $tiempoFinal;
    
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO pedidos (codigo, cliente, idMozo, 
                                                        mesa, estado, monto, alta, entrega, tiempoEstimado, 
                                                        tiempoFinal) 
                                                        VALUES (:codigo, :cliente, :idMozo, :mesa, :estado, 
                                                        :monto, :alta, :entrega, :tiempoEstimado, :tiempoFinal)");
        
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':monto', strval($this->monto), PDO::PARAM_STR);
        $consulta->bindValue(':alta', $this->alta, PDO::PARAM_STR);
        $consulta->bindValue(':entrega', $this->entrega, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoFinal', $this->tiempoFinal, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function buscar($codigo)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM pedidos 
                                                        WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
}

?>