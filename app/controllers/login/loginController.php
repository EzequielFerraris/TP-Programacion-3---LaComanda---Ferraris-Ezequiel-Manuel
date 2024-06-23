<?php

include_once "utils/Autentificador.php";
class LoginController
{
    public function login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mail = $parametros["mail"];
        $password = $parametros["password"];
        $puesto = $parametros["puesto"];

        $validez = true;

        switch ($puesto)
        {
            case "socio":
            break;
            case "cervecero":
            case "bartender":
            case "cocinero":
            case "cocineroCandybar":
            case "mozo":
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
            
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }

    }
 

}

?>