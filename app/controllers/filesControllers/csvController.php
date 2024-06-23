<?php
use Psr\Http\Message\UploadedFileInterface;

class CsvController
{
    public static string $directory = "uploaded/csv"; 

    public function guardarCSV($request, $response, $args)
    {

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles["pedido"];

        $path = self::moverArchivo($uploadedFile);

        try
        {
            $resultado = self::leerCSV($path);          
            $payload = json_encode(array("mensaje" => $resultado));
        }
        catch(Exception $e)
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }
          
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function leerCSV($path)
    {
        $resultado = "Productos cargados de manera satisfactoria.";

        try
            {
                $file = fopen($path, "r");
                
                fgetcsv($file, 0, ","); //Salta la primera línea

                while(!feof($file))
                {
                    $productoLeido = fgetcsv($file, 0, ",");

                    $c = !empty($productoLeido[0]);

                    if($c)
                    {
                        $instancia = new Producto();      
                        $instancia->nombre = $productoLeido[0];
                        $instancia->precio = $productoLeido[1];
                        $instancia->sector = $productoLeido[2];
                        $instancia->hayStock = $productoLeido[3];
                        $instancia->crear();
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

        $path = self::$directory . DIRECTORY_SEPARATOR . $filename;

        $uploadedFile->moveTo($path);

        return $path;
    }
}

?>