<?php

include_once "./models/socios/socio.php";
class SocioController
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        //$claveSocio = $parametros['claveSocio'];
        //
        $apellido = $parametros['apellido'];
        $nombre = $parametros['nombre'];
        $mail = $parametros['mail'];
        $clave = $parametros['clave'];
                
        $instancia = new Socio();
        $instancia->apellido = $apellido;
        $instancia->nombre = $nombre;
        $instancia->mail = $mail;
        $instancia->setPassword($clave);
        $instancia->encriptar();
        $instancia->crear();

        $payload = json_encode(array('Mensaje'=> 'Socio agregado con éxito', 
                                    'resultado' => true,
                                    'accion'=>'Agregar socio'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function TraerTodos($request, $response, $args)
    {
        $lista = Socio::obtenerTodos();

        $payload = json_encode(array('Mensaje'=> $lista, 
                                    'resultado' => true,
                                    'accion'=>'Listar socios'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
  
    public static function buscarPorMail($mail)
    {
        return Socio::buscarPorMail($mail);
    }

}


?>