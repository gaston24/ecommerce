<?php

function actua_despacho(){
	$dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
	
	
	//////////////TOMA CADA UNO DE LOS PEDIDOS QUE NO ESTAN EN AUDITORIA
	$sqlAcutaComprobantes = "
	SET DATEFORMAT YMD

	UPDATE SOF_AUDITORIA SET PEDIDO_ENTREGADO_E = 1, LOCAL_ENTREGA = 
	CASE 
	WHEN LOCAL_ENTREGA IS NULL THEN '<br>' + CAST(CAST(C.FECHA AS DATE) AS VARCHAR)
	ELSE LOCAL_ENTREGA + '<br>' + CAST(CAST(C.FECHA AS DATE) AS VARCHAR)
	END
	FROM SOF_AUDITORIA A
	INNER JOIN GVA12 B
	ON LEFT(A.NRO_COMP, 14) COLLATE Latin1_General_BIN = B.N_COMP AND B.T_COMP = 'FAC'
	INNER JOIN GC_GDT_GUIA_ENCABEZADO C
	ON B.GC_GDT_NUM_GUIA = C.NUM_GUIA
	WHERE PEDIDO_ENTREGADO_E = 0
	";

	ini_set('max_execution_time', 300);
	odbc_exec($cid,$sqlAcutaComprobantes)or die(exit("Error en odbc_exec"));
}

?>