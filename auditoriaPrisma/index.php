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

<link rel="stylesheet" href="css/style.css">

</head>
<body>


<h2 id="title">Auditoria de Prisma </h2>

<div>
    <button class="btn btn-success" id="btnExport">Exportar <i class="fa fa-file-excel-o"></i></button>
</div>

<table id="table" class="table table-striped" style="width:66%">
        <thead>
            <th scope="col" style="width: 3%">E_MAIL</th>
            <th scope="col" style="width: 3%">CANT_PEDIDOS</th>
            <th scope="col" style="width: 3%">CANT_ART</th>
            <th scope="col" style="width: 3%">CANT_DIRECCIONES</th>
            <th scope="col" style="width: 3%">CANT_TARJETAS</th>
            <th scope="col" style="width: 3%">PUNTAJE</th>
            <th scope="col" style="width: 3%">TOTAL_$</th>
            <th scope="col" style="width: 3%">PENDIENTES</th>
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
    
</body>

</html>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- Plugin to export Excel -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<script>

    $(document).ready(() => {
        $("#btnExport").click(function() {
            $("#table").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Detalle Prisma",
                filename: "Detalle auditora Prisma", //do not include extension
                fileext: ".xls" // file extension
            });
        });

    });
</script>