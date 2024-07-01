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

    public static function obtenerPorSector()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT puesto, COUNT(puesto) as acciones FROM auditlog 
                                                        GROUP BY puesto");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorTrabajador()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT mail, puesto, COUNT(accion) as acciones FROM auditlog 
                                                        GROUP BY mail");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerLoginsPorTrabajador($mail)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objAccesoDatos->RetornarConsulta("SELECT mail, puesto, accion, fecha FROM auditlog 
                                                        WHERE mail = :mail AND accion = :accion ");

        $consulta->bindValue(':accion', 'Login exitoso', PDO::PARAM_STR);
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>