
<?php
include_once "Trabajador.php";
include_once "./db/AccesoDatos.php";

class Cocinero extends Trabajador
{
    public function crear()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("INSERT INTO cocineros 
                                                        (apellido, nombre, estado, fechaIngreso) 
                                                        VALUES (:apellido, :nombre, :estado, :fechaIngreso)");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->RetornarUltimoIdInsertado();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM cocineros");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cocinero');
    }

    public static function buscar($apellido, $nombre)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM cocineros 
                                                        WHERE apellido = :apellido 
                                                        AND nombre = :nombre");
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cocinero');
    }

    public static function buscarPorId($id)
    {
        $objAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDatos->RetornarConsulta("SELECT * FROM cocineros 
                                                        WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);;
        $consulta->execute();

        return $consulta->fetchObject('Cocinero');
    }

    public function update()
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("UPDATE cocineros SET apellido = :apellido, 
                                                        nombre = :nombre, estado = :estado, 
                                                        fechaIngreso = :fechaIngreso WHERE id = :id");
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    public static function hardDelete($id)
    {
        $objAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objAccesoDato->RetornarConsulta("DELETE FROM cocineros WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                
        $consulta->execute();
    }

}

?>