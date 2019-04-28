<?php

require_once "./clases/proveedor.php";
require_once "./clases/AccesoDatos.php";

class Pedido
{
    public $producto;
    public $cantidad;
    public $idProveedor;

    public function constructor($producto,$cantidad, $idProveedor)
    {
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->idProveedor = $idProveedor;
    }

    public static function hacerPedido($pedido)
    {
        $flag = false;
        $proveedores = Proveedor::TraerTodoLosProveedores();
        foreach($proveedores as $proveedor)
        {
            if($pedido->idProveedor == $proveedor->id)
            {
                Pedido::cargarPedido($pedido);
                $flag = true; 
                break;
            }
        }
        if($flag)
            echo "El pedido se cargo correctamente";
        else
            echo "El id de proveedor no coincide con ningun proveedor";
    }

    public static function cargarPedido($pedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedido(producto,cantidad,idProveedor)values(:producto,:cantidad,:idProveedor)");
        $consulta->bindValue(':producto',$pedido->producto, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $pedido->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idProveedor', $pedido->idProveedor, PDO::PARAM_INT);
        $consulta->execute();                            
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function listarPedidos()
    {
        $proveedores = Proveedor::TraerTodoLosProveedores();
        $pedidos = Pedido::TraerTodoLosPedidos();
        foreach ($pedidos as $pedido) 
        {
            foreach ($proveedores as $proveedor) 
            {
                if($pedido->idProveedor == $proveedor->id)
                {
                    echo "Producto: ".$pedido->producto." - Cantidad: ".$pedido->cantidad." - Id Proveedor: ".
                    $pedido->idProveedor." - Nombre: ".$proveedor->nombre."\n";
                }
            }
        }
    }

    public static function TraerTodoLosPedidos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from pedido");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "pedido");		
    }

    public static function listarPedidoProveedor($idProveedor)
    {
        $pedidos = Pedido::TraerTodoLosPedidos();
        foreach($pedidos as $pedido)
        {
            if($pedido->idProveedor == $idProveedor)
            {
                echo "Producto: ".$pedido->producto." - Cantidad: ".$pedido->cantidad." - Id Proveedor: ".
                $pedido->idProveedor."\n";
            }
        }
    }
}

?>