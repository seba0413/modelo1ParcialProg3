<?php

class Servicio
{
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    public function constructor($id, $tipo, $precio, $demora)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->demora = $demora;
    }

    public function cargarTipoServicio($path, $tipo)
    {
        $datosServicio = "{$this->id};{$this->tipo};{$this->precio};{$this->demora}".PHP_EOL;

        if($tipo == "10000" || $tipo == "20000" || $tipo == "50000")
        {
            if (file_exists($path))
            {
                $file = fopen($path, "a");
                fwrite($file, $datosServicio);
                fclose($file);
            }  
            else
            {
                $file = fopen($path, "w");
                fwrite($file, $datosServicio);
                fclose($file);
            }
        }
    }

    public static function darTipoServicio($vehiculo)
    {   
        if($vehiculo->precio <= 100000)
            return "10000";
        if($vehiculo->precio > 100000 && $vehiculo->precio < 200000)
            return "20000";
        if($vehiculo->precio >= 200000)  
            return "50000";  
    }
}

?>