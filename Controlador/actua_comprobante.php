<?php

function actua_comprobante(){
	
	require_once 'Class/Conexion.php';
	$cid = new Conexion();
	$cid_central = $cid->conectarSql('central');
	
	// $cid=odbc_connect($dsn, $user, $pass);
	
	if (!$cid_central){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
	
	
	//////////////TOMA CADA UNO DE LOS PEDIDOS QUE NO ESTAN EN AUDITORIA
	$sqlAcutaComprobantes = "
	EXEC SJ_ACTUA_COMPROBANTES
	";
	
	ini_set('max_execution_time', 300);
	$stmt = sqlsrv_query( $cid_central, $sqlAcutaComprobantes );
	// var_dump("aca");
	// die();

	// sqlsrv_execute($stmt)or die(exit("Error en odbc_exec"));
	
}

?>