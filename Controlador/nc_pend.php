<?php

function nc_pendientes(){
    $dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}

    
    $sqlNc = 
    "
    SELECT * FROM SJ_NC_ECOMMERCE_PEND
    "
    ;
    
    ini_set('max_execution_time', 300);
    $result=odbc_exec($cid,$sqlNc)or die(exit("Error en odbc_exec"));
    $cont = 0;
    $fecha_array = [];
    $promo_array = [];
    $importe_array = [];
    $cod_articu = [];
    while($v=odbc_fetch_array($result)){
        if($v['NUM_NC'] == 'NO'){
            $fecha_array[$cont] = $v['FECHA'];
            $promo_array[$cont] = $v['DESC_PROMOCION_TARJETA'];
            $importe_array[$cont] = $v['NC'];
            $cod_articu[$cont] = $v['COD_ARTICU'];
            $cont++;
        }
    }
    
    if($cont != 0){
        for($x=0;$x<$cont;$x++){
            echo '<script>alert("Esta pendiente la NC del dia '.$fecha_array[$x].' por la promo '. $promo_array[$x].' por un importe de '.$importe_array[$x].' (ARTICULO: '.$cod_articu[$x].' )")</script>';
        }
    }
    
    
}
    
    ?>