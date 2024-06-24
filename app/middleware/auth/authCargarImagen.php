<?php

include_once 'middleware/validaciones.php';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthCargarImagen
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles["imagen"];

        $parametros = $request->getParsedBody();
        $id_pedido = $parametros['pedido'];
        
        if(Validaciones::es_alfanumerico($id_pedido)  && $uploadedFile->getError() === UPLOAD_ERR_OK 
        && Validaciones::es_imagen($uploadedFile))
        {
            $response = $handler->handle($request);
        } 
        else 
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Uno o más de los parámetros es inválido.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


}

?>