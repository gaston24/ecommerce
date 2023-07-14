
<?php

require_once '../Class/conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar();

$warehouse = $_POST['warehouse'];
$cuenta = $_POST['cuenta'];
$rubro = json_decode($_POST['rubros']);
$stockSeguridad = json_decode($_POST['cantidad']);

var_dump($warehouse);


if (isset($_POST['rubros'])) {
    $c = 0;
    while ($c < count($rubro)) {
        try {
            $sql2 = "EXEC GC_SP_ECOMMERCE_ASIGNAR_STOCK_SEGURIDAD_CLASIFICADOR '$cuenta','$warehouse','$rubro[$c]',$stockSeguridad[$c],0";
            $stmt = sqlsrv_prepare($cid_central, $sql2);
            sqlsrv_execute($stmt);
                echo 'ok '.$cuenta.' '.$warehouse.' '.$rubro[$c].' '.$stockSeguridad[$c];
            $c++;
        } catch (Exception $e) {
            // $e->getMessage() contains the error message
            echo 'Error: ' + $e->getMessage();
        }
    }
   /*  echo 'ok'; */
} else {
    try {
        $sql = "EXEC GC_SP_ECOMMERCE_ACTIVACION_STOCK_SEGURIDAD_POR_CLASIFICADOR '$cuenta','$warehouse', 1";
        $stmt = sqlsrv_query($cid_central, $sql);
    } catch (Exception $e) {
        // $e->getMessage() contains the error message
        echo 'Error: ' + $e->getMessage();
    }
}

$sql3 = "EXEC RO_SP_PIVOT_STOCK_SEGURIDAD_VTEX";

$stmt = sqlsrv_query($cid_central, $sql3);
