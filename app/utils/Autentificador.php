<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AutentificadorJWT
{
    private static $claveSecreta = 'laComanda_tds123';
    private static $tipoEncriptacion = 'HS256';

    
    public static function CrearToken($puesto, $id)
    {
        $ahora = time();
        $payload = array(
            'iat' => $ahora,
            'exp' => $ahora + (600000),
            'aud' => self::Aud(),
            'id' => $id,
            'puesto' => $puesto,
            'app' => "La Comanda"
        );
        return JWT::encode($payload, self::$claveSecreta, self::$tipoEncriptacion);
    }

    public static function VerificarToken($token)
    {
        if (empty($token)) 
        {
            throw new Exception("El token esta vacío.");
        }
        try 
        {
            $decodificado = JWT::decode(
                $token,
                new Key(self::$claveSecreta, self::$tipoEncriptacion)
            );
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
        if ($decodificado->aud !== self::Aud()) {
            throw new Exception("No es el usuario valido");
        }
    }


    public static function ObtenerPayLoad($token)
    {
        if (empty($token)) {
            throw new Exception("El token esta vacío.");
        }
        return JWT::decode(
            $token,
            new Key(self::$claveSecreta, self::$tipoEncriptacion)
        );
    }

    public static function ObtenerPuesto($token)
    {
        return JWT::decode(
            $token,
            new Key(self::$claveSecreta, self::$tipoEncriptacion)
        )->puesto;
    }

    public static function ObtenerID($token)
    {
        return JWT::decode(
            $token,
            new Key(self::$claveSecreta, self::$tipoEncriptacion)
        )->id;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}