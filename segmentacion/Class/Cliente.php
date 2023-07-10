<?php

class Cliente{

    function __construct(){
        require_once __DIR__.'/../../Class/conexion.php';
        $cid = new Conexion();
        $this->cid_central = $cid->conectarSql('central');

    } 
    
    public function traerRubros(){

        $sql = " 
            SELECT DESCRIP FROM STA11FLD
            WHERE DESCRIP NOT LIKE '[_]%' 
            AND DESCRIP NOT LIKE 'Todos'
            AND DESCRIP NOT LIKE '%OUTLET' 
            AND DESCRIP NOT IN ('ALHAJEROS', 'PACKAGING')
            ORDER BY DESCRIP
        ";

        $result = sqlsrv_query($this->cid_central, $sql);
        
        $array = [];

        while ($v = sqlsrv_fetch_array($result)) {
            $array[] = $v;
        }

        return $array;
    }

    public function traerCategorias($rubros = null){

        $sql = " 
            SELECT * FROM RO_MAESTRO_RUBRO_CATEGORIA 
        ";
        
        if($rubros != null){
            $sql .= " WHERE RUBRO IN $rubros ";
        }
        $sql .= " ORDER BY 1, 2";
 

        $result = sqlsrv_query($this->cid_central, $sql);
        
        $array = [];

        while ($v = sqlsrv_fetch_array($result)) {
            $array[] = $v;
        }

        return $array;
    }

    public function traerBancos(){

        $sql = " 
            SELECT *   FROM [LAKERBIS].locales_lakers.dbo.CTA_BANCO 
        ";

        $result = sqlsrv_query($this->cid_central, $sql);
        
        $array = [];

        while ($v = sqlsrv_fetch_array($result)) {
            $array[] = $v;
        }

        return $array;
    }



}