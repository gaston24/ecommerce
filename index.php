<?php
require_once 'Class/Pedido.php';

$pedidos = new Pedido();

actua_comprobante();
actua_despacho();
remitos_buscar_once();

if(!isset($_GET['desde'])){
	nc_pendientes();
}

$hoy = date("Y-m-d");
$tienda = (!isset($_GET['tienda'])) ? '%' : '%'.$_GET['tienda'].'%';
$desde = (!isset($_GET['desde'])) ? $hoy : $_GET['desde'];
$hasta = (!isset($_GET['hasta'])) ? $hoy : $_GET['hasta'];

?>

<!doctype HTML>

<html lang="en">
<head>
<title>XL Extralarge - Inicio</title>	
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="assets/icono.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css" >
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
<!-- Including Font Awesome CSS from CDN to show icons -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="vista/style.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>

<body>
<div class="container-fluid">

<div class="row mb-2"  id="render">

	<div class="col" id="formulario">

	<form method="GET" action="">
		<div class="row mb-1">
		
		<div class="col-sm-2">
		<label class="col-sm col-form-label">Desde:</label>
			<input type="date" class="form-control form-control-sm" name="desde" value="<?=$desde?>">
		</div>
	  
		<div class="col-sm-2">
		<label class="col-sm col-form-label">Hasta:</label>
			<input type="date" class="form-control form-control-sm" name="hasta" value="<?=$hasta?>">
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

$arrayPedidos = $pedidos->traerPedidos($desde, $hasta, $tienda);

$bandera = 0;
$pedido_viejo = '';
$pedido_nuevo = '';

?>

	</form>  
	
	</div>

	<div class="col-1 mt-4 mr-4">
		<button onclick="filterIncompletos()" id="buttonIncompletos">Incompletos</button>
			<title>Seleccionar todos los pedidos incompletos</title>
		</svg>
	</div>
	
	<!-- <div class="col-2 col-auto mr-auto" id="botones">
		<div class="col mt-4" id="columnita">
			<button class="btn btn-info" onClick="total()">Marcar Imprimir </button>
		</div>
	</div> -->

	
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
		<th width="7%" class="h6">NRO<BR>ORDEN</th>
		<th width="9%" class="h6">FECHA<BR>PEDIDO</th>
		<th width="9%" class="h6">PEDIDO</th>
		<th width="12%" class="h6">NOMBRE</th>
		<th width="9%" class="h6">COD<BR>ARTICU</th>
		<th width="13%" class="h6">DESC<BR>ARTICULO</th>
		<th width="1%" class="h6">CANT</th>
		<th width="1%" class="h6">SEL</th>
		<th width="4%" class="h6">IMPORTE</th>
		<th width="4%" class="h6">MEDIO<BR>PAGO</th>
		<th width="4%" class="h6">NRO<BR>FACT</th>
		<th width="3%" class="h6">METODO<BR>ENVIO</th>
		<th width="4%" class="h6">LOCAL /<BR>FEC GUIA</th>
		<th width="1%" class="h6" style="color: white;">F</th>
		<th width="1%" class="h6" style="color: white;">C</th>
		<th width="1%" class="h6" style="color: white;">I</th>
		<th width="1%" class="h6" style="color: white;">D</th>
	</tr>
</thead>
<tbody id="table">
<?php
$id = 0;
foreach($arrayPedidos as $key => $value){

	$date = date_create($value[0]->FECHA_PEDIDO);
	$date = date_format($date,"Y/m/d H:i");
?>
		<div class="row" >
		

		<?php
			echo '<tr id="tr" style="';
			if($value[0]->FULL_FILMENT==1 ){
				echo 'font-weight:bold;color:#1380ED;';
			}elseif($value[0]->NRO_COMP == ''){
				echo 'font-weight:bold;color:#FE2E2E;';
			}else{
				echo '';
			}
			if($bandera == 0){
				$pedido_viejo = $value[0]->NRO_PEDIDO;
				$bandera = 1;
			}elseif($value[0]->NRO_PEDIDO==$pedido_viejo){
				echo '';
			}else{
				echo ';border-top: 1px solid black;';
				$pedido_viejo = $value[0]->NRO_PEDIDO;
			}

			echo '">';
		?>

			<td width="4%"><?= $value[0]->ORIGEN?></td>
			<td width="7%"> 
			<?php if($value[0]->ORIGEN=='VTEX' ){
				?>
					<a href="https://xlshop.myvtex.com/admin/checkout/#/orders/<?= $value[0]->NRO_ORDEN_ECOMMERCE; ?>" target="_blank">
					<?= $value[0]->NRO_ORDEN_ECOMMERCE?>
					</a>
				<?php
			}else{
			 echo $value[0]->NRO_ORDEN_ECOMMERCE;
			} ?>
			
			</td>


			<td width="9%"><?= $date?></td>
			<td width="9%"><?= $value[0]->NRO_PEDIDO?></td>
			<td width="12%" name="orden_<?php ?>"><small><?= $value[0]->RAZON_SOCIAL?></small></td>
			<td width="9%"><?= $value[0]->COD_ARTICULO;?></td>
			<td width="13%"><small><?= $value[0]->DESCRIPCION;?></small></td>
			<td width="1%" style="text-align: center;"><?= $value[0]->CANTIDAD_A_FACTURAR;?></td>
			<td width="1%" style="text-align: center;"> <input type="checkbox" name="nro_pedido[]" id="nro_orden_<?= $id?>" value="<?= $value[0]->NRO_PEDIDO?>"> </td>
			<td width="4%" style="text-align: center;"><?= '$ '.number_format($value[0]->IMPORTE_PAGO , 0, '', '.')?></td>
			<td width="4%" style="text-align: center;"><?= $value[0]->MEDIO_PAGO;?></td>
			<td width="4%" style="text-align: center;"><small><?= $value[0]->NRO_COMP?></small></td>
			<td width="3%" style="text-align: center;"><small><?= $value[0]->METODO_ENVIO?></small></td>
			<td width="4%" style="text-align: center;"><small><?= $value[0]->LOCAL_ENTREGA?></small></td>
			<td width="1%">
			<?php if( strpos( strtoupper($value[0]->NRO_COMP), 'CANCELADO') <> 0 ){
			?>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-x-fill" viewBox="0 0 16 16" style="color: red" >
				<title>Pedido cancelado</title>
					<path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7.354 5.646 8.5 6.793l1.146-1.147a.5.5 0 0 1 .708.708L9.207 7.5l1.147 1.146a.5.5 0 0 1-.708.708L8.5 8.207 7.354 9.354a.5.5 0 1 1-.708-.708L7.793 7.5 6.646 6.354a.5.5 0 1 1 .708-.708z"/>
				</svg>
			<?php
			}else if($value[0]->FACTURADO== 1){ ?>
				<i class="fas fa-receipt"  title="Pedido facturado" style="color: #007bff; font-size: 20px;"></i>
					<?php }else if($value[0]->FACTURADO== 0){?>
						<i class="fas fa-square" style="color: white; font-size: 20px;">
					<?php } ?>
			</td>
			<td width="1%">
			<?php if($value[0]->CONTROLADO== 1){ ?>
				<i class="fa fa-clipboard-check"  title="Pedido controlado" style="color: green; font-size: 20px;"></i>
					<?php }else if($value[0]->CONTROLADO== 0){?>
						<i class="fas fa-square" style="color: white; font-size: 20px;">
						<?php } ?>
			</td>
			<td width="1%" id="incompleto">
			<?php if($value[0]->PREPARADO== 1){ ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-dash-fill" viewBox="0 0 16 16" style="color: orange">
					<title>Pedido incompleto</title>
				<path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM6.5 7h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1z"/>
				</svg>
				<?php }else if($value[0]->PREPARADO== 0){?>
					<i class="fas fa-square" style="color: white; font-size: 20px;">
					<?php } ?>
			</td>
			<td width="1%">
			<?php if($value[0]->DESPACHADO== 1){ ?>
				<i class="fas fa-truck"  title="Pedido entregado" style="color: #17a2b8; font-size: 17px;"></i>
					<?php }else if($value[0]->DESPACHADO== 0){?>
						<i class="fas fa-square" style="color: white; font-size: 20px;">
						<?php } ?>
			</td>

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