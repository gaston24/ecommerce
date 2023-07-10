<?php

include 'Controlador/metodos.php';
require_once 'Conexion.php';

class Pedido{

    private function getDatos($sql){

        $cid = new Conexion();
        $cid_central = $cid->conectarSql('central');

        ini_set('max_execution_time', 300);
        $result=sqlsrv_query($cid_central,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=sqlsrv_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;

    }

    
    
    public function traerPedidos($desde, $hasta, $tienda){

        $tienda = $_GET['tienda'];
            
        $sql = "
        SET DATEFORMAT YMD
        EXEC RO_ECOMMERCE_PEDIDOS '$desde', '$hasta', '%$tienda'
        ";

        $array = $this->getDatos($sql);    

        return $array;
    }



}