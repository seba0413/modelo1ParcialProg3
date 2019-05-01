<?php

require_once "./clases/vehiculo.php";
require_once "./clases/servicio.php";


class Turno
{
    public $patente;
    public $fecha;
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;

    public function constructor($fecha, $patente, $marca, $modelo, $precio, $tipoServicio)
    {
        $this->fecha = $fecha;
        $this->patente = $patente;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->tipoServicio = $tipoServicio;
    }

    public static function sacarTurno($pathTurno, $pathVehiculo, $fecha, $patente)
    { 
        $vehiculos = Vehiculo::listarVehiculos($pathVehiculo);
        foreach ($vehiculos as $vehiculo) 
        {
            if($vehiculo->patente == $patente)
            {
                $servicio = Servicio::darTipoServicio($vehiculo);
                $turno = new Turno();
                $turno->constructor($fecha, $patente, $vehiculo->marca, $vehiculo->modelo, $vehiculo->precio, $servicio);
                $datos = "{$turno->fecha};{$turno->patente};{$turno->marca};{$turno->modelo};{$turno->precio};{$turno->tipoServicio}".PHP_EOL;
                if (file_exists($pathTurno))
                {
                    $file = fopen($pathTurno, "a");
                    fwrite($file, $datos);
                    fclose($file);        
                }  
                else
                {
                    $file = fopen($pathTurno, "w");
                    fwrite($file, $datos);
                    fclose($file);
                }
            }            
        }
    }

    public static function turnos($pathTurno)
    {
        $arrayTurnos = Turno::listarTurnos($pathTurno);
        Turno::mostrarDatos($arrayTurnos);        
    }

    public static function listarturnos($path)
    {
        if (file_exists($path))
        {
            $gestor = fopen($path, "r");
            while(!feof($gestor))
            {
                $datosTurno = fgets($gestor, filesize($path));
                $arrayDatosTurno = explode(";", $datosTurno);
                if(count($arrayDatosTurno)>1)
                {
                    $turno = new Turno();
                    $turno->constructor(trim($arrayDatosTurno[0]), trim($arrayDatosTurno[1]), trim($arrayDatosTurno[2]), trim($arrayDatosTurno[3]), trim($arrayDatosTurno[4]), trim($arrayDatosTurno[5]));
                    $turnos[] = $turno;
                }                
            }                          
            fclose($gestor);
        }  
        return $turnos;
    }

    public static function mostrarDatos($turnos)
    {
        $file = fopen("./archivos/tabla.html","w");
        fwrite($file, "<!DOCTYPE html>".PHP_EOL);
        fwrite($file, "<html lang='en'>".PHP_EOL);
        fwrite($file, '<table border="1" align="center" bordercolor="blue" cellspacing="2">'.PHP_EOL);
        fwrite($file, "<thead>".PHP_EOL);
        fwrite($file, "<tr><th>Patente</th>".PHP_EOL);
        fwrite($file, "<th>Fecha</th>".PHP_EOL);
        fwrite($file, "<th>Marca</th>".PHP_EOL);
        fwrite($file, "<th>Modelo</th>".PHP_EOL);
        fwrite($file, "<th>Precio</th>".PHP_EOL);
        fwrite($file, "<th>TipoServicio</th></tr>".PHP_EOL);
        fwrite($file, "</thead>".PHP_EOL);
        fwrite($file, "<tbody>".PHP_EOL);
        foreach($turnos as $turno)
        {
            fwrite($file, "<tr>".PHP_EOL);
            fwrite($file, "<td>".$turno->patente."</td>".PHP_EOL);
            fwrite($file, "<td>".$turno->fecha."</td>".PHP_EOL);
            fwrite($file, "<td>".$turno->marca."</td>".PHP_EOL);
            fwrite($file, "<td>".$turno->modelo."</td>".PHP_EOL);
            fwrite($file, "<td>".$turno->precio."</td>".PHP_EOL);
            fwrite($file, "<td>".$turno->tipoServicio."</td>".PHP_EOL);
            fwrite($file, "</tr>".PHP_EOL);
        }       
        fwrite($file, "</tbody>".PHP_EOL);
        fwrite($file, "</table>".PHP_EOL);
        fwrite($file, "</html>".PHP_EOL);
        fclose($file);

        foreach ($turnos as $turno) 
        {
             echo "Fecha: ".$turno->fecha." - Patente: ".$turno->patente." - Marca: ".$turno->marca." - Modelo: ".$turno->modelo." - Precio: ".$turno->precio." - Tipo Servicio: ".$turno->tipoServicio."\n";
        }
    }

    public static function inscripciones($path, $dato)
    {
        $flag = false; 
        $turnos = Turno::listarturnos($path);
        foreach ($turnos as $turno) 
        {
            if($turno->fecha == $dato || $turno->tipoServicio == $dato)
            {
                $arrayTurnos[] = $turno;
                $flag = true; 
            }
        }
        if($flag)
        {
            Turno::mostrarDatos($arrayTurnos);
        }
    }
}

?>