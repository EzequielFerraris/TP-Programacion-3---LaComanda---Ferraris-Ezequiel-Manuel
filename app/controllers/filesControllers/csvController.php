<?php
use Psr\Http\Message\UploadedFileInterface;
use Slim\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

include_once "models/productos/producto.php";
include_once "models/trabajadores/Trabajador.php";
include_once "models/pedidos/pedido.php";

class CsvController
{
    public static string $directoryUpload = "uploaded/csv"; 
    public static string $directoryDownload = "download/csv"; 

    //GUARDAR DESDE CSV ------------------------------------------------
    public function guardarCSV($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $objetoAGuardar = $parametros["objeto"]; 

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles["pedido"];

        $path = self::moverArchivo($uploadedFile);

        if($path != "")
        {
            try
            {
                $arrayObjetos = self::leerCSV($path, true);

                switch($objetoAGuardar)
                {
                    case "producto":
                        $productos = Producto::mapearProductosCSV($arrayObjetos);
                        $r = Producto::guardarProductosCSV($productos);
                        $r ? $resultado = "Archivo cargado correctamente" : $resultado = "El archivo no se pudo guardar";
                    break;
                    default:
                        $resultado = "No se pudo cargar el archivo";
                    break;
                }

                $payload = json_encode(array('Mensaje'=> $resultado, 
                                        'resultado' => true,
                                        'accion'=>'Descargar csv'));
            }
            catch(Exception $e)
            {
                $payload = json_encode(array('Mensaje'=> $e->getMessage(), 
                                        'resultado' => true,
                                        'accion'=>'Descargar csv'));
            }
        }
        else 
        {
            $payload = json_encode(array('Mensaje'=> "El archivo no se pudo guardar correctamente.", 
                                        'resultado' => true,
                                        'accion'=>'Descargar csv'));
        }
        
          
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function leerCSV(string $path, bool $saltarLinea) : array
    {
        $resultado = array();

        try
            {
                $file = fopen($path, "r");
                
                if($saltarLinea) {fgetcsv($file, 0, ",");} //Salta la primera línea
                
                while(!feof($file))
                {
                    $productoLeido = fgetcsv($file, 0, ",");

                    if(!empty($productoLeido[0]))
                    {
                        array_push($resultado, $productoLeido);
                    }
                }
                fclose($file);
            }
            catch(Exception $e)
                {
                    $resultado =  $e->getMessage();
                }
            return $resultado;    
    }

    public static function moverArchivo(UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);

        $filename = $basename . "." . $extension;

        $path = self::$directoryUpload . DIRECTORY_SEPARATOR . $filename;

        try
        {
            $uploadedFile->moveTo($path);
        }
        catch(Exception $e)
        {
            $path = "";
        }

        return $path;
    }

     //CREAR Y ENVIAR CSV AL CLIENTE PARA DESCARGAR ------------------------------------------------
    public static function descargarCSV($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $objetoCSV = $parametros["objeto"]; 

        $fileName = $objetoCSV . ".csv";
        $csv_file = self::$directoryDownload . DIRECTORY_SEPARATOR . $fileName;

        $csvExiste = self::crearDescargaCSV($objetoCSV, $csv_file);

        if($csvExiste)
        {
            $response = $response
                ->withHeader('Content-Type', 'text/csv')
                ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $fileName))
                ->withAddedHeader('Cache-Control', 'max-age=0')
                ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
                ->withHeader('Pragma', 'no-cache')
                ->withBody((new Stream(fopen($csv_file, 'rb'))));
                
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> "No se pudo crear el archivo", 
                                        'resultado' => true,
                                        'accion'=>'Descargar csv'));
            $response->getBody()->write($payload);
        }
        
        return $response; 
    }

    public static function crearDescargaCSV(string $objetoCSV, string $path)
    {
        $resultado = false;

        switch($objetoCSV)
        {
            case "producto":
                $listaObjetos = Producto::obtenerTodosArray();
            break;
            case "pedido":
                $listaObjetos = Pedido::obtenerTodosArray();
            break;
            case "trabajador":
                $listaObjetos = Trabajador::obtenerTodosArray();
            break;
        }    

        if(count($listaObjetos) > 0)
        {
            try
            {
                $handler = fopen($path, 'w+');

                foreach ($listaObjetos as $producto) 
                {
                    fputcsv($handler, $producto);
                }

                fclose($handler);

                $resultado = true;

            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }

        return $resultado;
    }

    public static function descargarCSVGET($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        $objetoCSV = "producto"; 

        $fileName = $objetoCSV . ".csv";
        $csv_file = self::$directoryDownload . DIRECTORY_SEPARATOR . $fileName;

        $csvExiste = self::crearDescargaCSV($objetoCSV, $csv_file);

        if($csvExiste)
        {
            $response = $response
                ->withHeader('Content-Type', 'text/csv')
                ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $fileName))
                ->withAddedHeader('Cache-Control', 'max-age=0')
                ->withHeader('Cache-Control', 'post-check=0, pre-check=0')
                ->withHeader('Pragma', 'no-cache')
                ->withBody((new Stream(fopen($csv_file, 'rb')))); 
        }
        else
        {
            $payload = json_encode(array('Mensaje'=> "No se pudo crear el archivo", 
                                        'resultado' => true,
                                        'accion'=>'Descargar csv'));
            $response->getBody()->write($payload);
        }
        
        return $response;
 
    }
}

?>