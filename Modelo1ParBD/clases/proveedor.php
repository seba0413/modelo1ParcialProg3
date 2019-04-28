<?php

require_once "./clases/AccesoDatos.php";

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

    public function cargarProveedor()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into proveedor(id,nombre,email,foto)values(:id,:nombre,:email,:foto)");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();                            
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
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

    public static function consultarProveedor($nombre)
    {
        $proveedor = Proveedor::consultaPorNombre($nombre);
        if(!Proveedor::mostrarDatos($proveedor))
            echo "No existe el proveedor ".$nombre;
    }

    public static function consultaPorNombre($nombre)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from proveedor where nombre = :nombre");
        $consulta->bindValue(':nombre',$nombre, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "proveedor");
    }

    public static function mostrarDatos($proveedor)
    {
        if($proveedor)
        {
            foreach($proveedor as $proveedor)
            {
                echo "Id: {$proveedor->id}, Nombre: {$proveedor->nombre}, Email: {$proveedor->email}, Foto: {$proveedor->foto}".PHP_EOL;
            }
            return true;
        }
        else
            return false;
    }

    public static function proveedores()
    {
        $proveedores = Proveedor::TraerTodoLosProveedores();
        Proveedor::mostrarDatos($proveedores);
    }

    public static function TraerTodoLosProveedores()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from proveedor");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "proveedor");		
    }

    public function modificarProveedor()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update proveedor 
            set nombre=:nombre,
            email=:email,
            foto=:foto 
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public static function fotosBack($pathFotosBuck)
    {
        if ($gestor = opendir($pathFotosBuck)) 
        {    
            while (false !== ($entrada = readdir($gestor))) 
            {
                if($entrada != '.' && $entrada != '..')
                {
                    $var = explode("_", $entrada);
                    $id = $var[0];
                    $var2 = explode(".", $var[1]);
                    $fecha = $var2[0];
                    $nombre = Proveedor::buscarProveedorPorId($id);
                    echo $nombre."_".$fecha."\n";
                }
            }

        }
    }

    static function buscarProveedorPorid($id)
    {
        $proveedores = Proveedor::TraerTodoLosProveedores();
        foreach ($proveedores as $proveedor) 
        {
            if($proveedor->id == $id)
            {
                return $proveedor->nombre;
            }
        }
    }
}



?>