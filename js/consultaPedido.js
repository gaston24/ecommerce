
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Variables globales
    let seccionCounter = 1;
    let estadoActual = 'abierto';

    // Inicializar Select2
    $('#selectArticulo').select2({
        width: '100%',
        placeholder: 'Buscar artículo...',
        dropdownParent: $('#historialModal'), // Importante para modales
        language: 'es',
        templateResult: formatArticuloResult,
        templateSelection: formatArticuloSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Funciones para formatear los resultados
    function formatArticuloResult(articulo) {
        if (!articulo.id || !articulo.element) return articulo.text;
        
        const dataset = articulo.element.dataset;
        if (!dataset) return articulo.text;
    
        return `<div class="select2-result-article">
            <strong>${dataset.codigo || ''}</strong>
            ${dataset.descripcion || ''}<br>
            <small>Stock: ${dataset.stock || '0'}</small>
        </div>`;
    }
    
    function formatArticuloSelection(articulo) {
        if (!articulo.id || !articulo.element) return articulo.text;
        
        const dataset = articulo.element.dataset;
        if (!dataset) return articulo.text;
    
        return `${dataset.codigo || ''} - ${dataset.descripcion || ''}`;
    }

    // Control de estados
    function actualizarBadgeEstado() {
        const badge = document.querySelector('.estado-actual');
        if (!badge) return; // Prevenir error si no existe el elemento
    
        // Remover clases existentes de bootstrap
        badge.classList.remove('bg-danger', 'bg-warning', 'bg-success');
        
        switch(estadoActual) {
            case 'abierto':
                badge.classList.add('bg-danger');
                badge.textContent = 'Abierto';
                break;
            case 'proceso':
                badge.classList.add('bg-warning');
                badge.textContent = 'En Proceso';
                break;
            case 'resuelto':
                badge.classList.add('bg-success');
                badge.textContent = 'Resuelto';
                break;
        }
    }

    // Funciones del Spinner
    function showSpinner() {
        document.getElementById('spinner').classList.remove('spinner-hidden');
    }

    function hideSpinner() {
        document.getElementById('spinner').classList.add('spinner-hidden');
    }

    // Función para abrir el historial
    window.abrirHistorial = function(codigo, descripcion, precio, cantidad) {
        // Actualizar información del artículo en el modal
        document.getElementById('modalArticulo').textContent = descripcion;
        document.getElementById('modalCodigo').textContent = `Código: ${codigo}`;
        document.getElementById('modalPrecio').textContent = `$ ${parseFloat(precio).toLocaleString('es-AR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
        document.getElementById('modalCantidad').textContent = cantidad;

        // Reset del estado del modal
        estadoActual = 'abierto';
        actualizarBadgeEstado();

        // Limpiar secciones anteriores
        document.getElementById('seccionesHistorial').innerHTML = '';
        document.getElementById('agregarSeccion').style.display = 'block';
        document.getElementById('seccionResolucion').style.display = 'none';
        document.getElementById('btnResolucion').style.display = 'block';

        // Abrir el modal
        const modal = new bootstrap.Modal(document.getElementById('historialModal'));
        modal.show();
    }

    // Función para guardar sección
    function guardarSeccion(seccionElement) {
        // Obtener valores
        const tipoContacto = seccionElement.querySelector('.tipo-contacto').value;
        const agente = seccionElement.querySelector('.agente').value;
        const comentario = seccionElement.querySelector('.comentario').value;

        // Validar que haya texto en el comentario
        if (!comentario.trim()) {
            alert('Debe ingresar un comentario');
            return;
        }

        // Marcar sección como guardada
        seccionElement.classList.add('seccion-guardada');

        // Deshabilitar campos
        seccionElement.querySelectorAll('select, textarea').forEach(elem => {
            elem.disabled = true;
        });

        // Ocultar botón guardar
        const btnGuardar = seccionElement.querySelector('.btn-guardar-seccion');
        btnGuardar.style.display = 'none';

        // Mostrar botón agregar sección
        document.getElementById('agregarSeccion').style.display = 'block';

        // Si es la primera sección, cambiar a "en proceso"
        if (estadoActual === 'abierto') {
            estadoActual = 'proceso';
            actualizarBadgeEstado();
            console.log('Estado cambiado a:', estadoActual); // Debug
        }

        // Actualizar fecha de creación
        const fechaCreacion = seccionElement.querySelector('.fecha-creacion');
        if (fechaCreacion) {
            fechaCreacion.textContent = new Date().toLocaleString();
        }
    }

    // Función para crear nueva sección
    function crearNuevaSeccion() {
        const seccionesContainer = document.getElementById('seccionesHistorial');
        seccionCounter++;

        const nuevaSeccionHTML = `
            <div class="seccion-historial border-start border-4 border-primary ps-3 mt-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-comments me-2"></i>Tipo de Contacto
                        </label>
                        <select class="form-select tipo-contacto">
                            <option value="mail">Mail</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user me-2"></i>Agente
                        </label>
                        <select class="form-select agente">
                            <option value="at">Agustina Taboada</option>
                            <option value="fc">Florencia Consoli</option>
                            <option value="jd">Julieta Dalmeida</option>
                            <option value="ls">Leonel Segovia</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fas fa-comment me-2"></i>Comentario
                        </label>
                        <textarea class="form-control comentario" rows="4" placeholder="Ingrese su comentario aquí..."></textarea>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>Creado: <span class="fecha-creacion">${new Date().toLocaleString()}</span>
                    </small>
                    <button type="button" class="btn btn-primary btn-guardar-seccion">
                        <i class="fas fa-save me-1"></i>Guardar Sección
                    </button>
                </div>
            </div>
        `;

        seccionesContainer.insertAdjacentHTML('beforeend', nuevaSeccionHTML);
        document.getElementById('agregarSeccion').style.display = 'none';
    }

    // Event Listeners para Resolución
    const btnResolucion = document.getElementById('btnResolucion');
    const seccionResolucion = document.getElementById('seccionResolucion');
    const tipoResolucion = document.getElementById('tipoResolucion');
    const seccionSucursal = document.getElementById('seccionSucursal');
    const selectSucursal = document.getElementById('selectSucursal');
    const selectArticulo = document.getElementById('selectArticulo');

    if (btnResolucion) {
        btnResolucion.addEventListener('click', function() {
            seccionResolucion.style.display = 'block';
            this.style.display = 'none';
        });
    }

    if (tipoResolucion) {
        tipoResolucion.addEventListener('change', async function() {
            // Primero manejo de sucursales y artículos
            seccionSucursal.style.display = 
                (this.value === 'cambio' || this.value === 'completado') ? 'block' : 'none';
            
            if (seccionSucursal.style.display === 'block') {
                try {
                    showSpinner();
                    const response = await fetch('Controller/traerWarehouse.php');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const sucursales = await response.json();
                    console.log('Sucursales recibidas:', sucursales); // Debug
                    
                    selectSucursal.innerHTML = '<option value="">Seleccione sucursal...</option>';
                    if (Array.isArray(sucursales)) {
                        sucursales.forEach(suc => {
                            if (suc[0] && suc[0].WAREHOUSE) {
                                const warehouse = suc[0].WAREHOUSE;
                                selectSucursal.innerHTML += `
                                    <option value="${warehouse}">${warehouse}</option>
                                `;
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error cargando sucursales:', error);
                    alert('Error al cargar las sucursales. Por favor, intente nuevamente.');
                } finally {
                    hideSpinner();
                }
            } else {
                seccionSucursal.style.display = 'none';
                seccionArticulo.style.display = 'none';
            }
    
            // Luego manejo de botones
            const botonesNormales = document.getElementById('botonesNormales');
            const botonFinalizar = document.getElementById('botonFinalizar');
    
            if (this.value === 'completado') {
                botonesNormales.style.display = 'none';
                botonFinalizar.style.display = 'block';
            } else {
                botonesNormales.style.display = 'block';
                botonFinalizar.style.display = 'none';
            }
        });
    }

    if (selectSucursal) {
        selectSucursal.addEventListener('change', async function() {
            if (this.value) {
                try {
                    showSpinner();
                    const response = await fetch(`Controller/buscarStock.php?sucursal=${this.value}`);
                    const data = await response.json();
    
                    // Destruir select2 existente
                    if ($('#selectArticulo').data('select2')) {
                        $('#selectArticulo').select2('destroy');
                    }
    
                    // Limpiar y agregar nueva opción por defecto
                    $('#selectArticulo').empty().append(new Option('Seleccione artículo...', ''));
    
                    if (Array.isArray(data)) {
                        data.forEach(art => {
                            if (art[0]) {
                                const articulo = art[0];
                                const option = new Option(
                                    `${articulo.ARTICULO} - ${articulo.DESC_CTA_ARTICULO}`,
                                    articulo.ARTICULO
                                );
                                
                                // Establecer los datos como atributos data
                                option.dataset.codigo = articulo.ARTICULO;
                                option.dataset.descripcion = articulo.DESC_CTA_ARTICULO;
                                option.dataset.stock = articulo.CANT_STOCK || '0';
                                
                                $('#selectArticulo').append(option);
                            }
                        });
                    }
    
                    // Reinicializar select2
                    $('#selectArticulo').select2({
                        width: '100%',
                        placeholder: 'Buscar artículo...',
                        dropdownParent: $('#historialModal'),
                        language: 'es',
                        templateResult: formatArticuloResult,
                        templateSelection: formatArticuloSelection,
                        escapeMarkup: function(markup) {
                            return markup;
                        }
                    });
                    
                    seccionArticulo.style.display = 'block';
                } catch (error) {
                    console.error('Error cargando artículos:', error);
                    alert('Error al cargar los artículos');
                } finally {
                    hideSpinner();
                }
            }
        });
    }

    // Event Listeners generales
    document.querySelector('form')?.addEventListener('submit', showSpinner);

    document.addEventListener('click', function(e) {
        // Listener para botones de guardar sección
        if (e.target.matches('.btn-guardar-seccion')) {
            guardarSeccion(e.target.closest('.seccion-historial'));
        }

        // Listener para botón de agregar sección
        if (e.target.matches('#agregarSeccion')) {
            crearNuevaSeccion();
        }
    });

    // Agregar el evento para el botón finalizar
    document.getElementById('finalizarReclamo')?.addEventListener('click', function() {
        // Aquí puedes agregar la lógica para finalizar el reclamo
        // Por ejemplo, guardar en la base de datos

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('historialModal'));
        modal.hide();
    });

    // Inicialización
    actualizarBadgeEstado();

    // Manejo del spinner
    if (document.querySelector('.alert-warning')) {
        hideSpinner();
    }

    window.addEventListener('load', hideSpinner);
});