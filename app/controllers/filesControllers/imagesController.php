<?php
use Psr\Http\Message\UploadedFileInterface;


class ImagesController
{
    public static string $directoryUpload = "uploaded/images";

    public function guardarImagen($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_pedido = $parametros["pedido"]; 

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles["imagen"];

        $path = self::moverArchivo($uploadedFile, $id_pedido);

        if($path != "")
        {
            $payload = json_encode(array("mensaje" => "Imagen asociada correctamente"));
        }
        else 
        {
            $payload = json_encode(array("mensaje" => "La imagen no pudo cargarse"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public static function moverArchivo(UploadedFileInterface $uploadedFile, string $id_pedido)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = $id_pedido . "-" . date("Y-m-d");

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
}

?>