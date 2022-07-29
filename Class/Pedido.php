<?php

include 'Controlador/metodos.php';

class Pedido{
    
    private $dsn = '1 - CENTRAL';
    private $usuario = "sa";
    private $clave="Axoft1988";

    private function getDatos($sql){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;

    }
    
    public function traerPedidos($desde, $hasta, $tienda){

        $sql = "
        SET DATEFORMAT YMD
        EXEC RO_ECOMMERCE_PEDIDOS '$desde', '$hasta', '$tienda'
        ";

        $array = $this->getDatos($sql);    

        return $array;
    }



}