<?php

function auditoria($dias){
    include '../AccesoDatos/dsn.php';

    $sql = "
    EXEC SJ_AUDITORIA_PRISMA $dias
    ";

    $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

    $rows = array();

    while($v=odbc_fetch_array($result)){
        $rows[] = $v;
        }

    return $rows;

}

function auditoriaDetalle($mail){
    include '../AccesoDatos/dsn.php';

    $sql = "
    EXEC SJ_AUDITORIA_PRISMA_DETALLE '$mail'
    ";

    $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

    $rows = array();

    while($v=odbc_fetch_array($result)){
        $rows[] = $v;
        }

    return $rows;

}


function actualizarAuditoria($mail, $nroPedido, $tarjeta, $autoriza, $noAutoriza){
    include '../../AccesoDatos/dsn.php';

    $sql = "
    EXEC SJ_AUDITORIA_PRISMA_DETALLE_ACTUALIZAR '$mail', '$nroPedido', '$tarjeta', '$autoriza', '$noAutoriza'
    ";

    odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

}