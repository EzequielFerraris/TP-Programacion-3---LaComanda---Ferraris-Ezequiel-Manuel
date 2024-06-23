<?php
use Psr\Http\Message\UploadedFileInterface;


class ImagesController
{
    public static string $directory = "uploaded/images";

    public static function moverArchivo(array $data, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = $data["mesa"] . $data["pedido"] . $data["fecha"];
        $filename = $basename . $extension;

        $uploadedFile->moveTo(self::$directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

}

?>