<?php

require_once "./clases/proveedor.php";

class Pedido
{
    public $cantidad;
    public $producto;
    public $idProveedor;

    public function constructor($producto, $cantidad, $idProveedor)
    {
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->idProveedor = $idProveedor;
    }

    public function retornarJson()
    {
        $datos =  json_encode($this).PHP_EOL;
        return $datos;
    }

    public static function hacerPedido($pathPedido, $pathProveedor, $pedido)
    {
        $flag = false; 
        $proveedores = Proveedor::listarProveedores($pathProveedor);
        foreach($proveedores as $pedido)
        {
            if($pedido->id == $pedido->idProveedor)
            {
                Pedido::guardarPedido($pathPedido, $pedido);
                $flag = true;
                break;
            }
        }

        if($flag)
            echo "Pedido guardado correctamente";
        else
            echo "El id de proveedor no coincide con ningun proveedor";

    }

    public static function guardarPedido($pathPedido, $pedido)
    {
        if (file_exists($pathPedido))
        {
            $file = fopen($pathPedido, "a");
            fwrite($file, $pedido->retornarJson());
            fclose($file);
        }  
        else
        {
            $file = fopen($pathPedido, "w");
            fwrite($file, $pedido->retornarJson());
            fclose($file);
        }
    }

    public static function listarPedidos($pathPedido, $pathProveedor)
    {
        $pedidos = Pedido::arrayPedidos($pathPedido);
        $proveedores = Proveedor::listarProveedores($pathProveedor);

        foreach($pedidos as $pedido)
        {
            foreach($proveedores as $proveedor)
            {
                if($pedido->idProveedor == $proveedor->id)
                {
                    echo "Producto: ".$pedido->producto." - Cantidad: ".$pedido->cantidad." - IdProveedor: ".
                    $pedido->idProveedor." - Nombre: ".$proveedor->nombre."\n";
                }
            }
        }
    }

    static function arrayPedidos($pathPedido)
    {
        if (file_exists($pathPedido))
        {
            $gestor = fopen($pathPedido, "r");
            while(!feof($gestor))
            {
                $contenido = fgets($gestor, filesize($pathPedido));    
                $datosPedido = json_decode($contenido, true);
                if($datosPedido)
                {
                    $pedido = new Pedido();
                    $pedido->constructor($datosPedido['producto'], $datosPedido['cantidad'], $datosPedido['idProveedor']);
                    $arrayPedidos[] = $pedido;
                }      
            }        
            fclose($gestor);
        }
        return $arrayPedidos;
    }

    public static function listarPedidoProveedor($pathPedido, $idProveedor)
    {
        $pedidos = Pedido::arrayPedidos($pathPedido);
        foreach($pedidos as $pedido)
        {
            if($pedido->idProveedor == $idProveedor)
            {
                echo "Producto: ".$pedido->producto." - Cantidad: ".$pedido->cantidad." - IdProveedor: ".
                $pedido->idProveedor."\n";  
            }
        }
    }
}

?>