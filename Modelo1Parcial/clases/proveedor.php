<?php

class Proveedor{

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

    public function cargarProveedor($path, $foto)
    {
        $datosProveedor = "{$this->id};{$this->nombre};{$this->email};{$this->foto}".PHP_EOL;

        if (file_exists($path))
        {
            $file = fopen($path, "a");
            fwrite($file, $datosProveedor);
            fclose($file);
            echo "Proveedor cargado con exito";
        }  
        else
        {
            $file = fopen($path, "w");
            fwrite($file, $datosProveedor);
            fclose($file);
            echo "Proveedor cargado con exito";
        }

        if($foto)
        {
            $this->guardarFoto($foto);
        }
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

    public static function consultarProveedor($path, $nombre)
    {
        $arrayProveedores = Proveedor::listarProveedores($path);
        foreach($arrayProveedores as $proveedor)
        {
            if(strcasecmp($proveedor->nombre,$nombre)==0)   
                $proveedores[] = $proveedor;
        }

        if($proveedores)
            Proveedor::mostrarDatos($proveedores);
        else
            echo "No existe el provedor ".$nombre;
    }

    public static function proveedores($path)
    {   
        $proveedores = Proveedor::listarProveedores($path);
        Proveedor::mostrarDatos($proveedores);
    }

    public static function mostrarDatos($proveedores)
    {
        foreach($proveedores as $proveedor)
        {
            echo "Id: {$proveedor->id}, Nombre: {$proveedor->nombre}, Email: {$proveedor->email}, Foto: {$proveedor->foto}".PHP_EOL;
        }
    }

    public static function listarProveedores($path)
    {
        if (file_exists($path))
        {
            $gestor = fopen($path, "r");
            while(!feof($gestor))
            {
                $datosProveedor = fgets($gestor, filesize($path));
                $arrayDatosProveedor = explode(";", $datosProveedor);
                if(count($arrayDatosProveedor)>1)
                {
                    $proveedor = new Proveedor();
                    $proveedor->constructor(trim($arrayDatosProveedor[0]),trim($arrayDatosProveedor[1]),trim($arrayDatosProveedor[2]),trim($arrayDatosProveedor[3]));
                    $arrayProveedores[] = $proveedor;
                }                
            }                          
            fclose($gestor);
        }  
        return $arrayProveedores;
    }

    public function modificarProveedor($path, $foto)
    {
        $proveedores = Proveedor::listarProveedores($path);
        $this->modificarDatos($proveedores);
        $this->escribirArchivoTXT($path, $proveedores);
        if($foto)
            $this->guardarFoto($foto); 
    }

    function modificarDatos($proveedores)
    {
        foreach($proveedores as $proveedor)
        {
            if (strcmp ($proveedor->id , $this->id) == 0)
            {
                $proveedor->nombre = $this->nombre;
                $proveedor->email = $this->email;
                $proveedor->foto = $this->foto;
                echo "Datos modificados correctamente";
                break;
            }                
        }
    }

    function escribirArchivoTXT($path, $proveedores)
    {
        $file = fopen($path, "w");
        foreach ($proveedores as $proveedor)
        {
            $datosProveedor = "{$proveedor->id};{$proveedor->nombre};{$proveedor->email};{$proveedor->foto};".PHP_EOL;
            fwrite($file, $datosProveedor);
        }                
        fclose($file);
    }

    public static function fotosBack($pathFotosBack, $pathProveedores)
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
                    $nombreProveedor = Proveedor::buscarProveedorPorId($pathProveedores, $id);
                    echo $nombreProveedor."_".$fecha."\n";              
                }
            }
        }
    }

    static function buscarProveedorPorId($pathProveedores, $id)
    {
        $proveedores = Proveedor::listarProveedores($pathProveedores);
        foreach($proveedores as $proveedor)
        {
            if($proveedor->id == $id)
                return $proveedor->nombre;
        }
    }
}

?>