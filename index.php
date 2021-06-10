<?php
require_once 'AccesoDatos/dsn.php';
include 'Controlador/metodos.php';

actua_comprobante();
actua_despacho();
remitos_buscar_once();

if(!isset($_GET['desde'])){
	nc_pendientes();
}

?>

<!doctype HTML>

<html lang="en">
<head>
<title>XL Extralarge - Inicio</title>	
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="assets/icono.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css" >
		<link rel="stylesheet" href="Vista/style.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="style/style.css">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">

<?php
$hoy = date("Y-m-d");
if(!isset($_GET['tienda'])){$tienda = '%';}else{	$tienda = '%'.$_GET['tienda'].'%';}
if(!isset($_GET['estado'])){$estado = '%';}else{	$estado = '%'.$_GET['estado'].'%';}
?>

<div class="row mb-2"  id="render">

	<div class="col" id="formulario">

	<form method="GET" action="">
		<div class="row mb-1">
		
		<div class="col-sm-2">
		<label class="col-sm col-form-label">Desde:</label>
			<input type="date" class="form-control form-control-sm" name="desde" value="<?php if(!isset($_GET['desde'])){	echo $hoy;}else{ echo $_GET['desde'] ;}?>">
		</div>
	  
		<div class="col-sm-2">
		<label class="col-sm col-form-label">Hasta:</label>
			<input type="date" class="form-control form-control-sm" name="hasta" value="<?php if(!isset($_GET['hasta'])){	echo $hoy;}else{ echo $_GET['hasta'] ;}?>">
		</div>
				
		<div class="col-sm-2" id="gaston">
		<label class="col-sm col-form-label">Tienda:</label>
			<select class="form-control form-control-sm" name="tienda">
			<option selected></option>
			<option value="vtex">VTEX</option>
			<option value="ml">MERCADO LIBRE</option>
			<option value="d">DAFITI</option>
			<option value="fot">FOTTER</option>
			</select >
		</div>
		<?php 
		if(isset($_GET['desde'])){			
		?>
		<div class="col-sm-2" id="busqueda" style="display:none">
		<label class="col-sm col-form-label">Busqueda Rapida:</label>
			<input type="text" class="form-control form-control-sm" onkeyup="busquedaRapida()" onkeypress = "return pulsar(event)" id="textBox" name="factura" placeholder="Sobre cualquier campo.." autofocus>
		</div>
		<?php 
		}
		?>
		
		<div class="col-sm-1 pt-4">
			<input type="submit" class="btn btn-primary" value="Buscar">
		</div>
	  
	  </div>
	  
	  
	  


<?php
if(isset($_GET['desde'])){
$desde =  $_GET['desde'];
$hasta =  $_GET['hasta'];

require_once 'AccesoDatos/conexion.php';

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

//echo $factura;
$bandera = 0;
$pedido_viejo = '';
$pedido_nuevo = '';

?>

	</form>  
	
	</div>
	
	<div class="col-2 col-auto mr-auto" id="botones">
		<div class="col mt-4" id="columnita">
			<button class="btn btn-info" onClick="total()">Marcar Imprimir </button>
		</div>
	</div>

	
</div>



<div style="width:100%; padding-bottom:5%; margin-bottom:5%" >
<div >
<a id="prueba">

</a>
</div>

<table class="table table-hover table-condensed table-fh table-14c" id="id_tabla">
<thead>
	<tr>
		<th width="4%" class="h6">TIENDA</th>
		<th width="9%" class="h6">NRO<BR>ORDEN</th>
		<th width="9%" class="h6">FECHA<BR>PEDIDO</th>
		<th width="9%" class="h6">PEDIDO</th>
		<th width="12%" class="h6">NOMBRE</th>
		<th width="9%" class="h6">COD<BR>ARTICU</th>
		<th width="12%" class="h6">DESC<BR>ARTICULO</th>
		<th width="4%" class="h6">CANT</th>
		<th width="3%" class="h6">SEL</th>
		<th width="6%" class="h6">IMPORTE</th>
		<th width="6%" class="h6">MEDIO<BR>PAGO</th>
		<th width="6%" class="h6">NRO<BR>FACT</th>
		<th width="6%" class="h6">LOCAL /<BR>FEC GUIA</th>
		<th width="6%" class="h6">ESTADO</th>
	</tr>
</thead>
<tbody id="table">
<?php
$id = 0;
while($v=odbc_fetch_array($result)){
?>
		<div class="row" >
		

		<?php
			echo '<tr id="tr" style="';
			if($v['FULL_FILMENT']==1 ){
				echo 'font-weight:bold;color:#1380ED;';
			}elseif($v['NRO_COMP'] == ''){
				echo 'font-weight:bold;color:#FE2E2E;';
			}else{
				echo '';
			}
			if($bandera == 0){
				$pedido_viejo = $v['NRO_PEDIDO'];
				$bandera = 1;
			}elseif($v['NRO_PEDIDO']==$pedido_viejo){
				echo '';
			}else{
				echo ';border-top: 1px solid black;';
				$pedido_viejo = $v['NRO_PEDIDO'];
			}
			if($v['ART_A_GESTIONAR']==1){
				echo 'background-color:#E4FF00';
			}
			echo '">';
		?>

			<td width="4%"><?= $v['ORIGEN']?></td>
			<td width="9%"> 
			<?php if($v['ORIGEN']=='VTEX' ){
				?>
					<a href="https://xlshop.myvtex.com/admin/checkout/#/orders/<?= $v['NRO_ORDEN_ECOMMERCE']; ?>" target="_blank">
					<?= $v['NRO_ORDEN_ECOMMERCE']?>
					</a>
				<?php
			}else{
			 echo $v['NRO_ORDEN_ECOMMERCE'];
			} ?>
			
			</td>


			<td width="9%"><?= $v['FECHA_PEDIDO']?></td>
			<td width="9%"><?= $v['NRO_PEDIDO']?></td>
			<td width="12%" name="orden_<?php ?>"><small><?= $v['RAZON_SOCIAL']?></small></td>
			<td width="9%"><?= $v['COD_ARTICULO'];?></td>
			<td width="12%"><small><?= $v['DESCRIPCION'];?></small></td>
			<td width="4%"><?= $v['CANTIDAD_A_FACTURAR'];?></td>
			<td width="3%"> <input type="checkbox" name="nro_pedido[]" id="nro_orden_<?= $id?>" value="<?= $v['NRO_PEDIDO']?>"> </td>
			<td width="6%"><?= '$ '.number_format($v['IMPORTE_PAGO'] , 0, '', '.')?></td>
			<td width="6%"><?= $v['MEDIO_PAGO'];?></td>
			<td width="6%"><small><?= $v['NRO_COMP']?></small></td>
			<td width="6%"><small><?= $v['LOCAL_ENTREGA']?></small></td>
			<td width="6%"><small><?= $v['ESTADO']?></small></td>
			
		</tr>
		</div>
<?php
$id++;
}
?>

		

</tbody>

</table>


</div>
<?php
}
?>

  
</div>
<script type="text/javascript" src="Controlador/main.js"></script>


<script src="assets/bootstrap/popper.min.js" ></script>
<script src="assets/bootstrap/bootstrap.min.js" ></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>

</html>	