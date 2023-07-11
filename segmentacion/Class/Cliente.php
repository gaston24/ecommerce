<?php

class Cliente{

    function __construct(){
        require_once __DIR__.'/../../Class/conexion.php';
        $cid = new Conexion();
        $this->cid_central = $cid->conectarSql('central');
        $this->cid_mongo = $cid->conectarMongoDb();

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

    public function traerClientes($desde,$hasta,$selectBanco,$selectRubro,$selectCategoria,$selectRangoEtario){
      

        $mongoCollection = $this->cid_mongo->selectCollection("Ventas");

        $filter = [];

        if($selectBanco != null){
            $filter["BANCO"] = array('$in' => $selectBanco);
        }
        if($selectRubro != null){
            $filter["RUBRO"] = array('$in' => $selectRubro);
        }
        if($selectCategoria != null){
            $filter["CATEGORIA"] = array('$in' => $selectCategoria);
        }
        if($selectRangoEtario != null){
            $filter["RANGO_ETARIO"] = array('$in' => $selectRangoEtario);
        }
        
        if ($desde != null && $hasta != null) {

            $desdeDate = new MongoDB\BSON\UTCDateTime(strtotime($desde) * 1000);
            $hastaDate = new MongoDB\BSON\UTCDateTime(strtotime($hasta) * 1000);
        
            $filter["FECHA"] = ['$gte' => $desdeDate, '$lte' => $hastaDate];

        }
 

        $options = [
        'projection' => []
        ]; // Opciones adicionales, como ordenar los resultados, limitarlos, etc.

        $result = $mongoCollection->find($filter, $options);
        $newArray = [];
        foreach ($result as $x => $document) {

        $documentArray = $document->getArrayCopy();

        // Acceder al campo _id
        $id = (string) $documentArray['_id'];

        $newArray[$x]['ID'] = $id;
        // Acceder a los demás campos
        $keys = array_keys($documentArray);

        foreach ($keys as $key) {
            // Saltar el campo _id
            if ($key === '_id') {
                continue;
            }
            $newArray[$x][$key] = $documentArray[$key];

        }

        }

        return ($newArray);

    
    }



}