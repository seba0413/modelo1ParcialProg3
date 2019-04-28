<?php

require_once "./clases/proveedor.php";
require_once "./clases/pedido.php";

$pathProveedores = "./archivos/proveedores.json";
$pathPedidos = "./archivos/pedidos.json";
$pathFotosBack = "./fotosBackup/";

$dato = $_SERVER['REQUEST_METHOD'];

if($dato == 'POST')
{    
    $caso = $_POST['caso'];

    switch($caso)
    {
        case 'cargarProveedor':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $foto = $_FILES['foto'];
            $proveedor = new Proveedor();
            $proveedor->constructor($id, $nombre, $email, $id.".jpg");
            $proveedor->cargarProveedor($pathProveedores, $foto);
        break;
        case 'hacerPedido':
            $producto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $idProveedor = $_POST['idProveedor'];
            $pedido = new Pedido();
            $pedido->constructor($producto, $cantidad, $idProveedor);
            Pedido::hacerPedido($pathPedidos, $pathProveedores, $pedido);
        break;
        case 'modificarProveedor':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $foto = $_FILES['foto'];
            $proveedor = new Proveedor();
            $proveedor->constructor($id, $nombre, $email, $id.".jpg");
            $proveedor->modificarProveedor($pathProveedores, $foto);
        break;
    }
}
else
{
    $caso = $_GET['caso'];

    switch($caso)
    {
        case 'consultarProveedor':
            $nombre = $_GET['nombre'];
            Proveedor::consultarProveedor($pathProveedores, $nombre);
        break;

        case 'proveedores':
            Proveedor::proveedores($pathProveedores);
        break;

        case 'listarPedidos':
            Pedido::listarPedidos($pathPedidos, $pathProveedores);
        break;
        
        case 'listarPedidoProveedor':
            $id = $_GET['id'];
            Pedido::listarPedidoProveedor($pathPedidos, $id);
        break;

        case 'fotosBack':
            Proveedor::fotosBack($pathFotosBack, $pathProveedores);
        break;

    }
}

?>