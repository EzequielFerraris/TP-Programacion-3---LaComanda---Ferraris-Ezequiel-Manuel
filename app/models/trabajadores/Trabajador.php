<?php

class Trabajador
{
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $puesto;
    public string $estado;
    public string $fechaIngreso;
    public string $mail;
    private string $clave;


    //MANEJO PASSWORD
    public function login(string $pass) : bool
    {
        $hash = $this->clave;
        $result = password_verify($pass, $hash);
        return $result;
    }

    public function setPassword($clave)
    {
        $this->clave = $clave;
    }

    public function getPassword()
    {
        return $this->clave;
    }

    public function encriptar()
    {
        $nueva = password_hash($this->getPassword(), PASSWORD_DEFAULT);
        $this->clave = $nueva;
    }

    //ABM FUNCIONES
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM trabajadores");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Trabajador");
    }
    public static function buscar($apellido, $nombre)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM trabajadores 
                                                        WHERE apellido = :apellido 
                                                        AND nombre = :nombre");
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject("Trabajador");
    }

    public static function buscarPorId($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM trabajadores 
                                                        WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);;
        $consulta->execute();

        return $consulta->fetchObject("Trabajador");
    }

    public static function buscarPorMail($mail)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM trabajadores 
                                                        WHERE mail = :mail");
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Trabajador');
    }
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO trabajadores 
                                                        (apellido, nombre, estado, puesto, fechaIngreso, 
                                                        mail, clave) 
                                                        VALUES (:apellido, :nombre, :estado, 
                                                        :puesto, :fechaIngreso, :mail, :clave)");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }
    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE trabajadores SET apellido = :apellido, 
                                                        nombre = :nombre, estado = :estado, puesto = :puesto 
                                                        fechaIngreso = :fechaIngreso, mail = :mail 
                                                        WHERE id = :id");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function cambiarPass()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE trabajadores SET clave = :clave WHERE id = :id");
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
}

?>