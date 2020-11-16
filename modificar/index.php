<!doctype HTML>
<html>
<head>
<title>Pedidos Ecommerce Modificar</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body >
<div class="container mt-3">

<table class="table table-dark">
  <thead>
    <tr>
      <th scope="col">OPCION</th>
      <th scope="col">PEDIDO</th>
      <th scope="col">ARTICULO VIEJO</th>
      <th scope="col">ARTICULO NUEVO</th>
      <th scope="col">CANTIDAD</th>
      <th scope="col">CONFIRMAR</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">MODIFICAR</th>
      <td><input type="text" class="form-control" placeholder="Pedido" id="pedidoModificar" onblur="buscarPedido(value)"></td>
      <td><input type="text" class="form-control" placeholder="Articulo viejo" id="articuloViejoModificar"></td>
      <td><input type="text" class="form-control" placeholder="Articulo nuevo" id="articuloNuevoModificar"></td>
      <td><input type="text" class="form-control" id="cantidadModificar" readonly></td>
      <td><button type="button" class="btn btn-info" onClick="modificar()">Modificar</button></td>
    </tr>
    <tr>
      <th scope="row">CANTIDAD</th>
      <td><input type="text" class="form-control" placeholder="Pedido" id="pedidoCantidad" onblur="buscarPedido(value)"></td>
      <td><input type="text" class="form-control" placeholder="Articulo" id="articuloViejoCantidad"></td>
      <td><input type="text" class="form-control" id="articuloNuevoCantidad" readonly></td>
      <td><input type="text" class="form-control" placeholder="Cantidad" id="cantidadCantidad"></td>
      <td><button type="button" class="btn btn-info" onClick="cantidad()">Cantidad</button></td>
    </tr>
    <tr>
      <th scope="row">ELIMINAR</th>
      <td><input type="text" class="form-control" placeholder="Pedido" id="pedidoEliminar" onblur="buscarPedido(value)"></td>
      <td><input type="text" class="form-control" placeholder="Articulo" id="articuloViejoEliminar"></td>
      <td><input type="text" class="form-control" id="articuloNuevoEliminar" readonly></td>
      <td><input type="text" class="form-control" id="cantidadEliminar" readonly></td>
      <td><button type="button" class="btn btn-info" onClick="eliminar()">Eliminar</button></td>
    </tr>
  </tbody>
</table>



<script src="main.js"></script>
</body>
</html>	