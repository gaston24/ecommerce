<?php

function actua_comprobante(){
	$dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
	
	
	//////////////TOMA CADA UNO DE LOS PEDIDOS QUE NO ESTAN EN AUDITORIA
	$sqlAcutaComprobantes = "
	EXEC SJ_ACTUA_COMPROBANTES
	";

	ini_set('max_execution_time', 300);
	odbc_exec($cid,$sqlAcutaComprobantes)or die(exit("Error en odbc_exec"));
}

?>