<?php

$dsn = '1 - CENTRAL';
$user = 'sa';
$pass = 'Axoft1988';


$sqlCero = "
UPDATE SOF_AUDITORIA SET NRO_TRACKING = 0, USUARIO_ETIQUETA = '' WHERE NRO_TRACKING = 1 
";

$cid=odbc_connect($dsn, $user, $pass);

odbc_exec($cid, $sqlCero);


var_dump($_POST);

for($x=0; $x<count($_POST['pedidos']); $x++){
		
    $pedido = $_POST['pedidos'][$x];
    
    echo 'Pedido: '.$pedido;
	
		$sqlUno = "UPDATE SOF_AUDITORIA SET NRO_TRACKING = 1 WHERE NRO_PEDIDO = '$pedido'";
		odbc_exec($cid, $sqlUno);
}



?>