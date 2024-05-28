<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once '../vendor/autoload.php'; //NOS TRAE LOS PAQUETES INSTALADOS

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write("Funciona!");
    return $response;
});


$app->run();