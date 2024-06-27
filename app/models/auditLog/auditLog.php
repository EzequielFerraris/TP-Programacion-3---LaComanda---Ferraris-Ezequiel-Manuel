<?php
include_once "./db/AccesoDatos.php";
class AuditLog
{
    public int $id;
    public int $id_usuario;
    public string $mail;
    public string $puesto;
    public string $accion;
    public string $fecha;
    
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO auditlog 
                                                        (id_usuario, mail, puesto, 
                                                        accion, fecha) 
                                                        VALUES (:id_usuario, :mail, :puesto, 
                                                        :accion, :fecha)");
        $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

     
    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM auditlog");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'AuditLog');
    }

    public static function buscarPorDia($fecha)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM auditlog 
                                                        WHERE fecha = :fecha");
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'AuditLog');
    }

    public static function buscarPorPeriodo($fecha1, $fecha2)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM auditlog 
                                                        WHERE fecha BETWEEN :fecha1 AND :fecha2
                                                        ORDER BY fecha DESC");
        $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'AuditLog');
    }
/*
    
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
        */
}
?>