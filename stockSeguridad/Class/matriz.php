
<?php

class Matriz
{

    private function retornarArray($sqlEnviado)
    {

        require_once 'conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();
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

}