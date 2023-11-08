<?php

include 'Controlador/metodos.php';

class Pedido{
    
    private $dsn = '1 - CENTRAL';
    private $usuario = "sa";
    private $clave="Axoft1988";

    private function getDatos($sql){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        ini_set('max_execution_time', 500);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;

    }

    
    
    public function traerPedidos($desde, $hasta, $tienda, $warehouse){

        $tienda = $_GET['tienda'];
        $warehouse = $_GET['warehouse'];
            
        $sql = "
        SET DATEFORMAT YMD
        EXEC RO_ECOMMERCE_PEDIDOS '$desde', '$hasta', '%$tienda', '%$warehouse'
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