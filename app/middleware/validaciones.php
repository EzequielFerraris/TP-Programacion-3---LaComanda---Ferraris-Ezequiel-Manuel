
<?php

abstract class Validaciones
{

    public function __construct(){}

    public static function int_positivo(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            if($par > 0)
            {
                $result = true;
            }
        }
        return $result;
    }

    public static function int_negativo(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            if($par < 0)
            {
                $result = true;
            }
        }
        return $result;
    }

    public static function es_int(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_float(float $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_FLOAT) === 0 || !filter_var($par, FILTER_VALIDATE_FLOAT) === false)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_float_positivo(float $par) : bool
    {
        $result = false;

        if((filter_var($par, FILTER_VALIDATE_FLOAT) === 0 || !filter_var($par, FILTER_VALIDATE_FLOAT) === false) && $par > 0)
        {
            $result = true;
        }

        return $result;
    }
    public static function es_mail_valido(string $par) : bool
    {
        $result = false;

        if(!filter_var($par, FILTER_VALIDATE_EMAIL) == false && strlen($par) > 0)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_string(string $par) : bool
    {
        $result = false;

        if(is_string($par) && strlen($par) > 0)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_alfanumerico(string $par) : bool
    {
        $result = false;

        if(ctype_alnum($par) && strlen($par) > 0)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_letras(string $par) : bool
    {
        $result = false;

        if(preg_match("/^[a-zA-Z áéíóúAÉÍÓÚÑñ]+$/", $par) && strlen($par) > 0)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_date(DateTime $par) : bool
    {
        $result = false;

        if($par instanceof DateTime)
        {
            $result = true;
        }
        
        return $result;
    }

    public static function tiene_longitud_x(string $par, int $x) : bool
    {
        $result = false;

        if(strlen($par) == $x)
        {
            $result = true;
        }
        
        return $result;
    }

    public static function igual_menos_longitud_x(string $par, int $x) : bool
    {
        $result = false;

        if(strlen($par) <= $x)
        {
            $result = true;
        }
        
        return $result;
    }

    public static function puede_ser_date(string $par) : bool
    {
        $result = false;

        try
        {
            $date= new DateTime($par);
            if(self::es_date($date))
            {
                $result = true;
            }
        }
        catch(Exception $e)
        {
            
        }
        
        return $result;
    }

    public static function es_puesto_valido(string $par) : bool
    {
        $result = false;
        if(Validaciones::es_letras($par))
        {
            switch($par)
            {
                case "socio":
                case "cocinero":
                case "cocineroCandybar":
                case "bartender":
                case "mozo":
                case "cervecero":
                    $result = true;
                break;   
            }
        }
        
        return $result;
    }

    public static function es_sector_valido(string $par) : bool
    {
        $result = false;
        if(Validaciones::es_letras($par))
        {
            switch($par)
            {
                case "cocina":
                case "barra":
                case "choperas":
                case "candybar":
                    $result = true;
                break;   
            }
        }
        
        return $result;
    }

    public static function pass_valido(string $par) : bool
    {
        $result = false;

        if(preg_match("/^[0-9a-zA-Z áéíóúAÉÍÓÚÑñ@\_]+$/", $par) && strlen($par) > 0)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_objeto_valido(string $par) : bool
    {
        $result = false;
        if(Validaciones::es_letras($par))
        {
            switch($par)
            {
                case "pedido":
                case "trabajador":
                case "producto":
                    $result = true;
                break;   
            }
        }
        
        return $result;
    }

    public static function es_mesa_estado(string $par) : bool
    {
        $result = false;
        if(Validaciones::es_letras($par))
        {
            switch($par)
            {
                case "libre":
                case "con cliente esperando pedido":
                case "con cliente comiendo":
                case "con cliente pagando":
                case "cerrada":
                    $result = true;
                break;   
            }
        }
        
        return $result;
    }

    public static function es_csv($file) : bool
    {
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 
                            'application/octet-stream', 'application/vnd.ms-excel', 
                            'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 
                            'application/excel', 'application/vnd.msexcel', 'text/plain');
        $result = false;
        if(in_array($file->getClientMediaType(), $csvMimes))
        {
            $result = true;
        }
        
        return $result;
    }
    
    public static function es_imagen($file) : bool
    {
        $csvMimes = array('image/jpeg', 'image/png');
        $result = false;
        if(in_array($file->getClientMediaType(), $csvMimes))
        {
            $result = true;
        }
        
        return $result;
    }

    public static function es_rating_valido(int $par) : bool
    {
        $result = false;

        if(filter_var($par, FILTER_VALIDATE_INT) === 0 || !filter_var($par, FILTER_VALIDATE_INT) === false)
        {
            if($par > 0 && $par < 11)
            {
                $result = true;
            }
        }
        return $result;
    }
   
    public static function es_comentario_valido(string $par) : bool
    {
        $result = false;

        if(preg_match("/^[a-zA-Z1-9 áéíóúAÉÍÓÚÑñ\s,\.]+$/", $par) && strlen($par) > 0 && strlen($par) < 66)
        {
            $result = true;
        }

        return $result;
    }

    public static function es_categoria_validacion(string $par) : bool
    {
        $result = false;
        if(Validaciones::es_letras($par))
        {
            switch($par)
            {
                case "cocinero":
                case "restaurante":
                case "mozo":
                case "mesa":
                    $result = true;
                break;   
            }
        }
        
        return $result;
    }
}


?>