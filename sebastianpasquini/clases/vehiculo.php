<?php

class Vehiculo
{
    public $marca;
    public $modelo;
    public $patente;
    public $precio;

    public function constructor($marca, $modelo, $patente, $precio)
    {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->patente = $patente;
        $this->precio = $precio;
    }
    
    public function cargarVehiculo($path, $patente)
    {
        $datosVehiculo = "{$this->marca};{$this->modelo};{$this->patente};{$this->precio}".PHP_EOL;
        $mismaPatente = false; 
        if (file_exists($path))
        {
            $vehiculos = Vehiculo::listarVehiculos($path);
            foreach($vehiculos as $vehiculo)
            {
                if($vehiculo->patente == $patente)
                {
                    $mismaPatente = true; 
                    break;
                }              
            }
            if(!$mismaPatente)
            {
                $file = fopen($path, "a");
                fwrite($file, $datosVehiculo);
                fclose($file);
            }
        }  
        else
        {
            $file = fopen($path, "w");
            fwrite($file, $datosVehiculo);
            fclose($file);
        }
    }

    public static function consultarVehiculo($path, $dato)
    {
        $flag = false; 
        $arrayVehiculos = Vehiculo::listarVehiculos($path);
        foreach($arrayVehiculos as $vehiculo)
        {       
            if((strcasecmp($vehiculo->marca, $dato)==0) ||
                (strcasecmp($vehiculo->modelo, $dato)==0) || 
                (strcasecmp($vehiculo->patente, $dato)==0))  
            {
                $vehiculos[] = $vehiculo;
                $flag = true;
            }        
        }            
        if($flag)
            Vehiculo::mostrarDatos($vehiculos);
        else
            echo "No existe ".$dato;
    }

    public static function listarVehiculos($path)
    {
        if (file_exists($path))
        {
            $gestor = fopen($path, "r");
            while(!feof($gestor))
            {
                $datosVehiculo = fgets($gestor, filesize($path));
                $arrayDatosVehiculo = explode(";", $datosVehiculo);
                if(count($arrayDatosVehiculo)>1)
                {
                    $vehiculo = new Vehiculo();
                    $vehiculo->constructor(trim($arrayDatosVehiculo[0]),trim($arrayDatosVehiculo[1]),trim($arrayDatosVehiculo[2]),trim($arrayDatosVehiculo[3]));
                    $arrayVehiculos[] = $vehiculo;
                }                
            }                          
            fclose($gestor);
        }  
        return $arrayVehiculos;
    }

    public static function mostrarDatos($vehiculos)
    {
        foreach($vehiculos as $vehiculo)
        {
            echo "Marca: {$vehiculo->marca}, Modelo: {$vehiculo->modelo}, Patente: {$vehiculo->patente}, Precio: {$vehiculo->precio}".PHP_EOL;
        }
    }



}

?>