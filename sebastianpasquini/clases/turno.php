<?php

require_once "./clases/turno.php";
require_once "./clases/servicio.php";


class Turno
{
    public $patente;
    public $fecha;
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;

    public function constructor($patente, $fecha)
    {
        $this->patente = $patente;
        $this->fecha = $fecha;
    }

    public function constructor2($fecha, $patente, $marca, $modelo, $precio, $tipoServicio)
    {
        $this->fecha = $fecha;
        $this->patente = $patente;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->tipoServicio = $tipoServicio;
    }

    public function sacarTurno($pathturno, $pathTurno)
    {
        //$turnos = turno::listarturnos2($pathturno)
                
                
                $datos = "{$this->fecha};{$turno->patente};{$turno->marca};{$turno->modelo};{$turno->precio};{$servicio}".PHP_EOL;
                
                if (file_exists($pathTurno))
                {
                    $turnos = turno::listarturnos2($pathturno);
                    foreach ($turnos as $turno)
                    {
                        $servicio = Servicio::darTipoServicio($turno);
                        if($turno->patente == $this->patente)
                        {
                            $file = fopen($pathTurno, "a");
                            fwrite($file, $datos);
                            fclose($file);

                        }

                    }
                   
                }  
                else
                {
                    $datos = "{$this->fecha};{$turno->patente};{$turno->marca};{$turno->modelo};{$turno->precio};{$servicio}".PHP_EOL;
                    $file = fopen($pathTurno, "w");
                    fwrite($file, $datos);
                    fclose($file);
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
                    $turno->constructor2(trim($arrayDatosTurno[0]), trim($arrayDatosTurno[1]), trim($arrayDatosTurno[2]), trim($arrayDatosTurno[3]), trim($arrayDatosTurno[4]), trim($arrayDatosTurno[5]));
                    $turnos[] = $turno;
                }                
            }                          
            fclose($gestor);
        }  
        return $turnos;
    }

    public function listarturnos2($path)
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
                    $turno->constructor(trim($arrayDatosTurno[0]), trim($arrayDatosTurno[1]));
                    $turnos[] = $turno;
                }                
            }                          
            fclose($gestor);
        }  
        return $turnos;
    }

    public static function mostrarDatos($turnos)
    {
        foreach($turnos as $turno)
        {
            echo "Marca: {$turno->marca}, Modelo: {$turno->modelo}, Patente: {$turno->patente}, Precio: {$turno->precio}".PHP_EOL;
        }
    }

    public static function mostrarDatosTurnos($turnos)
    {
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
            Turno::mostrarDatosTurnos($arrayTurnos);
        }
    }
}

?>