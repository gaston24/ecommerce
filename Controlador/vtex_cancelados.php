<?php
function cancelados_vtex(){
	$dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
	
	
	//////////////SOF_AUDITORIA
	$sqlPedidosCancelados = "
	EXEC SJ_CANCELAR_PEDIDOS_VTEX
	";

	odbc_exec($cid,$sqlPedidosCancelados)or die(exit("Error en odbc_exec"));
	

}

?>