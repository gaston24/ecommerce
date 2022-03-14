<?php
function new_ml(){
	$dsn = '1 - CENTRAL';
	$user = 'sa';
	$pass = 'Axoft1988';

	$cid=odbc_connect($dsn, $user, $pass);

	if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
	
	
	//////////////TOMA CADA UNO DE LOS PEDIDOS QUE NO ESTAN EN AUDITORIA
	$sqlPedidosNuevos = "
	SELECT 
	*
	FROM [192.168.0.143].emsys_XLEXTRALARGE.DBO.SOF_PEDIDO 
	WHERE CAST(NRO_PEDIDO COLLATE Latin1_General_BIN AS VARCHAR) NOT IN (SELECT NRO_ORDEN_ECOMMERCE FROM SOF_AUDITORIA)
	AND 
	CAST(NRO_PEDIDO AS VARCHAR) IN
	(
	SELECT 
	DISTINCT
	NRO_PEDIDO
	FROM [192.168.0.143].emsys_XLEXTRALARGE.DBO.SOF_DETALLE_PEDIDO 
	WHERE CAST(NRO_PEDIDO COLLATE Latin1_General_BIN AS VARCHAR) 
	NOT IN (SELECT DISTINCT NRO_ORDEN_ECOMMERCE FROM SOF_AUDITORIA)
	AND (CODIGO IS NOT NULL OR CODIGO != '')
	AND [Receiver_Address_Address_Line] not like 'RETIRO%'
	)
	";

	
	// echo '1'.$sqlPedidosNuevos.'<br>';


	ini_set('max_execution_time', 300);
	$result1=odbc_exec($cid,$sqlPedidosNuevos)or die(exit("Error en odbc_exec"));
		
	while($v=odbc_fetch_array($result1)){
		$ordenEcommerce = $v['NRO_PEDIDO'];
		$cliente = $v['First_Name'].' '.$v['Last_Name'];
		$cliente = str_replace("'","''", $cliente);
		$totalPedi = $v['IMPORTE'];
		$codPostal = $v['Receiver_Address_Zip_Code'];
		$direccion = $v['Receiver_Address_Address_Line'].' '.$v['Receiver_Address_Comment'];
		$direccion = substr($direccion, 0, 30);
		$localidad = $v['Receiver_Address_City_Name'];
		$localidad = substr($localidad, 0, 20);
		$dni = $v['Billing_Doc_Number'];
		$telefono = $v['Phone_Number'];
		$sucursal_ml = $v['SUCURSAL_ML'];
		$nro_sucurs = $v['NRO_SUCURS'];
		$full_filment = $v['mercado_envio_full'];
		$Date_Created = $v['Date_Created'];
		
		$transporte = ($v['LOGISTIC_TYPE'] == 'self_service') ? $transporte = '0135' : $transporte = '0136';
		
		$direccion_completa = $direccion.' '.$codPostal.' '.$localidad;

		// echo '2 - '.$direccion_completa.'<br>';
		
		// //////////////MODIFICA NUMERACION
		// $sqlProxNumero =
		// "EXEC SJ_PEDIDOS_PROX";
		// odbc_exec($cid,$sqlProxNumero)or die(exit("Error en odbc_exec"));
		
		
		// //////////////VARIABLE DE NUMERO ACTUAL
		// $sqlNumActual =
		// "SELECT PROXIMO FROM SJ_TEMP_PEDIDOS WHERE TALONARIO = 98";
		
		// ini_set('max_execution_time', 300);
		// $result=odbc_exec($cid,$sqlNumActual)or die(exit("Error en odbc_exec"));
			
		// while($v=odbc_fetch_array($result)){
		// 	$numPedido = ' 00002'.$v['PROXIMO'];
		// }
		
		// //////////////CHEQUEA FULLFILMENT 

		// if($full_filment == 1){
		// 	$depo = '11';
		// }else{
		// 	$depo = '01';
		// }

		// // echo '3 - '.$depo.'<br>';


		// //////////////INSERTAR ENCABEZADO GVA21
		
		// $sqlPedEncabezado =
		// "
		// INSERT INTO GVA21 
		// (
		// FILLER, APRUEBA, CIRCUITO, COD_CLIENT, COD_SUCURS, COD_TRANSP, COD_VENDED, COMENTARIO, COMP_STK, COND_VTA, COTIZ, ESTADO, EXPORTADO, FECHA_APRU, FECHA_ENTR, FECHA_PEDI, HORA_APRUE, ID_EXTERNO, 
		// LEYENDA_1, LEYENDA_2, LEYENDA_3, LEYENDA_4, LEYENDA_5, MON_CTE, N_LISTA, N_REMITO, NRO_O_COMP, NRO_PEDIDO, NRO_SUCURS, ORIGEN, PORC_DESC, REVISO_FAC, REVISO_PRE, REVISO_STK, TALONARIO, TALON_PED, 
		// TOTAL_PEDI, TIPO_ASIEN, MOTIVO, HORA, COD_CLASIF, ID_ASIENTO_MODELO_GV, TAL_PE_ORI, NRO_PE_ORI, FECHA_INGRESO, HORA_INGRESO, USUARIO_INGRESO,TERMINAL_INGRESO, FECHA_ULTIMA_MODIFICACION, 
		// HORA_ULTIMA_MODIFICACION, USUA_ULTIMA_MODIFICACION, TERM_ULTIMA_MODIFICACION, ID_DIRECCION_ENTREGA, ES_PEDIDO_WEB, FECHA_O_COMP, ACTIVIDAD_COMPROBANTE_AFIP, ID_ACTIVIDAD_EMPRESA_AFIP, 
		// TIPO_DOCUMENTO_PAGADOR, NUMERO_DOCUMENTO_PAGADOR, WEB_ORDER_ID, NRO_OC_COMP, USUARIO_TIENDA, TIENDA, ORDER_ID_TIENDA, 
		// TOTAL_DESC_TIENDA, TIENDA_QUE_VENDE, PORCEN_DESC_TIENDA, USUARIO_TIENDA_VENDEDOR, ID_NEXO_PEDIDOS_ORDEN
		// )
		// 	SELECT 
		// 	'', '', 1, '000000', '$depo', '$transporte', 'ZZ', '', 1, 1, 1, 2, 0, '1800-01-01', '1800-01-01', '$Date_Created', '', '$ordenEcommerce', 
		// 	'', '$cliente', 'ML - CARGADO DESDE APP', '$sucursal_ml', '', 1, 20, ' 0000000000000', '', '$numPedido', 0, 'E', 0, 'A', 'A', 'A', 0, 98,
		// 	$totalPedi, 'FD', '', '', '', 3, 0, '', '1800-01-01', '', '', '', '1800-01-01',
		// 	'', '', '', '', 0, '1800-01-01', '', NULL, 
		// 	0, '', 0, '', '', '', '',  
		// 	0, '', 0, '', ''
		// 	WHERE '$numPedido' NOT IN 
		// 	(
		// 	SELECT DISTINCT ID_EXTERNO FROM GVA21 WHERE TALON_PED = 98 AND FECHA_PEDI >= GETDATE()-15
		// 	)
		// ";

		// odbc_exec($cid,$sqlPedEncabezado)or die(exit("Error en odbc_exec"));
		// // echo $sqlPedEncabezado.'<hr>';

		// // echo '4 - '.$sqlPedEncabezado.'<br>';
		
		
		
		// ////////////// RECORRER DETALLE DE PEDIDO PARA INSERTAR DETALLE

		$sqlPedidoDetalle =
		"
		SELECT * FROM
		(
			SELECT NRO_PEDIDO, Date_Created, First_Name+Last_Name RAZON_SOCIAL, CODIGO, Item_Title, Quantity, Unit_Price 
			FROM [192.168.0.143].emsys_XLEXTRALARGE.DBO.SOF_DETALLE_PEDIDO 
			WHERE CAST(NRO_PEDIDO COLLATE Latin1_General_BIN AS VARCHAR) NOT IN
			(SELECT DISTINCT NRO_ORDEN_ECOMMERCE FROM SOF_AUDITORIA)
			AND (CODIGO IS NOT NULL OR CODIGO != '')
		)A
		WHERE NRO_PEDIDO = '$ordenEcommerce'
		
		
		// ";

		// // echo '5 - '.$sqlPedidoDetalle.'<br>';
		// $resultPedidoDetalle=odbc_exec($cid,$sqlPedidoDetalle)or die(exit("Error en odbc_exec"));
		
		// $renglonPedido = 1;
		
		// while($v=odbc_fetch_array($resultPedidoDetalle)){
		// 	$codArt = substr($v['CODIGO'],0, 15);
		// 	$cantArt = $v['Quantity'];
		// 	$precioArt = $v['Unit_Price'];
			
			
		// 	////////////// INSERTAR DETALLE DE PEDIDO GVA03
			
		// 	$sqlDetalle = 
		// 	"
		// 	INSERT INTO GVA03
		// 	(
		// 	FILLER, CAN_EQUI_V, CANT_A_DES, CANT_A_FAC, CANT_PEDID, CANT_PEN_D, CANT_PEN_F, COD_ARTICU, DESCUENTO, N_RENGLON, NRO_PEDIDO, PEN_REM_FC, PEN_FAC_RE, PRECIO, TALON_PED, COD_CLASIF, CANT_A_DES_2, 
		// 	CANT_A_FAC_2, CANT_PEDID_2, CANT_PEN_D_2, CANT_PEN_F_2, PEN_REM_FC_2, PEN_FAC_RE_2, ID_MEDIDA_VENTAS, ID_MEDIDA_STOCK_2,ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, COD_ARTICU_KIT, RENGL_PADR, 
		// 	PROMOCION,PRECIO_ADICIONAL_KIT, KIT_COMPLETO, INSUMO_KIT_SEPARADO, PRECIO_LISTA, PRECIO_BONIF, DESCUENTO_PARAM, PRECIO_FECHA, FECHA_MODIFICACION_PRECIO, USUARIO_MODIFICACION_PRECIO, 
		// 	TERMINAL_MODIFICACION_PRECIO, ID_NEXO_PEDIDOS_RENGLON_ORDEN
		// 	)
		// 	VALUES
		// 	(
		// 	'', 1, $cantArt, $cantArt, $cantArt, $cantArt, $cantArt, '$codArt', 0, $renglonPedido, '$numPedido', 0, 0, $precioArt, 98, '', 0, 
		// 	0, 0, 0, 0, 0, 0, 7, NULL, 7, 'V', '', 0, 
		// 	0, 0, 0, 0, 0, 0, 0, '1800-01-01', '1800-01-01', '', 
		// 	'', NULL
		// 	)
		// 	";
		// 	// echo '6 - '.$sqlDetalle.'<br>';
		// 	odbc_exec($cid,$sqlDetalle)or die(exit("Error en odbc_exec"));
		// 	// echo $sqlDetalle.'<hr>';
		// 	$renglonPedido++;
			
		// }
		
		// ////////////// INSERTAR DATOS DEL CLIENTE GVA38
		
		// $sqlDatosCliente = 
		// "
		// SET DATEFORMAT YMD
		
		// INSERT INTO GVA38
		// (
		// FILLER, ALI_ADI_IB, ALI_FIJ_IB, ALI_NOCATE, AL_FIJ_IB3, COD_PROVIN, C_POSTAL, DOMICILIO, E_MAIL, IB_L, IB_L3, II_D, II_IB3, II_L,  IVA_D, IVA_L, LOCALIDAD, N_COMP, N_CUIT, N_ING_BRUT, N_IVA, 
		// PORC_EXCL, RAZON_SOCI, SOBRE_II, SOBRE_IVA, TALONARIO, TELEFONO_1, TELEFONO_2, TIPO, TIPO_DOC, T_COMP, DESTINO_DE, CLA_IMP_CL, RECIBE_DE, AUT_DE, WEB, COD_RUBRO, CTA_CLI, CTO_CLI, IDENTIF_AFIP, 
		// DIRECCION_ENTREGA, CIUDAD_ENTREGA, COD_PROVINCIA_ENTREGA, LOCALIDAD_ENTREGA, CODIGO_POSTAL_ENTREGA, TELEFONO1_ENTREGA, TELEFONO2_ENTREGA, ID_CATEGORIA_IVA, CONSIDERA_IVA_BASE_CALCULO_IIBB, 
		// CONSIDERA_IVA_BASE_CALCULO_IIBB_ADIC, MAIL_DE, FECHA_NACIMIENTO, SEXO
		// )
		// VALUES
		// (
		// '', 0, 0, 0, 0, '02', '$codPostal', '$direccion', '', 'N', 0, 'N', 0, 'N', 'N', 'S', '$localidad', '$numPedido', '$dni', '', '',
		// 0, '$cliente', 'N', 'N', 98, '$telefono', '', '', 96, 'PED', 'T', '', 0, 0, '', '', 0, '', '', 
		// '$direccion', '$localidad', '02', '$localidad', '$codPostal', '$telefono', '', 2, 'N', 'N', NULL, '1800-01-01', NULL
		// )
		// ";
		// // echo '7 - '.$sqlDatosCliente.'<br>';
		// odbc_exec($cid,$sqlDatosCliente)or die(exit("Error en odbc_exec"));
		// // echo $sqlDatosCliente.'<hr>';
		
		////////////// INSERTAR DATOS EN AUDITORIA
		
		$sqlAuditoria =
		"
		SET DATEFORMAT YMD
		
		INSERT INTO SOF_AUDITORIA
		(
		ORIGEN, NRO_ORDEN_ECOMMERCE, NRO_PEDIDO, FECHA_PEDIDO, RAZON_SOCIAL, COD_ARTICULO, DESCRIPCION, CANTIDAD_A_FACTURAR, 
		TOTAL_PEDIDO, NOMBRE_MEDIO_PAGO, IMPORTE_PAGO, OBSERV_AUDITORIA, 
		NRO_COMP, NRO_TRACKING, OBSERV_TRACKING, ID_MERCADOPAGO, LOCAL_ENTREGA, LOCAL_ML, FULL_FILMENT, LOGISTIC_TYPE
		)
		SELECT 
		'ML', NRO_PEDIDO, '$ordenEcommerce', Date_Created, '$cliente', CODIGO, LEFT(Item_Title, 30), Quantity, Unit_Price, 
		'MERCADOPAGO', Unit_Price, 'MERCADOPAGO', NULL, 0, NULL, NULL, '$sucursal_ml', '$nro_sucurs', MERCADO_ENVIO_FULL, LOGISTIC_TYPE
		FROM [192.168.0.143].emsys_XLEXTRALARGE.DBO.SOF_DETALLE_PEDIDO 
		WHERE CAST(NRO_PEDIDO COLLATE Latin1_General_BIN AS VARCHAR) 
		NOT IN (SELECT NRO_ORDEN_ECOMMERCE FROM SOF_AUDITORIA)
		AND NRO_PEDIDO = '$ordenEcommerce'
		";
		//echo $sqlAuditoria.'<hr>';
		odbc_exec($cid,$sqlAuditoria)or die(exit("Error en odbc_exec"));
		
	}

	// echo '8 - '.$sqlAuditoria.'<br>';

	// $sqlActuaDeposito = "EXEC SJ_ML_PEDIDOS_CAMBIA_DEPOSITO";
	// odbc_exec($cid,$sqlActuaDeposito)or die(exit("Error en odbc_exec"));



}

?>