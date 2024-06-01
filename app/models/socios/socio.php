<?php

class Socio
{
    public int $id;
    public string $apellido;
    public string $nombre;
    public string $mail;
    private string $clave;

    public function __construct() 
    {
        $this->clave = password_hash($this->clave, PASSWORD_DEFAULT);
    }

    public function login(string $pass) : bool
    {
        $hash = $this->clave;
        $result = password_verify($pass, $hash);
        return $result;
    }

    public function encriptar()
    {
        $this->clave = password_hash($this->clave, PASSWORD_DEFAULT);
    }

    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO socios 
                                                        (apellido, nombre, mail, clave) 
                                                        VALUES (:apellido, :nombre, :mail, :clave)");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR); 
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM socios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Socio');
    }

    public static function buscar($apellido, $nombre)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM socios 
                                                        WHERE apellido = :apellido 
                                                        AND nombre = :nombre");
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Socio');
    }

    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE socios SET apellido = :apellido, 
                                                        nombre = :nombre, mail = :mail, clave = :clave WHERE id = :id");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function hardDelete($id)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("DELETE FROM socios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                
        $consulta->execute();
    }

}

?>