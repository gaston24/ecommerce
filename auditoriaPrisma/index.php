<?php

include 'Controlador/controlador.php';
$auditoria = auditoria(10);

?>
<!doctype HTML>
<html>
<head>
<title>Pedidos Ecommerce</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../../../../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">

</head>
<body>
<div class="container">

<h2 class="text-center mt-1 mb-3">Auditoria de Prisma </h2>


<table id="table" class="table table-striped" style="width:66%" align="center">
    <thead>
        <th >E_MAIL</th>
        <th >CANT_PEDIDOS</th>
        <th >CANT_ART</th>
        <th >CANT_DIRECCIONES</th>
        <th >CANT_TARJETA</th>
        <th >PUNTAJE</th>
        <th >TOTAL_PEDI</th>
        <th >PENDIENTES</th>
    </thead>
    <tbody >
        <?php foreach ($auditoria as $key => $value) { ?>
            <tr>
                <td><a href="detalleAuditoria.php?mailClient=<?= $value['E_MAIL']?>"> <?= $value['E_MAIL']?> </a></td>
                <td><?= $value['CANT_PEDIDOS']?></td>
                <td><?= $value['CANT_ART']?></td>
                <td><?= $value['CANT_DIRECCIONES']?></td>
                <td><?= $value['CANT_TARJETA']?></td>
                <td><?= $value['PUNTAJE']?></td>
                <td><?= number_format($value['TOTAL_PEDI'], 0, '', '.')?></td>
                <td><?= number_format($value['CANT'], 0, '', '.')?></td>
                
                
            </tr>
        <?php } ?>
    </tbody>
</table>
    




</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="Controlador/main.js"></script>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
</body>

</html>