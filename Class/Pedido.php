<?php

require_once $_SERVER['DOCUMENT_ROOT']. '/ecommerce/Class/Conexion.php';





class Pedido{
    

    private function getDatos($sql){
        $cid = new Conexion();
        $cid_central = $cid->conectarSql('central');

        ini_set('max_execution_time', 300);
        $result=sqlsrv_query($cid_central,$sql)or die(exit("Error en sqlsrv_query"));
        $data = [];
        while($v=sqlsrv_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;

    }

    
    
    public function traerPedidos($desde, $hasta, $tienda, $warehouse, $estado = null){

        $tienda = $_GET['tienda'];
        $warehouse = $_GET['warehouse'];
            
        $sql = "
        SET DATEFORMAT YMD
        EXEC RO_ECOMMERCE_PEDIDOS '$desde', '$hasta', '%$tienda', '%$warehouse', '$estado'
        ";

        $array = $this->getDatos($sql);    

        return $array;
    }

    public function traerWarehouse(){

            
        $sql = "SELECT * FROM
                (
                SELECT REPLACE(NOMBRE_SUC, 'RT - SUC - ', '') WAREHOUSE FROM STA22
                WHERE NOMBRE_SUC LIKE 'RT%'
                    UNION ALL
                SELECT 'CENTRAL'
                ) A
                ORDER BY 1
        ";

        $array = $this->getDatos($sql);    

        return $array;
    }



}