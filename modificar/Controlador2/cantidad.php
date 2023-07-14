<?php

require '../../../Controlador/dsn_central.php';

$pedido = $_POST['pedido'];
$viejo = $_POST['viejo'];
$cant = $_POST['cant'];

$cantidad = "EXEC SJ_SP_CAMBIAR_CANTIDADES_PEDIDOS_ECOMMERCE '$pedido', '$viejo', $cant";

odbc_exec($cid, $cantidad);






?>