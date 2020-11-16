<?php

function new_vtex(){
	$dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$sql = "
	SET DATEFORMAT YMD

	EXEC SJ_NUEVOS_VTEX_AUDITORIA

	";

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){
		exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");
	}

	ini_set('max_execution_time', 300);
	odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
	
}


?>