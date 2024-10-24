
<?php

require_once 'Class/Conexion.php';
require_once 'Class/Pedido.php';
$pedidos = new Pedido();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Pedidos E-commerce</title>
    <link rel="shortcut icon" href="assets/icono.ico" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style/consultaPedido.css">
  
</head>
<body>
    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Seguimiento de Pedidos E-commerce
                </h4>
            </div>
            <div class="card-body">
                <!-- Formulario de búsqueda -->
                <form method="POST" class="search-container mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" name="desde" class="form-control" 
                                    value="<?php echo isset($_POST['desde']) ? $_POST['desde'] : date('Y-m-d', strtotime('-30 days')); ?>"
                                    max="<?php echo date('Y-m-d'); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date" name="hasta" class="form-control" 
                                    value="<?php echo isset($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d'); ?>"
                                    max="<?php echo date('Y-m-d'); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="numero" class="form-control" 
                                    placeholder="Ingrese Número de Orden o Pedido" 
                                    value="<?php echo isset($_POST['numero']) ? htmlspecialchars($_POST['numero']) : ''; ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                        <div id="spinner" class="spinner-wrapper spinner-hidden">
                            <div class="spinner-dots">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['numero'])) {
                    $numero = trim($_POST['numero']);
                    $desde = isset($_POST['desde']) ? $_POST['desde'] : date('Y-m-d', strtotime('-30 days'));
                    $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
                    
                    $resultado = $pedidos->buscarPedido($desde, $hasta, $numero);
                    
                    if ($resultado && !empty($resultado)) {
                        foreach($resultado as $row) {
                            $pedido = $row[0]; // Accedemos al objeto dentro del array
                ?>
                            <!-- Información del Pedido -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-label">Fecha y Hora</div>
                                            <div class="info-value">
                                                <?php 
                                                echo $pedido->FECHA_PEDIDO instanceof DateTime ? 
                                                    $pedido->FECHA_PEDIDO->format('d/m/Y') : date('d/m/Y', strtotime($pedido->FECHA_PEDIDO));
                                                echo ' ' . $pedido->HORA;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Marketplace</div>
                                            <div class="info-value"><?php echo $pedido->MARKETPLACE; ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Nro. Pedido</div>
                                            <div class="info-value d-flex align-items-center">
                                                <?php echo $pedido->NRO_PEDIDO; ?>
                                                <?php if ($pedido->CANCELADO == 1): ?>
                                                    <i class="bi bi-x-circle-fill ms-2 text-danger icon-state" data-bs-toggle="tooltip" title="Pedido Cancelado"></i>
                                                <?php endif; ?>
                                                <?php if ($pedido->INCOMPLETO == 1): ?>
                                                    <i class="fas fa-exclamation-triangle ms-2 text-warning icon-state" data-bs-toggle="tooltip" title="Pedido Incompleto"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Nro. Orden</div>
                                            <div class="info-value"><?php echo $pedido->NRO_ORDEN; ?></div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="info-label">Cliente</div>
                                            <div class="info-value"><?php echo $pedido->CLIENTE; ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Dirección de Entrega</div>
                                            <div class="info-value"><?php echo $pedido->DIRECCION_ENTREGA; ?></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Lugar de Entrega</div>
                                            <div class="info-value"><?php echo $pedido->LUGAR_ENTREGA; ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Prepara</div>
                                            <div class="info-value"><?php echo $pedido->PREPARA; ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Método de Envío</div>
                                            <div class="info-value"><?php echo $pedido->METODO_ENVIO; ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-label">Sucursal Entrega</div>
                                            <div class="info-value"><?php echo $pedido->SUCURSAL_ENTREGA; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline de Estados -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Estado del Pedido</h5>
                                    <div class="timeline">
                                        <div class="row timeline-row">
                                            <div class="col timeline-step">
                                                <div class="timeline-icon <?php echo $pedido->SINCRONIZADO ? 'active' : ''; ?>">
                                                    <i class="fas fa-sync-alt icon"></i>
                                                </div>
                                                <div>Sincronizado</div>
                                                <div class="timeline-date">
                                                    <?php 
                                                    echo $pedido->FECHA_SINCRONIZADO ? 
                                                        ($pedido->FECHA_SINCRONIZADO instanceof DateTime ? 
                                                            $pedido->FECHA_SINCRONIZADO->format('d/m/Y H:i') : 
                                                            date('d/m/Y H:i', strtotime($pedido->FECHA_SINCRONIZADO))) : 
                                                        'Pendiente'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col timeline-step">
                                                <div class="timeline-icon <?php echo $pedido->FACTURADO ? 'active' : ''; ?>">
                                                    <i class="fas fa-file-invoice icon"></i>
                                                </div>
                                                <div>Facturado</div>
                                                <div class="timeline-date">
                                                    <?php 
                                                    echo $pedido->FECHA_FACTURADO ? 
                                                        ($pedido->FECHA_FACTURADO instanceof DateTime ? 
                                                            $pedido->FECHA_FACTURADO->format('d/m/Y H:i') : 
                                                            date('d/m/Y H:i', strtotime($pedido->FECHA_FACTURADO))) : 
                                                        'Pendiente'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col timeline-step">
                                                <div class="timeline-icon <?php echo $pedido->CONTROLADO ? 'active' : ''; ?>">
                                                    <i class="fas fa-clipboard-check icon"></i>
                                                </div>
                                                <div>Controlado</div>
                                                <div class="timeline-date">
                                                    <?php 
                                                    echo $pedido->FECHA_CONTROLADO ? 
                                                        ($pedido->FECHA_CONTROLADO instanceof DateTime ? 
                                                            $pedido->FECHA_CONTROLADO->format('d/m/Y H:i') : 
                                                            date('d/m/Y H:i', strtotime($pedido->FECHA_CONTROLADO))) : 
                                                        'Pendiente'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col timeline-step">
                                                <div class="timeline-icon <?php echo $pedido->DESPACHADO ? 'active' : ''; ?>">
                                                    <i class="fas fa-truck icon"></i>
                                                </div>
                                                <div>Despachado</div>
                                                <div class="timeline-date">
                                                    <?php 
                                                    echo $pedido->FECHA_DESPACHADO ? 
                                                        ($pedido->FECHA_DESPACHADO instanceof DateTime ? 
                                                            $pedido->FECHA_DESPACHADO->format('d/m/Y H:i') : 
                                                            date('d/m/Y H:i', strtotime($pedido->FECHA_DESPACHADO))) : 
                                                        'Pendiente'; 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col timeline-step">
                                                <div class="timeline-icon <?php echo $pedido->ENTREGADO ? 'active' : ''; ?>">
                                                    <i class="fas fa-check icon"></i>
                                                </div>
                                                <div>Entregado</div>
                                                <div class="timeline-date">
                                                    <?php 
                                                    echo $pedido->FECHA_ENTREGADO ? 
                                                        ($pedido->FECHA_ENTREGADO instanceof DateTime ? 
                                                            $pedido->FECHA_ENTREGADO->format('d/m/Y H:i') : 
                                                            date('d/m/Y H:i', strtotime($pedido->FECHA_ENTREGADO))) : 
                                                        'Pendiente'; 
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Después del card del timeline, agregar: -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Detalle del Pedido</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%">Producto</th>
                                                    <th style="width: 20%" class="text-end">Precio</th>
                                                    <th style="width: 10%" class="text-end">Cant.</th>
                                                    <th style="width: 20%" class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                $detalles = $pedidos->buscarDetallePedido($desde, $hasta, $numero);
                                                if ($detalles) {
                                                    foreach($detalles as $detalle) {
                                                        $item = $detalle[0];
                                                        $subtotal = $item->CANT_PEDID * $item->IMPORTE;
                                                        $total += $subtotal;

                                                        // Lógica para las imágenes
                                                        $imageName = substr($item->COD_ARTICU, 0, 13);
                                                        $imageUrl = file_exists("../Imagenes/".$imageName.".jpg") ? 
                                                                "../Imagenes/".$imageName.".jpg" : "";
                                                        
                                                        // Verificar si es SALE
                                                        $isSale = (substr($item->DESCRIPCIO, -11) == '-- SALE! --');
                                                        $description = $isSale ? substr($item->DESCRIPCIO, 0, -11) : $item->DESCRIPCIO;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?php echo $imageUrl ? $imageUrl : '/api/placeholder/50/50'; ?>" 
                                                                alt="<?php echo $imageUrl ? $item->COD_ARTICU : 'Sin imagen'; ?>"
                                                                class="product-image me-3"
                                                                style="width: 50px; height: 50px; object-fit: contain; cursor: pointer;"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#imageModal<?php echo $imageName; ?>">
                                                            <div>
                                                                <div class="fw-bold text-primary">
                                                                    <?php echo $description; ?>
                                                                    <?php if ($isSale): ?>
                                                                        <span class="badge bg-danger">SALE</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="text-muted">
                                                                    <small>Código: <?php echo $item->COD_ARTICU; ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">$ <?php echo number_format($item->IMPORTE, 2, ',', '.'); ?></td>
                                                    <td class="text-end"><?php echo $item->CANT_PEDID; ?></td>
                                                    <td class="text-end">$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                                    <td class="text-end fw-bold">$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Modales para las imágenes -->
                            <?php
                            if ($detalles) {
                                foreach($detalles as $detalle) {
                                    $item = $detalle[0];
                                    $imageName = substr($item->COD_ARTICU, 0, 13);
                                    $imageUrl = file_exists("../Imagenes/".$imageName.".jpg") ? 
                                            "../Imagenes/".$imageName.".jpg" : "";
                            ?>
                            <div class="modal fade" id="imageModal<?php echo $imageName; ?>" tabindex="-1" 
                                aria-labelledby="imageModalLabel<?php echo $imageName; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel<?php echo $imageName; ?>">
                                                <?php echo $item->DESCRIPCIO; ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="<?php echo $imageUrl; ?>" 
                                                alt="<?php echo $imageName; ?>.jpg - imagen no encontrada" 
                                                class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            ?>

                <?php
                        }
                    } else {
                        echo '<div class="alert alert-warning">No se encontraron resultados para la búsqueda.</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        // Inicializar todos los tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Función para mostrar el spinner
        function showSpinner() {
            document.getElementById('spinner').classList.remove('spinner-hidden');
        }

        // Función para ocultar el spinner
        function hideSpinner() {
            document.getElementById('spinner').classList.add('spinner-hidden');
        }

        // Modificar el formulario para mostrar el spinner
        document.querySelector('form').addEventListener('submit', function(e) {
            showSpinner();
        });

        // Si hay error en la búsqueda, ocultar el spinner
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.alert-warning')) {
                hideSpinner();
            }
        });

        // Ocultar el spinner cuando la página termina de cargar
        window.addEventListener('load', function() {
            hideSpinner();
        });

    </script>

</body>
</html>