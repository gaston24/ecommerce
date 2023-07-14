<?php

function agregar_novedades(){
    $dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
    
    $sqlAgreNov = 
    "
    EXEC SJ_SP_ECOMMERCE_AGREGAR_NOVEDADES
    "
    ;
  
    odbc_exec($cid,$sqlAgreNov)or die(exit("Error en odbc_exec"));
}
    
?>