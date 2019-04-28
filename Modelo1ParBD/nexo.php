<?php

require_once "./clases/proveedor.php";
require_once "./clases/pedido.php";

$pathFotosBack = "./fotosBackup/";

$dato = $_SERVER['REQUEST_METHOD'];

if($dato == 'POST')
{
    $caso = $_POST['caso'];

    switch ($caso) {
        case 'cargarProveedor':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $foto = $_FILES['foto'];
            $proveedor = new Proveedor();
            $proveedor->constructor($id, $nombre, $email, $id.".jpg");
            $proveedor->cargarProveedor();
            $proveedor->guardarFoto($foto);            
        break;
        
        case 'hacerPedido':
            $producto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $idProveedor = $_POST['idProveedor'];
            $pedido = new Pedido();
            $pedido->constructor($producto, $cantidad, $idProveedor);
            Pedido::hacerPedido($pedido);
        break;

        case 'modificarProveedor':
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $foto = $_FILES['foto'];
            $proveedor = new Proveedor();
            $proveedor->constructor($id, $nombre, $email, $id.".jpg");
            $proveedor->modificarProveedor();
            $proveedor->guardarFoto($foto);

        break;    
    }
}
else
{
    $caso = $_GET['caso'];

    switch ($caso) {
        case 'consultarProveedor':
            $nombre = $_GET['nombre'];
            Proveedor::consultarProveedor($nombre); 
            break;
        
        case 'proveedores':
            Proveedor::proveedores();            
            break;

        case 'listarPedidos':
            Pedido::listarPedidos();
        break;   
        
        case 'listarPedidoProveedor':
            $id = $_GET['id'];
            Pedido::listarPedidoProveedor($id);
        break;

        case 'fotosBack':
            Proveedor::fotosBack($pathFotosBack);
        break;
    }
}
?>