<?php

require '../../../Controlador/dsn_central.php';

$pedido = $_POST['pedido'];

$buscar = "EXEC SJ_SP_BUSCAR_PEDIDOS_ECOMMERCE '$pedido'";

$result = odbc_exec($cid, $buscar);
while($v=odbc_fetch_array($result)){
    $val = $v['RESULT'];
}
echo $val;

?>