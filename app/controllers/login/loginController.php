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
                if ($socio->login($password)) {$validez = true; $idLog = $socio->id;}
            break;
            case "cervecero":
            case "bartender":
            case "cocinero":
            case "cocineroCandybar":
            case "mozo":
                $empleado = Trabajador::buscarPorMail($mail);
                if ($empleado->login($password)) {$validez = true; $idLog = $empleado->id;}
            break;
        }

        if($validez)
        {
            try
            {
                $token = AutentificadorJWT::CrearToken($puesto, $idLog, $mail);
                $payload = json_encode(array('Mensaje'=> $token,
                                            'jwt' => $token,  
                                            'resultado' => true,
                                            'accion'=>'Login exitoso'));
            }
            catch(Exception $e)
            {
                $payload = json_encode(array('Mensaje' => $e->getMessage()));
            }

        }
        else
        {
            $payload = json_encode(array('Mensaje' => "Alguno de los parámetros es incorrecto."));
        }
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

?>