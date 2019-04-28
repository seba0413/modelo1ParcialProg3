<?php

require_once "./clases/proveedor.php";

class Pedido
{
    public $producto;
    public $cantidad;
    public $idProveedor;

    public function constructor($producto, $cantidad, $idProveedor)
    {
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->idProveedor = $idProveedor;
    }

    public static function hacerPedido($pathPedidos, $pathProveedores, $pedido)
    {
        $flag = false;
        $proveedores = Proveedor::listarProveedores($pathProveedores);
        foreach($proveedores as $pedido)
        {
            if($pedido->id == $pedido->idProveedor)
            {
                Pedido::guardarPedido($pathPedidos, $pedido);
                $flag = true;
                echo "Pedido cargado";
                break;
            }           
        }
        if(!$flag)
            echo "El id de proveedor no coincide con ningun proveedor";
    }

    public static function guardarPedido($path, $pedido)
    {
        $datosPedido = "{$pedido->producto};{$pedido->cantidad};{$pedido->idProveedor}".PHP_EOL;

        if (file_exists($path))
        {
            $file = fopen($path, "a");
            fwrite($file, $datosPedido);
            fclose($file);
        }  
        else
        {
            $file = fopen($path, "w");
            fwrite($file, $datosPedido);
            fclose($file);
        }
    }

    public static function listarPedidos($pathPedidos, $pathProveedores)
    {
        $proveedores = Proveedor::listarProveedores($pathProveedores);
        $pedidos = Pedido::arrayPedidos($pathPedidos);

        foreach($pedidos as $pedido)
        {
            foreach($proveedores as $proveedor)
            {
                if($proveedor->id == $pedido->idProveedor)
                    echo "Producto: ".$pedido->producto." - Cantidad: ".$pedido->cantidad." - Id Proveedor: ".
                    $pedido->idProveedor." - Nombre Proveedor: ".$proveedor->nombre."\n";
            }
        }
    }

    static function arrayPedidos($path)
    {
        if (file_exists($path))
        {
            $gestor = fopen($path, "r");
            while(!feof($gestor))
            {
                $datosPedido = fgets($gestor, filesize($path));
                $arrayDatosPedido = explode(";", $datosPedido);
                if(count($arrayDatosPedido)>1)
                {
                    $pedido = new Pedido();
                    $pedido->constructor(trim($arrayDatosPedido[0]),trim($arrayDatosPedido[1]),trim($arrayDatosPedido[2]));
                    $arrayPedidos[] = $pedido;
                }                
            }                          
            fclose($gestor);
        }  
        return $arrayPedidos;
    }

    public static function listarPedidoProveedor($pathPedidos, $id)
    {
        $pedidos = Pedido::arrayPedidos($pathPedidos);
        foreach($pedidos as $pedido)
        {
            if($pedido->idProveedor == $id)
            {
                echo "{$pedido->producto};{$pedido->cantidad};{$pedido->idProveedor}".PHP_EOL;
            }
        }
    }
}

?>