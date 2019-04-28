<?php

class Proveedor
{
    public $id;
    public $nombre;
    public $email;
    public $foto;

    public function constructor($id, $nombre, $email, $foto)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->foto = $foto;
    }

    public function retornarJson()
    {
        $datos =  json_encode($this).PHP_EOL;
        return $datos;
    }

    public function cargarProveedor($pathProveedor, $foto)
    {
        if (file_exists($pathProveedor))
        {
            $file = fopen($pathProveedor, "a");
            fwrite($file, $this->retornarJson());
            fclose($file);
        }  
        else
        {
            $file = fopen($pathProveedor, "w");
            fwrite($file, $this->retornarJson());
            fclose($file);
        }
        echo "Proveedor cargado correctamente";
        $this->guardarFoto($foto);        
    }

    public function guardarFoto($foto)
    {        
        $ruta = $foto['tmp_name'];
        $extension = explode(".",$foto['name']);
        $index = count($extension) - 1;
        $rutafoto = "./fotos/{$this->id}.{$extension[$index]}";
        $fecha = date("d") . "-" . date("m") . "-" . date("Y");
        $rutaBackup = "./fotosBackup/{$this->id}_{$fecha}.{$extension[$index]}";
        
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

    public static function consultarProveedor($pathProveedor, $nombre)
    {
        $flag = false;
        $proveedores = Proveedor::listarProveedores($pathProveedor);
        
        foreach($proveedores as $proveedor)
        {
            if(strcasecmp($proveedor->nombre, $nombre)==0)
            {
                $arrayProv[] = $proveedor;
                $flag = true;
            }
        }
        if($flag)
            Proveedor::mostrarDatos($arrayProv);
        else
            echo "No existe el proveedor ".$nombre;
    }

    public static function listarProveedores($pathProveedor)
    {
        if (file_exists($pathProveedor))
        {
            $gestor = fopen($pathProveedor, "r");
            while(!feof($gestor))
            {
                $contenido = fgets($gestor, filesize($pathProveedor));    
                $datosProveedor = json_decode($contenido, true);
                if($datosProveedor)
                {
                    $proveedor = new Proveedor();
                    $proveedor->constructor($datosProveedor['id'], $datosProveedor['nombre'], $datosProveedor['email'], $datosProveedor['foto']);
                    $arrayProveedores[] = $proveedor;
                }      
            }        
            fclose($gestor);
        }
        return $arrayProveedores;
    }

    public static function mostrarDatos($proveedores)
    {
        foreach($proveedores as $proveedor)
        {
            echo "Id: {$proveedor->id}, Nombre: {$proveedor->nombre}, Email: {$proveedor->email}, Foto: {$proveedor->foto}".PHP_EOL;
        }
    }

    public static function proveedores($pathProveedor)
    {
        $proveedores = Proveedor::listarProveedores($pathProveedor);
        Proveedor::mostrarDatos($proveedores);
    }

    public function modificarProveedor($pathProveedor, $foto)
    {
        $proveedores = Proveedor::listarProveedores($pathProveedor);
        $this->modificarDatos($proveedores);
        $this->escribirArchivoJSON($pathProveedor, $proveedores);
        $this->guardarFoto($foto);
    }

    function modificarDatos($proveedores)
    {
        $flag = false;
        foreach($proveedores as $proveedor)
        {
            if (strcmp($proveedor->id ,$this->id ) == 0)
            {
                $flag = true; 
                if($this->nombre)                
                    $proveedor->nombre = $this->nombre;

                if($this->email)
                    $proveedor->email = $this->email;

                if($this->foto)
                    $proveedor->foto = $this->foto;

                break;
            }                
        }
        if($flag)
            echo "Datos modificados correctamente";
        else
            echo "No se encontro el id a modificar";
    }

    function escribirArchivoJSON($pathProveedor, $proveedores)
    {
        $file = fopen($pathProveedor, "w");
        foreach ($proveedores as $proveedor)
        {
            fwrite($file, $proveedor->retornarJson());
        }            
        fclose($file); 
    }

    public static function fotosBack($pathFotosBack, $pathProveedor)
    {
        if ($gestor = opendir($pathFotosBack)) 
        {       
            while (false !== ($entrada = readdir($gestor))) 
            {
                if($entrada != '.' && $entrada != '..')
                {
                    $var = explode("_", $entrada);
                    $id = $var[0];
                    $var2 = explode(".", $var[1]);
                    $fecha = $var2[0];
                    $nombre = Proveedor::buscarProveedorPorId($pathProveedor, $id);
                    echo $nombre."_".$fecha."\n";
                }
                
            }
        }
    }

    public static function buscarProveedorPorId($pathProveedor, $id)
    {
        $proveedores = Proveedor::listarProveedores($pathProveedor);
        foreach($proveedores as $proveedor)
        {
            if($proveedor->id == $id)
            {
                $nombre = $proveedor->nombre;
                return $nombre;
            }
        }
    }
}

?>