

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Control Ecommerce</title>
    <?php 
        require_once $_SERVER['DOCUMENT_ROOT']. '/ecommerce/assets/css/css.php';
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 1rem;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08); /* Sombra permanente sutil */
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1); /* Sombra más pronunciada en hover */
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    /* Estilos para NC Promociones */
    .card-promociones .card-header {
        background-color: #ebf5ff;
        border: 1px solid rgba(33, 150, 243, 0.1); /* Borde sutil */
    }
    .card-promociones .card-icon {
        color: #2196f3;
        font-size: 1.8rem;
    }

    /* Estilos para NC Devoluciones */
    .card-devoluciones .card-header {
        background-color: #fff0f3;
        border: 1px solid rgba(233, 30, 99, 0.1);
    }
    .card-devoluciones .card-icon {
        color: #e91e63;
        font-size: 1.8rem;
    }

    /* Estilos para Órdenes sin Integrar */
    .card-ordenes .card-header {
        background-color: #fff3e0;
        border: 1px solid rgba(255, 152, 0, 0.1);
    }
    .card-ordenes .card-icon {
        color: #ff9800;
        font-size: 1.8rem;
    }

    /* Estilos para Pedidos sin Facturar */
    .card-pedidos .card-header {
        background-color: #e8f5e9;
        border: 1px solid rgba(76, 175, 80, 0.1);
    }
    .card-pedidos .card-icon {
        color: #4caf50;
        font-size: 1.8rem;
    }

    .card-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .info-icon {
        color: #757575;
        font-size: 1rem;
        margin-left: 0.5rem;
        cursor: help;
        transition: color 0.2s;
    }
    .info-icon:hover {
        color: #424242;
    }

    .no-data {
        color: #757575;
        font-style: italic;
        font-size: 1.1rem;
        text-align: center;
        padding: 1rem 0;
    }

    .date-info {
        font-size: 0.875rem;
        color: #757575;
        margin-top: 0.5rem;
    }

    .alert-info {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #495057;
    }

    .card-body {
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.05); /* Borde sutil para el body */
        border-top: none;
        border-radius: 0 0 12px 12px;
    }
    .card-flex .card-header {
        background-color: #f3e5f5;
        border: 1px solid rgba(156, 39, 176, 0.1);
    }
    .card-flex .card-icon {
        color: #9c27b0;
        font-size: 1.8rem;
    }

    /* Estilo para el timestamp */
    .text-muted {
        color: #6c757d !important;
    }
    .text-muted i {
        margin-right: 0.5rem;
    }

    .card-facturas .card-icon {
        font-size: 1.8rem;
    }

    .modal-xl {
    max-width: 95%;
    }

    .table {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    #ncrChart {
        height: 400px !important;
    }

    .card-facturas .card-header {
    background-color: #fff8e1;
    border: 1px solid rgba(255, 193, 7, 0.1);
    }
    .card-facturas .card-icon {
        color: #ffc107;
    }

</style>

</head>
<body>
    <?php
    require_once 'Class/Control.php';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    // Inicializar variables
    $ncPromociones = null;
    $ncDevoluciones = null;
    $ordenesSinIntegrar = null;
    $pedidosSinFacturar = null;
    $pedidosFlexCentral = null;
    $facturasSinRemito = null;
    $ultimaActualizacion = new DateTime();
    $error = null;
    
    try {
        $control = new Control();
        $ncPromociones = $control->traerNcPendPromociones();
        $ncDevoluciones = $control->traerNcPendDevoluciones();
        $ordenesSinIntegrar = $control->traerOrdenesSinIntegrar();
        $pedidosSinFacturar = $control->traerPedidosSinFactTiendas();
        $pedidosFlexCentral = $control->traerPedidosFlex();
        $facturasSinRemito = $control->traerFacturasSinRemito();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    ?>

    <div class="container py-4">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                Error: <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <div class="alert alert-info ">
            <h2 class="text-center mb-4">
                <i class="fas fa-chart-line"></i> Tablero de Control Ecommerce
            </h2>
            <!-- Timestamp de última actualización -->
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> 
                        Última actualización: <?php echo $ultimaActualizacion->format('d/m/Y H:i:s'); ?>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- NC Pendientes Promociones -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 card-promociones">
                    <div class="card-header">
                        <i class="fas fa-tags card-icon"></i>
                        <h5 class="card-title mt-2">
                            NC Pendientes por Promociones
                            <i class="fas fa-info-circle info-icon" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Notas de crédito pendientes por promociones aplicadas">
                            </i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($ncPromociones && !empty($ncPromociones->CANT_NC_PROMO)): ?>
                            <p class="card-value"><?php echo htmlspecialchars($ncPromociones->CANT_NC_PROMO); ?></p>
                            <p class="mb-0">Importe: $<?php echo number_format($ncPromociones->IMPORTE_NC, 2); ?></p>
                            <p class="date-info">Desde: <?php echo $ncPromociones->FECHA->format('d/m/Y'); ?></p>
                        <?php else: ?>
                            <p class="no-data">Sin NC pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 card-devoluciones">
                    <div class="card-header">
                        <i class="fas fa-undo card-icon"></i>
                        <h5 class="card-title mt-2">
                            NC Pendientes por Devoluciones
                            <i class="fas fa-info-circle info-icon" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="Notas de crédito pendientes por devoluciones de productos de los últimos 90 días">
                            </i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($ncDevoluciones && !empty($ncDevoluciones->CANT_NC_DEV)): ?>
                            <p class="card-value"><?php echo htmlspecialchars($ncDevoluciones->CANT_NC_DEV); ?></p>
                            <p class="mb-0">Importe: $<?php echo number_format($ncDevoluciones->IMPORTE_PEND, 2); ?></p>
                            <p class="date-info">Desde: <?php echo $ncDevoluciones->FECHA->format('d/m/Y'); ?></p>
                            <button type="button" class="btn btn-outline-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#modalNcr">
                                <i class="fas fa-chart-bar me-2"></i>Ver Estadísticas
                            </button>
                        <?php else: ?>
                            <p class="no-data">Sin devoluciones pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal para el gráfico -->
            <div class="modal fade" id="modalNcr" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-chart-bar"></i> Notas de Crédito por Día
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="ncrChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Órdenes sin Integrar -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 card-ordenes">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle card-icon"></i>
                        <h5 class="card-title mt-2">
                            Órdenes sin Integrar en Tango
                            <i class="fas fa-info-circle info-icon" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Órdenes pendientes de integración al sistema tango">
                            </i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($ordenesSinIntegrar && !empty($ordenesSinIntegrar->CANT_ORDENES)): ?>
                            <p class="card-value"><?php echo htmlspecialchars($ordenesSinIntegrar->CANT_ORDENES); ?></p>
                            <p class="mb-0">Total: $<?php echo number_format($ordenesSinIntegrar->TOTAL_ORDEN, 2); ?></p>
                            <p class="date-info">Desde: <?php echo $ordenesSinIntegrar->FECHA_ORDEN->format('d/m/Y H:i'); ?></p>
                        <?php else: ?>
                            <p class="no-data">Sin órdenes pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pedidos sin Facturar -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 card-pedidos">
                    <div class="card-header">
                        <i class="fas fa-file-invoice card-icon"></i>
                        <h5 class="card-title mt-2">
                            Pedidos sin Facturar Tiendas
                            <i class="fas fa-info-circle info-icon" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Pedidos con más de 30 minutos desde su ingreso en tango pendientes de facturación">
                            </i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($pedidosSinFacturar !== null && $pedidosSinFacturar->TOTAL_PEDIDOS !== null): ?>
                            <p class="card-value"><?php echo htmlspecialchars($pedidosSinFacturar->CANT_PED_SIN_FACT); ?></p>
                            <p class="mb-0">Total: $<?php echo number_format($pedidosSinFacturar->TOTAL_PEDIDOS, 2); ?></p>
                            <?php if ($pedidosSinFacturar->FECHA_PEDI): ?>
                                <p class="date-info">Desde: <?php echo $pedidosSinFacturar->FECHA_PEDI->format('d/m/Y H:i'); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="no-data">Sin pedidos pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila para la card de Flex -->
        <div class="row mt-3">
            <!-- Card de Flex -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 card-flex">
                    <div class="card-header">
                        <i class="fas fa-truck-fast card-icon"></i>
                        <h5 class="card-title mt-2">
                            Pedidos Pend. de Despacho Flex Central
                            <i class="fas fa-info-circle info-icon" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="Pedidos Flex pendientes de despacho en Depósito Central. Para el día actual se consideran los que ingresan antes de las 12 hs.">
                            </i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($pedidosFlexCentral !== null && $pedidosFlexCentral->CANT_PED_PEND !== null): ?>
                            <p class="card-value"><?php echo htmlspecialchars($pedidosFlexCentral->CANT_PED_PEND); ?></p>
                            <p class="mb-0">Total: $<?php echo number_format($pedidosFlexCentral->TOTAL_PEDIDOS, 2); ?></p>
                            <?php if ($pedidosFlexCentral->FECHA_PEDI): ?>
                                <p class="date-info">Desde: <?php echo $pedidosFlexCentral->FECHA_PEDI->format('d/m/Y H:i'); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="no-data">Sin pedidos pendientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        
        <!-- Card de Facturas sin remito -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 card-facturas">
                <div class="card-header">
                    <i class="fas fa-file-invoice-dollar card-icon"></i>
                    <h5 class="card-title mt-2">
                        Facturas de Tiendas sin Remito
                        <i class="fas fa-info-circle info-icon" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Facturas pendientes de asociar con remito">
                        </i>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($facturasSinRemito !== null && $facturasSinRemito->CANT_FACTURAS !== null): ?>
                        <p class="card-value"><?php echo htmlspecialchars($facturasSinRemito->CANT_FACTURAS); ?></p>
                        <p class="mb-0">Total: $<?php echo number_format($facturasSinRemito->IMPORTE, 2); ?></p>
                        <p class="date-info">Desde: <?php echo $facturasSinRemito->FECHA_FACTURA->format('d/m/Y H:i'); ?></p>
                        <div class="mt-3">
                        <button type="button" class="btn btn-outline-warning w-100 mt-2" data-bs-toggle="modal" data-bs-target="#modalFacturasDetalle">
                            <i class="fas fa-list-ul me-2"></i>Ver Detalle
                        </button>
                        </div>
                    <?php else: ?>
                        <p class="no-data">Sin facturas pendientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                
        </div>    
    </div>

    <!-- Modal para el detalle de facturas -->
        <div class="modal fade" id="modalFacturasDetalle" tabindex="-1" aria-labelledby="modalFacturasDetalleLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFacturasDetalleLabel">
                            <i class="fas fa-file-invoice-dollar"></i> Detalle de Facturas sin Remito
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sucursal</th>
                                        <th>Fecha</th>
                                        <th>Factura</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th class="text-end">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $detalleFacturas = $control->traerDetalleFacturasSinRemito();
                                    if (!empty($detalleFacturas)):
                                        foreach ($detalleFacturas as $detalle): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($detalle->SUCURSAL); ?></td>
                                                <td><?php echo $detalle->FECHA_FACTURA->format('d/m/Y H:i'); ?></td>
                                                <td><?php echo htmlspecialchars($detalle->FACTURA); ?></td>
                                                <td><?php echo htmlspecialchars($detalle->COD_ARTICU); ?></td>
                                                <td><?php echo htmlspecialchars($detalle->DESC_CTA_ARTICULO); ?></td>
                                                <td class="text-end"><?php echo number_format($detalle->CANTIDAD, 0); ?></td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No hay datos para mostrar</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Auto refresh cada 5 minutos -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico
            const chartData = {
                labels: [<?php 
                    $ncrData = $control->traerNcrRealizadas();
                    if (!empty($ncrData)) {
                        echo implode(',', array_map(function($row) {
                            return "'" . $row->FECHA_EMIS->format('d/m/Y') . "'";
                        }, $ncrData));
                    }
                ?>],
                values: [<?php 
                    if (!empty($ncrData)) {
                        echo implode(',', array_map(function($row) {
                            return $row->CANT_NCR;
                        }, $ncrData));
                    }
                ?>]
            };

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // Inicializar el gráfico cuando se abre el modal
            const modalNcr = document.getElementById('modalNcr');
            modalNcr.addEventListener('shown.bs.modal', function () {
                const ctx = document.getElementById('ncrChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Cantidad de NC',
                            data: chartData.values,
                            backgroundColor: '#e91e63',
                            borderColor: '#c2185b',
                            borderWidth: 1,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Notas de Crédito de los últimos 7 días',
                                font: { size: 16 }
                            },
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            }
                        }
                    }
                });
            });

            // Auto refresh
            setTimeout(function() {
                window.location.reload();
            }, 300000);
        });

    </script>
</body>
</html>