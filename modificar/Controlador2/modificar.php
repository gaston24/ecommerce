<?php

require '../../../Controlador/dsn_central.php';

$pedido = $_POST['pedido'];
$viejo = $_POST['viejo'];
$nuevo = $_POST['nuevo'];

$modificar = "EXEC SJ_SP_CAMBIAR_PEDIDOS_ECOMMERCE '$pedido', '$viejo', '$nuevo'";

odbc_exec($cid, $modificar);




?>