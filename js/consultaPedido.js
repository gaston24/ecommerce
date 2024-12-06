
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Configuración inicial
    Dropzone.autoDiscover = false;
    let dropzones = [];
    let dropzoneCounter = 1;
    let estadoActual = 'abierto';

    // Control de estados
    const badgesEstado = document.querySelectorAll('.estado-badge');
    const btnResuelto = document.getElementById('marcarResuelto');

    // Agregar modal al DOM
    agregarModalVisualizador();

    // Funciones de control de estados
    function actualizarEstado(nuevoEstado) {
        badgesEstado.forEach(badge => {
            badge.classList.remove('active');
            if (badge.classList.contains(`estado-${nuevoEstado}`)) {
                badge.classList.add('active');
            }
        });
        estadoActual = nuevoEstado;
        console.log('Estado actualizado a:', nuevoEstado);
    }

    // Funciones del Spinner
    function showSpinner() {
        document.getElementById('spinner').classList.remove('spinner-hidden');
    }

    function hideSpinner() {
        document.getElementById('spinner').classList.add('spinner-hidden');
    }

    // Función para inicializar Dropzone
    function inicializarDropzone(elementId, nroPedido, seccionId) {
        const dropzone = new Dropzone(`#${elementId}`, {
            url: "upload.php",
            thumbnailWidth: 120,
            thumbnailHeight: 120,
            parallelUploads: 20,
            clickable: '.dropzone',
            renameFile: function (file) {
                const extension = file.name.split('.').pop();
                return `${nroPedido}_${seccionId}_${new Date().getTime()}.${extension}`;
            },
            previewTemplate: `
                <div class="dz-preview dz-file-preview">
                    <div class="dz-image">
                        <img data-dz-thumbnail class="img-preview-thumb" />
                    </div>
                    <div class="dz-details">
                        <div class="dz-filename"><span data-dz-name></span></div>
                    </div>
                    <div class="dz-success-mark">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="dz-error-mark">
                        <i class="fas fa-times"></i>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary btn-ampliar mt-2">
                        <i class="fas fa-search-plus"></i> Ampliar
                    </button>
                </div>`,
            dictDefaultMessage: `
                <i class="fas fa-cloud-upload-alt mb-2 display-4"></i>
                <p>Arrastra aquí las imágenes o haz clic para seleccionar</p>`,
            dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
            dictInvalidFileType: "No puedes subir archivos de este tipo.",
            dictResponseError: "El servidor respondió con código {{statusCode}}.",
            dictCancelUpload: "Cancelar subida",
            dictUploadCanceled: "Subida cancelada.",
            dictRemoveFile: "Eliminar archivo",
            dictMaxFilesExceeded: "No puedes subir más archivos.",
            acceptedFiles: "image/*",
            maxFilesize: 5,
            init: function() {
                this.on("addedfile", function(file) {
                    console.log("Archivo añadido:", file.name);
                    if (estadoActual === 'abierto') {
                        actualizarEstado('proceso');
                    }

                    const btnAmpliar = file.previewElement.querySelector('.btn-ampliar');
                    btnAmpliar.addEventListener('click', function(e) {
                        e.stopPropagation();
                        mostrarImagenModal(file);
                    });
                });

                this.on("click", function(e) {
                    if (!e.target.closest('.dz-message') && !e.target.closest('.btn-ampliar')) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                this.on("success", (file, response) => {
                    console.log("Archivo subido:", response);
                });
                
                this.on("error", (file, error) => {
                    console.error("Error:", error);
                });
            }
        });

        dropzones.push(dropzone);
        return dropzone;
    }

    function guardarSeccion(seccionElement) {
        // Obtener datos de la sección
        const tipoContacto = seccionElement.querySelector('.tipo-contacto').value;
        const agente = seccionElement.querySelector('.agente').value;
        const imagenes = seccionElement.querySelector('.dropzone').dropzone.files;
        
        // Simular envío al servidor
        // Aquí irá tu código de guardado real
        
        // Cambiar vista de la sección
        seccionElement.classList.add('seccion-guardada');
        
        // Deshabilitar campos
        seccionElement.querySelector('.tipo-contacto').disabled = true;
        seccionElement.querySelector('.agente').disabled = true;
        
        // Modificar dropzone para mostrar solo imágenes
        const dropzoneElement = seccionElement.querySelector('.dropzone');
        dropzoneElement.style.height = 'auto';
        dropzoneElement.style.minHeight = 'auto';
        
        // Ocultar botón guardar
        seccionElement.querySelector('.btn-guardar-seccion').style.display = 'none';
        
        // Habilitar botón de nueva sección
        document.getElementById('agregarSeccion').style.display = 'block';
    }
    
    // Event listener para botones de guardar
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-guardar-seccion')) {
            guardarSeccion(e.target.closest('.seccion-historial'));
        }
    });

    // Función para crear nueva sección
    function crearNuevaSeccion() {
    const seccionesContainer = document.getElementById('seccionesHistorial');
    const nuevaSeccionHTML = `
        <div class="seccion-historial mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tipo de Contacto</label>
                    <select class="form-select tipo-contacto">
                        <option value="mail">Mail</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Agente</label>
                    <select class="form-select agente">
                        <option value="at">Agustina Taboada</option>
                        <option value="fc">Florencia Consoli</option>
                        <option value="jd">Julieta Dalmeida</option>
                        <option value="ls">Leonel Segovia</option>
                    </select>
                </div>
            </div>
            
            <div class="dropzone-container mt-3">
                <form action="upload.php" class="dropzone"></form>
            </div>
            
            <div class="text-end mt-3">
                <button type="button" class="btn btn-primary btn-guardar-seccion">
                    <i class="fas fa-save"></i> Guardar Sección
                </button>
            </div>
        </div>
    `;
    
    seccionesContainer.insertAdjacentHTML('beforeend', nuevaSeccionHTML);
    
    // Inicializar nuevo dropzone
    const nuevaSeccion = seccionesContainer.lastElementChild;
    const nuevoDropzone = nuevaSeccion.querySelector('.dropzone');
    dropzoneCounter++;
    nuevoDropzone.id = `dropzone-${dropzoneCounter}`;
    inicializarDropzone(nuevoDropzone.id, nroPedido, dropzoneCounter);
    
    // Ocultar botón agregar hasta que se guarde esta sección
    document.getElementById('agregarSeccion').style.display = 'none';
}

    function agregarModalVisualizador() {
        const modalHTML = `
            <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imagenModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img id="imagenModalContenido" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    function mostrarImagenModal(file) {
        const modalImagen = document.getElementById('imagenModalContenido');
        const modalTitle = document.querySelector('#imagenModal .modal-title');
        
        if (file.dataURL) {
            modalImagen.src = file.dataURL;
            modalTitle.textContent = file.name;
            const modal = new bootstrap.Modal(document.getElementById('imagenModal'));
            modal.show();
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                file.dataURL = e.target.result;
                modalImagen.src = e.target.result;
                modalTitle.textContent = file.name;
                const modal = new bootstrap.Modal(document.getElementById('imagenModal'));
                modal.show();
            }
            reader.readAsDataURL(file);
        }
    }

    // Agregar estilos CSS
    const styles = `
        .img-preview-thumb {
            cursor: default !important;
        }
        .dz-preview {
            position: relative;
        }
        .btn-ampliar {
            position: relative;
            z-index: 10;
        }
        .dz-image {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #imagenModalContenido {
            max-height: 80vh;
            width: auto;
            max-width: 100%;
        }
        .modal-body {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
        }
        .dropzone {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            min-height: 150px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            cursor: pointer;
        }
        .dropzone:hover {
            border-color: #0d6efd;
            background: #fff;
        }
    `;

    const styleSheet = document.createElement("style");
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);

    // Event Listeners
    document.querySelector('form')?.addEventListener('submit', showSpinner);
    
    document.getElementById('agregarSeccion')?.addEventListener('click', function() {
        crearNuevaSeccion();
    });
    
    btnResuelto?.addEventListener('click', function() {
        actualizarEstado('resuelto');
        this.disabled = true;
    });

    // Inicializaciones
    inicializarDropzone('dropzone-1');

    // Manejo del spinner
    if (document.querySelector('.alert-warning')) {
        hideSpinner();
    }

    window.addEventListener('load', hideSpinner);
});