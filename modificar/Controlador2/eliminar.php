<?php

require '../../../Controlador/dsn_central.php';

$pedido = $_POST['pedido'];
$viejo = $_POST['viejo'];   

$eliminar = "EXEC SJ_SP_CAMBIAR_ELIMINAR_PEDIDOS_ECOMMERCE '$pedido', '$viejo'";

odbc_exec($cid, $eliminar);




?>