
// export-functions.js

/**
 * Función base para exportar cualquier tabla a Excel
 * @param {string} tableSelector - Selector CSS para la tabla
 * @param {string} sheetName - Nombre de la hoja en Excel
 * @param {string} fileName - Nombre del archivo a generar
 * @param {boolean} skipLastColumn - Si debe omitir la última columna (ej. para íconos)
 */
function exportTableToExcel(tableSelector, sheetName, fileName, skipLastColumn = false) {
    const table = document.querySelector(tableSelector);
    if (!table) return;
    
    const tableClone = table.cloneNode(true);
    const rows = tableClone.querySelectorAll('tr');
    const wb = XLSX.utils.book_new();
    const data = [];
    
    rows.forEach((row) => {
        const rowData = [];
        // Seleccionar las celdas, omitiendo la última columna si es necesario
        let selector = skipLastColumn ? 'th:not(:last-child), td:not(:last-child)' : 'th, td';
        row.querySelectorAll(selector).forEach((cell) => {
            let value = cell.textContent.trim();
            
            // Convertir fechas (dd/mm/yyyy)
            if (value.match(/^\d{2}\/\d{2}\/\d{4}/)) {
                const [datePart, timePart] = value.split(' ');
                const [day, month, year] = datePart.split('/');
                const dateStr = `${year}-${month}-${day}`;
                value = timePart ? `${dateStr} ${timePart}` : dateStr;
            }
            // Convertir valores monetarios
            else if (value.startsWith('$')) {
                value = parseFloat(value.replace('$', '').replace(/,/g, ''));
            }
            // Convertir valores numéricos con comas
            else if (value.match(/^[\d,.]+$/)) {
                value = parseFloat(value.replace(/,/g, ''));
            }
            // Convertir porcentajes
            else if (value.includes('%')) {
                value = parseFloat(value.replace('%', ''));
            }
            
            rowData.push(value);
        });
        data.push(rowData);
    });
    
    const ws = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, ws, sheetName);
    
    // Generar nombre de archivo con fecha actual
    const today = new Date().toISOString().slice(0,10);
    XLSX.writeFile(wb, `${fileName}_${today}.xlsx`);
}

// Funciones específicas para cada tipo de exportación
function exportToExcel() {
    exportTableToExcel('#modalFlexDetalle table', "Pedidos Flex", "pedidos_flex");
}

function exportToExcelFacturas() {
    exportTableToExcel('#modalFacturasDetalle table', "Facturas sin Remito", "facturas_sin_remito", true);
}

function exportToExcelNcDevoluciones() {
    exportTableToExcel('#modalNcDevolucionesDetalle table', "NC Pendientes Devoluciones", "nc_pendientes_devoluciones");
}

function exportToExcelNcPromociones() {
    exportTableToExcel('#modalNcPromocionesDetalle table', "NC Pendientes Promociones", "nc_pendientes_promociones");
}

function exportToExcelOrdenes() {
    exportTableToExcel('#modalOrdenesSinIntegrar table', "Ordenes sin Integrar", "ordenes_sin_integrar");
}

function exportToExcelOrdenesCierre() {
    exportTableToExcel('#modalOrdenesPendientesCierre table', "Ordenes Pendientes Cierre", "ordenes_pendientes_cierre", true);
}

function exportToExcelPendingDispatch() {
    exportTableToExcel('#modalPendingDispatch table', "Pedidos Pendientes Despacho", "pedidos_pendientes_despacho");
}

function exportToExcelMlFull() {
    // Obtener la tabla
    const table = document.querySelector('#tablaProductosMlFull');
    if (!table) return;
    
    const tableClone = table.cloneNode(true);
    
    // Remover filas de filtro
    const filterRow = tableClone.querySelector('thead tr:first-child');
    if (filterRow) filterRow.remove();
    
    const rows = tableClone.querySelectorAll('tr');
    const wb = XLSX.utils.book_new();
    const data = [];
    
    rows.forEach((row) => {
        const rowData = [];
        // Omitir las columnas de enlaces al exportar
        row.querySelectorAll('th:not(:nth-child(5)):not(:nth-child(6)), td:not(:nth-child(5)):not(:nth-child(6))').forEach((cell) => {
            let value = cell.textContent.trim();
            
            // Convertir valores numéricos con comas
            if (value.match(/^[\d,]+$/)) {
                value = parseFloat(value.replace(/,/g, ''));
            }
            
            rowData.push(value);
        });
        data.push(rowData);
    });
    
    const ws = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, ws, "Productos ML Full");
    
    // Generar nombre de archivo con fecha actual
    const today = new Date().toISOString().slice(0,10);
    XLSX.writeFile(wb, `productos_ml_full_${today}.xlsx`);
}