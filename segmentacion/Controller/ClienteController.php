<?php 

$accion = $_GET['accion'];


switch ($accion) {
    case 'traerCategorias':
        traerCategorias();
        break;
    
    default:
        # code...
        break;
}


function traerCategorias () {

    require_once "../Class/Cliente.php";

    $cliente = new Cliente();

    $rubros = $_POST['rubros'];

    $categorias = $cliente->traerCategorias($rubros);

    echo json_encode($categorias);

}