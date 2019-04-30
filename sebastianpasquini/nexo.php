<?php

require_once "./clases/vehiculo.php";
require_once "./clases/servicio.php";
require_once "./clases/turno.php";

$pathVehiculo = "./archivos/vehiculos.txt";
$pathServicio = "./archivos/tiposServicio.txt";
$pathTurno = "./archivos/turnos.txt";

$dato = $_SERVER['REQUEST_METHOD'];

if($dato == 'POST')
{
    $caso = $_POST['caso'];

    switch($caso)
    {
        case 'cargarVehiculo':
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $patente = $_POST['patente'];
            $precio = $_POST['precio'];
            $vehiculo = new Vehiculo();
            $vehiculo->constructor($marca, $modelo, $patente, $precio);
            $vehiculo->cargarVehiculo($pathVehiculo, $patente);
        break;

        case 'cargarTipoServicio':
            $id = $_POST['id'];
            $tipo = $_POST['tipo'];
            $precio = $_POST['precio'];
            $demora = $_POST['demora'];
            $servicio = new Servicio();
            $servicio->constructor($id, $tipo, $precio, $demora);
            $servicio->cargarTipoServicio($pathServicio, $tipo);
        break;

        case '':
        break;
    }
}
else
{
    $caso = $_GET['caso'];

    switch($caso)
    {
        case 'consultarVehiculo':
            $dato = $_GET['dato'];
            Vehiculo::consultarVehiculo($pathVehiculo, $dato);      
        break;

        case 'sacarTurno':
            $patente = $_GET['patente'];
            $fecha = $_GET['fecha'];
            $turno = new Turno();
            $turno->constructor($patente, $fecha);
            $turno->sacarTurno($pathVehiculo, $pathTurno);
            
        break;

        case 'turnos':
            $turnos = Turno::listarturnos($pathTurno);
            Turno::mostrarDatosTurnos($turnos);         
        break;

        case 'inscripciones':
            $dato = $_GET['dato'];
            Turno::inscripciones($pathTurno, $dato);
           
        break;

        case '':

        break;
    }
}

?>