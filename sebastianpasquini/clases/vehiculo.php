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

    public function modificarVehiculo($path, $foto)
    {
        $vehiculos = Vehiculo::listarVehiculos($path);
        $this->modificarDatos($vehiculos);
        $this->escribirArchivoTXT($path, $vehiculos);
        if($foto)
            $this->guardarFoto($foto); 
    }

    function modificarDatos($vehiculos)
    {
        foreach($vehiculos as $vehiculo)
        {
            if (strcmp ($vehiculo->patente , $this->patente) == 0)
            {
                $vehiculo->marca = $this->marca;
                $vehiculo->modelo = $this->modelo;
                $vehiculo->precio = $this->precio;
                echo "Datos modificados correctamente";
                break;
            }                
        }
    }

    function escribirArchivoTXT($path, $vehiculos)
    {
        $file = fopen($path, "w");
        foreach ($vehiculos as $vehiculo)
        {
            $datosvehiculo = "{$vehiculo->marca};{$vehiculo->modelo};{$vehiculo->patente};{$vehiculo->precio};".PHP_EOL;
            fwrite($file, $datosvehiculo);
        }                
        fclose($file);
    }

    public function guardarFoto($foto)
    {        
        $ruta = $foto['tmp_name'];
        $extension = explode(".",$foto['name']);
        $index = count($extension) - 1; 
        $rutafoto = "./fotos/{$this->patente}.{$extension[$index]}";
        $fecha = date("d") . "-" . date("m") . "-" . date("Y");
        $rutaBackup = "./fotosBackup/{$this->patente}_{$fecha}.{$extension[$index]}";

        if(!$this->backupFoto($ruta, $rutafoto, $rutaBackup))
        {
            move_uploaded_file($ruta, $rutafoto);            
        } 
    }

    function backupFoto($rutaTemporal, $rutaOriginal, $rutaDestino)
    {
        if(file_exists($rutaOriginal))
        {
            rename($rutaOriginal, $rutaDestino);
            move_uploaded_file($rutaTemporal, $rutaOriginal);
            return true;
        }
        return false;  
    }

    public static function vehiculos($pathVehiculo)
    {
        $vehiculos = Vehiculo::listarVehiculos($pathVehiculo);
        Vehiculo::mostrarTablaVehiculos($vehiculos);
    }

    static function mostrarTablaVehiculos($vehiculos)
    {
        $file = fopen("./archivos/tablaVehiculos.html","w");
        fwrite($file, "<!DOCTYPE html>".PHP_EOL);
        fwrite($file, "<html lang='en'>".PHP_EOL);
        fwrite($file, '<table border="1" align="center" bordercolor="blue" cellspacing="2">'.PHP_EOL);
        fwrite($file, "<thead>".PHP_EOL);
        fwrite($file, "<tr><th>Patente</th>".PHP_EOL);
        fwrite($file, "<th>Marca</th>".PHP_EOL);
        fwrite($file, "<th>Modelo</th>".PHP_EOL);
        fwrite($file, "<th>Precio</th>".PHP_EOL);
        fwrite($file, "<th>Foto</th></tr>".PHP_EOL);
        fwrite($file, "</thead>".PHP_EOL);
        fwrite($file, "<tbody>".PHP_EOL);
        foreach($vehiculos as $vehiculo)
        {
            fwrite($file, "<tr>".PHP_EOL);
            fwrite($file, "<td>".$vehiculo->patente."</td>".PHP_EOL);
            fwrite($file, "<td>".$vehiculo->marca."</td>".PHP_EOL);
            fwrite($file, "<td>".$vehiculo->modelo."</td>".PHP_EOL);
            fwrite($file, "<td>".$vehiculo->precio."</td>".PHP_EOL);
            fwrite($file, '<td><img src="../fotos/'.$vehiculo->patente.'.jpg" width="50"/></td>'.PHP_EOL);
            fwrite($file, "</tr>".PHP_EOL);
        }       
        fwrite($file, "</tbody>".PHP_EOL);
        fwrite($file, "</table>".PHP_EOL);
        fwrite($file, "</html>".PHP_EOL);
        fclose($file);

        foreach ($vehiculos as $vehiculo) 
        {
             echo "Patente: ".$vehiculo->patente." - Marca: ".$vehiculo->marca." - Modelo: ".$vehiculo->modelo.
             " - Precio: ".$vehiculo->precio."\n";
        }
    }
}

?>