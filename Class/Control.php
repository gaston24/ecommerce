<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/ecommerce/Class/Conexion.php';

class Control {
    private function getDatos($sql) {
        $cid = new Conexion();
        $cid_central = $cid->conectarSql('central');
        
        ini_set('max_execution_time', 300);
        $result = sqlsrv_query($cid_central, $sql);
        
        if ($result === false) {
            $errors = sqlsrv_errors();
            throw new Exception("Error en la consulta: " . $errors[0]['message']);
        }
        
        $row = sqlsrv_fetch_object($result);
        return $row ? $row : null;
    }

    private function getDatosMultiples($sql) {
        $cid = new Conexion();
        $cid_central = $cid->conectarSql('central');
        
        ini_set('max_execution_time', 300);
        $result = sqlsrv_query($cid_central, $sql);
        
        if ($result === false) {
            $errors = sqlsrv_errors();
            throw new Exception("Error en la consulta: " . $errors[0]['message']);
        }
        
        $rows = array();
        while ($row = sqlsrv_fetch_object($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function traerNcPendPromociones() {
        $sql = "SELECT MIN(CAST(FECHA AS DATE)) FECHA, COUNT(*) CANT_NC_PROMO, SUM(NC) IMPORTE_NC FROM SJ_NC_ECOMMERCE_PEND WHERE NUM_NC = 'NO'";
        return $this->getDatos($sql);
    }

    public function traerNcPendDevoluciones() {
        $sql = "SELECT MIN(CAST(FECHA_PEDI AS DATE)) FECHA, COUNT(*) CANT_NC_DEV, SUM(IMPORTE) IMPORTE_PEND FROM 
                (
                SELECT A.FECHA_PEDI, A.NRO_PEDIDO, A.ORDER_ID_TIENDA, C.N_COMP, D.IMPORTE FROM GVA21 A
                LEFT JOIN RO_T_ESTADO_PEDIDOS_ECOMMERCE B ON A.ORDER_ID_TIENDA = B.ORDER_ID
                LEFT JOIN GVA55 C ON A.TALON_PED = C.TALON_PED AND A.NRO_PEDIDO = C.NRO_PEDIDO
                LEFT JOIN GVA12 D ON C.N_COMP = D.N_COMP AND C.T_COMP = D.T_COMP
                WHERE A.COD_CLIENT = '000000' AND A.FECHA_PEDI >= GETDATE()-90 AND B.CANCELADO = 1 AND B.NCR IS NULL AND C.N_COMP IS NOT NULL
                ) A";
        return $this->getDatos($sql);
    }

    public function traerNcrRealizadas() {
        $sql = "SELECT 
                    CAST(A.FECHA_EMIS AS DATE) AS FECHA_EMIS, 
                    COUNT(A.N_COMP) AS CANT_NCR 
                FROM GVA12 A
                INNER JOIN GVA53 B ON A.T_COMP = B.T_COMP AND A.N_COMP = B.N_COMP
                WHERE COD_CLIENT = '000000' 
                AND FECHA_EMIS >= DATEADD(DAY, -7, CAST(GETDATE() AS DATE))
                AND A.T_COMP = 'NCR' 
                AND B.COD_ARTICU LIKE '[XO]%'
                GROUP BY CAST(A.FECHA_EMIS AS DATE)
                ORDER BY CAST(A.FECHA_EMIS AS DATE)";
        return $this->getDatosMultiples($sql);
    }

    public function traerOrdenesSinIntegrar() {
        $sql = "SELECT MIN(CAST(A.FECHA_ORDEN AS DATETIME)) FECHA_ORDEN, COUNT(*) CANT_ORDENES, SUM(TOTAL_ORDEN) TOTAL_ORDEN FROM
                (
                SELECT A.FECHA_ULTIMA_SINCRONIZACION FECHA_ORDEN, A.ORDER_NRO_TIENDA, A.TOTAL_ORDEN, A.ESTADO_ORDEN FROM NEXO_PEDIDOS_ORDEN A
                LEFT JOIN GVA21 B ON A.ORDER_ID_TIENDA = B.ORDER_ID_TIENDA
                WHERE B.NRO_PEDIDO IS NULL AND A.FECHA_ORDEN >= GETDATE()-90 AND A.ESTADO_ORDEN NOT LIKE 'CANCELADA%'
                ) A";
        return $this->getDatos($sql);
    }

    public function traerPedidosSinFactTiendas() {
        $sql = "SELECT MIN(FECHA_HORA) AS FECHA_PEDI, COUNT(*) AS CANT_PED_SIN_FACT, SUM(TOTAL_PEDI) AS TOTAL_PEDIDOS FROM 
                (SELECT TRY_CAST(CONCAT(FORMAT(A.FECHA_PEDI, 'yyyy-dd-MM'), ' ', LEFT(A.HORA_INGRESO, 2), ':', SUBSTRING(A.HORA_INGRESO, 3, 2)) AS DATETIME) AS FECHA_HORA,
                A.TOTAL_PEDI FROM GVA21 A
                LEFT JOIN GVA55 C ON A.TALON_PED = C.TALON_PED AND A.NRO_PEDIDO = C.NRO_PEDIDO
                LEFT JOIN GVA12 D ON C.N_COMP = D.N_COMP AND C.T_COMP = D.T_COMP
                WHERE A.COD_CLIENT = '000000' AND A.FECHA_PEDI >= DATEADD(DAY, -7, GETDATE()) AND C.N_COMP IS NULL 
                AND A.COD_SUCURS NOT IN ('01', '11') AND DATEADD(MINUTE, 30, TRY_CAST(CONCAT(FORMAT(A.FECHA_PEDI, 'yyyy-MM-dd'), 
                ' ', LEFT(A.HORA_INGRESO, 2),':', SUBSTRING(A.HORA_INGRESO, 3, 2),':',RIGHT(A.HORA_INGRESO, 2)) AS DATETIME)) < GETDATE()) A
                ";
        return $this->getDatos($sql);
    }

    public function traerPedidosFlex() {
        $sql = "SELECT MIN(CAST(FECHA_SINCRONIZADO AS DATETIME)) AS FECHA_PEDI, COUNT(*) AS CANT_PED_PEND, SUM(TOTAL_PEDI) AS TOTAL_PEDIDOS FROM 
                (
                SELECT C.FECHA_SINCRONIZADO, A.HORA_INGRESO, A.ORDER_ID_TIENDA, A.TOTAL_PEDI, C.ENTREGADO, C.CANCELADO FROM GVA21 A
                INNER JOIN (SELECT * FROM RO_V_WAREHOUSE_METODO_ENVIO_VTEX WHERE METODO_ENVIO = 'FLEX') B ON A.COD_TRANSP = B.COD_TRANSP
                LEFT JOIN RO_T_ESTADO_PEDIDOS_ECOMMERCE C ON A.ORDER_ID_TIENDA = C.ORDER_ID
                WHERE A.COD_CLIENT = '000000' AND A.FECHA_PEDI >= CAST(GETDATE() - 10 AS DATE) AND ((A.FECHA_PEDI = CAST(GETDATE() AS DATE) AND 
                TRY_CAST(A.HORA_INGRESO AS INT) <= 120000) OR A.FECHA_PEDI < CAST(GETDATE() AS DATE)) AND C.DESPACHADO IS NULL AND C.ENTREGADO IS NULL
				AND C.CANCELADO IS NULL AND A.COD_SUCURS = '01' AND A.ESTADO != '5'
                ) A;
        ";
        return $this->getDatos($sql);
    }

    public function traerFacturasSinRemito() {
        $sql = "SELECT MIN(CAST(FECHA_FACTURA AS DATE)) FECHA_FACTURA, COUNT(DISTINCT(FACTURA)) CANT_FACTURAS, SUM(IMPORTE) IMPORTE, MAX(FECHA_ACTUALIZACION) FECHA_ACTUALIZACION 
                FROM [LAKERBIS].[LOCALES_LAKERS].DBO.RO_FACTURAS_SIN_REMITO;
        ";
        return $this->getDatos($sql);
    }

    public function traerDetalleFacturasSinRemito() {
        $sql = "SELECT SUCURSAL, FECHA_FACTURA, FACTURA, A.COD_ARTICU, B.DESC_CTA_ARTICULO, CANTIDAD 
                FROM RO_FACTURAS_SIN_REMITO A  
                INNER JOIN CTA_ARTICULO B ON A.COD_ARTICU = B.COD_ARTICULO
                ORDER BY FECHA_FACTURA DESC, SUCURSAL";
        return $this->getDatosMultiples($sql);
    }
    
}