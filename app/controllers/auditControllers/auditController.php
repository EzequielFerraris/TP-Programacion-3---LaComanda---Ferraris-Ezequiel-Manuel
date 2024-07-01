<?php

include_once "models/auditLog/auditLog.php";

class AuditController
{
    public function operacionesPorSector($request, $response, $args)
    {
        $lista = auditLog::obtenerPorSector();

        $listaPorSector = array('Gerencia' => 0, 'Cocina' => 0, 'Barra' => 0, 'Choperas' => 0, 'CandyBar' => 0, 'Servicio camareros' => 0); 

        foreach($lista as $set)
        {
            switch($set['puesto'])
            {
                case "socio":
                    $listaPorSector['Gerencia'] += $set['acciones'];
                break;
                case "cocinero":
                    $listaPorSector['Cocina'] += $set['acciones'];
                break;
                case "bartender":
                    $listaPorSector['Barra'] += $set['acciones'];
                break;
                case "cervecero":
                    $listaPorSector['Choperas'] += $set['acciones'];
                break;
                case "cocineroCandybar":
                    $listaPorSector['CandyBar'] += $set['acciones'];
                break;
                case "mozo":
                    $listaPorSector['Servicio camareros'] += $set['acciones'];
                break;
            }
        }

        $payload = json_encode(array('Mensaje'=> $listaPorSector, 
                                        'resultado' => true,
                                        'accion'=>'Obtener acciones por sector'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function operacionesPorTrabajadorSector($request, $response, $args)
    {
        $lista = auditLog::obtenerPorTrabajador();
        $Gerencia = array();
        $Cocina = array();
        $Barra = array();
        $Choperas = array();
        $CandyBar = array();
        $Camareros = array();

        foreach($lista as $set)
        {
            switch($set['puesto'])
            {
                case "socio":
                    array_push($Gerencia, $set);
                break;
                case "cocinero":
                    array_push($Cocina, $set);
                break;
                case "bartender":
                    array_push($Barra, $set);
                break;
                case "cervecero":
                    array_push($Choperas, $set);
                break;
                case "cocineroCandybar":
                    array_push($CandyBar, $set);
                break;
                case "mozo":
                    array_push($Camareros, $set);
                break;
            }
        }

        $listaPorSector = array('Gerencia' => $Gerencia, 
                                'Cocina' => $Cocina, 
                                'Barra' => $Barra, 
                                'Choperas' => $Choperas, 
                                'CandyBar' => $CandyBar, 
                                'Servicio camareros' => $Camareros);
        
        $payload = json_encode(array('Mensaje'=> $listaPorSector, 
                                        'resultado' => true,
                                        'accion'=>'Obtener acciones sector/trabajador'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function ingresosPorDiaTrabajador($request, $response, $args)
    {
        $params = $request->getQueryParams();

        $lista = auditLog::obtenerLoginsPorTrabajador($params['mail']);

        $payload = json_encode(array('Mensaje'=> $lista, 
                                        'resultado' => true,
                                        'accion'=>'Obtener acciones sector/trabajador'));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}

?>