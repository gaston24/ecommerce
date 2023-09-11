<?php

require_once 'Class/Pedido.php';

$pedidos = new Pedido();

actua_comprobante();
actua_despacho();
remitos_buscar_once();
new_ml();

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
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
			<option value="VTEX">VTEX</option>
			<option value="ML">MERCADO LIBRE</option>
			<option value="DAA">DAFITI</option>
			</select >
		</div>
		<?php 
		if(isset($_GET['desde'])){			
		?>
		<div class="col-sm-2" id="busqueda" style="display:none">
		<label class="col-sm col-form-label">Busqueda:</label>
			<input type="text" class="form-control form-control-sm" onkeyup="busquedaRapida()" onkeypress = "return pulsar(event)" id="textBox" name="factura" placeholder="Sobre cualquier campo.." autofocus>
		</div>
		<?php 
		}
		?>
		
		<div class="col-sm-1 pt-4">
			<input type="submit" class="btn btn-primary btn-buscar" value="Buscar">
		</div>
		<!-- spinner -->
		<div id="boxLoading"></div> 
	  
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

	<div class="col-1 mt-4 mr-1">
		<button onclick="filterPendientes()" id="buttonPendientes">Pendientes</button>
		</svg>
	</div>

	<div class="col-1 mt-4 mr-1">
		<button onclick="filterCancelados()" id="buttonCancelados">Sin NC</button>
		</svg>
	</div>

	<div class="col-1 mt-4 mr-1">
		<button onclick="filterIncompletos()" id="buttonIncompletos">Incompletos</button>
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

<table class="table table-hover table-condensed table-fh" id="id_tabla">
<thead>
	<tr>
		<th style="width: 2%;" class="headerTitle">TIENDA</th>
		<th style="width: 9%;" class="headerTitle">NRO<BR>ORDEN</th>
		<th style="width: 6%;" class="headerTitle">FECHA<BR>PEDIDO</th>
		<th style="width: 6%;" class="headerTitle">HORA<BR>PEDIDO</th>
		<th style="width: 5%;" class="headerTitle">PEDIDO</th>
		<th style="width: 12%;" class="headerTitle">NOMBRE</th>
		<th style="width: 8%;" class="headerTitle">COD<BR>ARTICULO</th>
		<th style="width: 8%;" class="headerTitle">DESC<BR>ARTICULO</th>
		<th style="width: 4%; text-align: left; padding-left: 0px" class="headerTitle">CANT</th>
		<th style="width: 5%;" class="headerTitle">IMPORTE</th>
		<th style="width: 5.5%;" class="headerTitle">NRO<BR>FACT</th>
		<th style="width: 5%;" class="headerTitle">DEPOSITO</th>
		<th style="width: 5%;" class="headerTitle">METODO<BR>ENVIO</th>
		<th style="width: 5%;" class="headerTitle">TIENDA</th>
		<th style="width: 1%; color: white;" class="headerTitle">F</th>
		<th style="width: 1%; color: white;" class="headerTitle">C</th>
		<th style="width: 1%; color: white;" class="headerTitle">I</th>
		<th style="width: 1%; color: white;" class="headerTitle">D</th>
		<th style="width: 1%; color: white;" class="headerTitle">E</th>
	</tr>
</thead>
<tbody id="table">
<?php
$id = 0;
foreach($arrayPedidos as $key => $value){

	$date = date_create($value[0]->FECHA_PEDIDO);
	$date = date_format($date,"Y/m/d");
?>
		<div class="row" >
		

		<?php
			echo '<tr id="tr" style="';
			if($value[0]->NRO_COMP == ''){
				echo 'font-weight:bold; color:#FE2E2E;';
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

			<td style="width: 2%;"><?= $value[0]->ORIGEN?></td>
			<td style="width: 9%;"> 
			<?php if($value[0]->ORIGEN=='VTEX' ){
				?>
					<a href="https://xlshop.myvtex.com/admin/orders/<?= $value[0]->NRO_ORDEN_ECOMMERCE; ?>" target="_blank">
					<?= $value[0]->NRO_ORDEN_ECOMMERCE?>
					</a>
				<?php
			}else{
			 echo $value[0]->NRO_ORDEN_ECOMMERCE;
			} ?>
			
			</td>


			<td style="width: 6%;"><?= $date?></td>
			<td style="width: 5%;"><?= $value[0]->HORA?></td>
			<td style="width: 7%;"><?= $value[0]->NRO_PEDIDO?></td>
			<td style="width: 12%;" name="orden_<?php ?>"><small><?= $value[0]->RAZON_SOCIAL?></small></td>
			<td style="width: 8%;"><?= $value[0]->COD_ARTICULO;?></td>
			<td style="width: 11%;"><small><?= $value[0]->DESCRIPCION;?></small></td>
			<td style="width: 4%;" style="text-align: left;"><?= $value[0]->CANTIDAD_A_FACTURAR;?></td>
			<td style="width: 5%;" ><?= '$ '.number_format($value[0]->IMPORTE_PAGO , 0, '', '.')?></td>
			<td style="width: 5%;" style="text-align: center;"><small><?= $value[0]->NRO_COMP?></small></td>
			<td style="width: 5%;" style="text-align: center;"><small><?= $value[0]->WAREHOUSE?></small></td>
			<td style="width: 5%;" style="text-align: center;"><small><?= $value[0]->METODO_ENVIO?></small></td>
			<td style="width: 5%;" style="text-align: center;"><small><?= $value[0]->DESC_SUCURSAL?></small></td>
			<td style="width: 0.1rem;" id="cancelado">
			<?php if($value[0]->CANCELADO == 1 && $value[0]->FACTURADO == 1 && !isset($value[0]->NCR)){?>
				<i class="bi bi-clipboard-x-fill cancelado" data-toggle="tooltip" data-placement="left" title="Pedido cancelado sin NC" style="color: #6610f2; font-size: 20px; padding: 0;"></i>
				<?php
			}else if($value[0]->CANCELADO == 1 && isset($value[0]->NCR)){?>
				<i class="bi bi-clipboard-x-fill" data-toggle="tooltip" data-placement="left" title="Pedido cancelado <?php if(isset($value[0]->NCR)){echo 'NCR '.$value[0]->NCR;}?>" style="color: green; font-size: 20px; padding: 0;"></i>
				<?php
			}else if($value[0]->CANCELADO == 1 ){
			?>
				<i class="bi bi-cart-x-fill" data-toggle="tooltip" data-placement="left" title="Cancelado" style="color: red; font-size: 20px; padding: 0;"></i>
			<?php
			}else if($value[0]->FACTURADO == 1){ ?>
				<i class="bi bi-file-earmark-text-fill" data-toggle="tooltip" data-placement="left"  title="Facturado" style="color: #6c757d; font-size: 20px;"></i>
					<?php }else if($value[0]->FACTURADO == 0){?>
						<i class="fas fa-square pendiente" style="color: white; font-size: 20px;">
					<?php } ?>
			</td>
			<td width="0.1rem">
			<?php if($value[0]->CONTROLADO== 1){ ?>
				<i class="bi bi-clipboard2-check-fill"  data-toggle="tooltip" data-placement="left" title="Controlado" style="color: green; font-size: 20px;"></i>
					<?php }else if($value[0]->CONTROLADO== 0){?>
						<i class="fas fa-square" style="color: white; font-size: 20px;">
						<?php } ?>
			</td>
			<td width="0.1rem" id="incompleto">
			<?php if($value[0]->FALTANTE== 1){ ?>
				<i title="Pedido incompleto" data-toggle="tooltip" data-placement="left" class="bi bi-cart-dash-fill incompleto" style="color: orange; font-size: 20px;"></i>	
				<?php }else if($value[0]->FALTANTE== 0){?>
					<i class="fas fa-square" style="color: white; font-size: 20px;">
					<?php } ?>
			</td>
			<td width="0.1rem">
			<?php if($value[0]->DESPACHADO== 1){ ?>
				<i class="fas fa-truck"  data-toggle="tooltip" data-placement="left" title="Despachado <?= $value[0]->FECHA_DESPACHO?>" style="color: #17a2b8; font-size: 17px;"></i>
					<?php }else if($value[0]->DESPACHADO== 0){?>
						<i class="fas fa-square" style="color: white; font-size: 20px;">
						<?php } ?>
			</td>
			<td width="0.1rem">
				<?php if($value[0]->ENTREGADO == 1){ ?>
					<i class="bi bi-box-seam-fill" data-toggle="tooltip" data-placement="left" title="Entregado <?= $value[0]->FECHA_ENTREGADO?>" style="color: #007bff; font-size: 17px;"></i>
						<?php }else if($value[0]->ENTREGADO == 0){?>
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

<script>

//Spinner listOrdenesActivas.php//
var btn = document.querySelectorAll('.btn-buscar');
    btn.forEach(el => {
        el.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});
    })

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>

</body>

</html>	