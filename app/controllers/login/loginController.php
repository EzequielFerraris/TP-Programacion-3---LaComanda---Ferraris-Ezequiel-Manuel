<?php

include_once "utils/Autentificador.php";
include_once "models/socios/socio.php";
include_once "models/trabajadores/Trabajador.php";

class LoginController
{
    public function login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mail = $parametros["mail"];
        $password = $parametros["password"];
        $puesto = $parametros["puesto"];

        $validez = false;

        switch ($puesto)
        {
            case "socio":
                $socio = Socio::buscarPorMail($mail);
                if ($socio->login($password)) {$validez = true;}
            break;
            case "cervecero":
            case "bartender":
            case "cocinero":
            case "cocineroCandybar":
            case "mozo":
                $empleado = Trabajador::buscarPorMail($mail);
                if ($empleado->login($password)) {$validez = true;}
            break;
        }

        if($validez)
        {
            try
            {
                $token = AutentificadorJWT::CrearToken($puesto);
                $payload = json_encode(array('jwt' => $token));
            }
            catch(Exception $e)
            {
                $payload = json_encode(array('Error' => $e->getMessage()));
            }

        }
        else
        {
            $payload = json_encode(array('Error' => "Alguno de los parámetros es incorrecto."));
        }
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

?>