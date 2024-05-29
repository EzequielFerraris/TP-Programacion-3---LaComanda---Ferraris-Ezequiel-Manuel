<?php
interface ABM
{
	public function TraerUno($request, $response, $args);
	public function TraerTodos($request, $response, $args);
	public function CargarUno($request, $response, $args);
	public function DarBajaUno($request, $response, $args);
	public function HardDeleteUno($request, $response, $args);
	public function ModificarUno($request, $response, $args);
}