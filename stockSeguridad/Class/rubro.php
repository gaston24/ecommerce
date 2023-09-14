
<?php

class Rubro
{

    private function retornarArray($sqlEnviado)
    {

        require_once $_SERVER['DOCUMENT_ROOT']. '/ecommerce/Class/Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectarSql('central');
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query($cid_central, $sql);

        $rows = array();

        while ($v = sqlsrv_fetch_array($stmt)) {
            $rows[] = $v;
        }


        return $rows;
    }


    public function traerRubros()
    {

        $sql = "SELECT REPLACE(REPLACE(PATH_CLASIF, 'Todos(1)/',''), RIGHT(PATH_CLASIF,3),'') RUBRO, PATH_CLASIF FROM GC_ECOMMERCE_CLASIFICADOR_ARTICULOS
		        WHERE PATH_CLASIF NOT LIKE '%DISC%' AND PATH_CLASIF NOT LIKE '%OUTLET%'
        ";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

}