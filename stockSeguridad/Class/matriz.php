
<?php

class Matriz
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


    public function traerMatriz()
    {

        $sql = " SELECT [ID], 
                [WAREHOUSE_ID], 
                [VTEX_CUENTA], 
                [DESC_SUCURSAL],
                ISNULL([ACCESORIOS DE VINILICO], 0) ACCESORIOS_DE_VINILICO, 
                ISNULL([BILLETERAS DE CUERO], 0) BILLETERAS_DE_CUERO,
                ISNULL([BILLETERAS DE VINILICO], 0) BILLETERAS_DE_VINILICO,
                ISNULL([CALZADOS], 0) CALZADOS,
                ISNULL([CAMPERAS], 0) CAMPERAS,
                ISNULL([CARTERAS DE CUERO], 0) CARTERAS_DE_CUERO,
                ISNULL([CARTERAS DE VINILICO],0) CARTERAS_DE_VINILICO,
                ISNULL([CHALINAS], 0) CHALINAS,
                ISNULL([CINTOS DE CUERO], 0) CINTOS_DE_CUERO,
                ISNULL([CINTOS DE VINILICO], 0) CINTOS_DE_VINILICO,
                ISNULL([LENTES], 0) LENTES,
                ISNULL([RELOJES], 0) RELOJES,
                ISNULL([INDUMENTARIA], 0) INDUMENTARIA
            FROM RO_T_STOCK_SEGURIDAD_VTEX ORDER BY DESC_SUCURSAL
        ";

        $rows = $this->retornarArray($sql);
        
        $myJSON = json_encode($rows);

        return $myJSON;

    }

    public function traerMatrizUy()
    {
        require_once $_SERVER['DOCUMENT_ROOT']. '/ecommerce/Class/Conexion.php';
        $cid = new Conexion();
        $cid_uruguay = $cid->conectarSql('uy');
        
        $sql = " SELECT [ID], 
                [WAREHOUSE_ID], 
                [VTEX_CUENTA], 
                [DESC_SUCURSAL], 
                ISNULL([BILLETERAS_DE_CUERO], 0) BILLETERAS_DE_CUERO,
                ISNULL([BILLETERAS_DE_VINILICO], 0) BILLETERAS_DE_VINILICO,
                ISNULL([CALZADOS], 0) CALZADOS,
                ISNULL([CAMPERAS], 0) CAMPERAS,
                ISNULL([CARTERAS_DE_CUERO], 0) CARTERAS_DE_CUERO,
                ISNULL([CARTERAS_DE_VINILICO],0) CARTERAS_DE_VINILICO,
                ISNULL([CHALINAS], 0) CHALINAS,
                ISNULL([CINTOS_DE_CUERO], 0) CINTOS_DE_CUERO,
                ISNULL([CINTOS_DE_VINILICO], 0) CINTOS_DE_VINILICO,
                ISNULL([LENTES], 0) LENTES,
                ISNULL([RELOJES], 0) RELOJES,
                0 as INDUMENTARIA
            FROM RO_T_STOCK_SEGURIDAD_VTEX ORDER BY DESC_SUCURSAL
        ";
     

        $stmt = sqlsrv_query($cid_uruguay, $sql);

        $rows = array();

        while ($v = sqlsrv_fetch_array($stmt)) {
            $rows[] = $v;
        }


        return $rows;
       

    }


}