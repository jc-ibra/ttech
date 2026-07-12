/**
 * admin-tables.js
 * Inicialización estándar de DataTables para las tablas del administrador.
 *
 * - Traducción al español embebida (sin depender de un JSON en CDN).
 * - Paginador, buscador y botones de exportación consistentes en todas las tablas.
 * - Filtros por columna vía <select class="js-dt-filter" data-table-target="#id" data-column="N">.
 *
 * Uso: agrega la clase `js-datatable` a cualquier <table> del admin.
 *      Opcionales por data-attribute:
 *        data-order-col="0"     columna de orden inicial (default 0)
 *        data-order-dir="asc"   dirección inicial (default asc)
 *        data-no-export="true"  oculta los botones CSV/Excel
 *        data-no-buttons="true" oculta toda la barra de botones
 *        data-page-length="25"  registros por página (default 25)
 */

// Traducción reutilizable (también la usa la tabla server-side de empleados).
window.DT_ES = {
  emptyTable: 'No hay información disponible',
  info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
  infoEmpty: 'Mostrando 0 a 0 de 0 registros',
  infoFiltered: '(filtrado de _MAX_ registros totales)',
  lengthMenu: 'Mostrar _MENU_ registros',
  loadingRecords: 'Cargando...',
  processing: 'Procesando...',
  search: 'Buscar:',
  zeroRecords: 'No se encontraron resultados',
  paginate: {
    first: 'Primero',
    last: 'Último',
    next: 'Siguiente',
    previous: 'Anterior'
  },
  aria: {
    sortAscending: ': activar para ordenar la columna de forma ascendente',
    sortDescending: ': activar para ordenar la columna de forma descendente'
  }
};

// Registro de instancias por id de tabla, para que los filtros las localicen.
window.adminTables = window.adminTables || {};

(function ($) {
  'use strict';

  if (typeof $ === 'undefined' || !$.fn || !$.fn.DataTable) {
    return; // DataTables no está cargado en esta vista.
  }

  function buildButtons($table) {
    if ($table.data('no-buttons')) {
      return [];
    }

    var buttons = [
      { extend: 'colvis', text: '<i class="ti ti-columns"></i> Columnas', className: 'btn btn-sm btn-outline-secondary' }
    ];

    if (!$table.data('no-export')) {
      buttons.push({ extend: 'csv', text: '<i class="ti ti-file-text"></i> CSV', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':visible' } });
      buttons.push({ extend: 'excel', text: '<i class="ti ti-file-spreadsheet"></i> Excel', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':visible' } });
    }

    return buttons;
  }

  function initTable(table) {
    var $table = $(table);

    if ($.fn.DataTable.isDataTable(table)) {
      return $table.DataTable();
    }

    var orderCol = parseInt($table.data('order-col'), 10);
    if (isNaN(orderCol)) orderCol = 0;
    var orderDir = ($table.data('order-dir') || 'asc').toLowerCase();
    var pageLength = parseInt($table.data('page-length'), 10) || 25;

    var dt = $table.DataTable({
      language: window.DT_ES,
      order: [[orderCol, orderDir]],
      pageLength: pageLength,
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
      buttons: buildButtons($table),
      layout: {
        topStart: 'buttons',
        topEnd: 'search',
        bottomStart: ['pageLength', 'info'],
        bottomEnd: 'paging'
      }
    });

    if (table.id) {
      window.adminTables['#' + table.id] = dt;
    }

    return dt;
  }

  function escapeRegex(value) {
    return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  // Filtros por columna: coincidencia exacta (respetando palabras).
  function wireFilters() {
    $(document).on('change', '.js-dt-filter', function () {
      var $select = $(this);
      var target = $select.data('table-target');
      var column = parseInt($select.data('column'), 10);
      var dt = window.adminTables[target];

      if (!dt || isNaN(column)) return;

      var value = $select.val();
      if (value === '' || value === null) {
        dt.column(column).search('').draw();
      } else {
        // \b...\b evita que "Activo" también case con "Inactivo".
        dt.column(column).search('\\b' + escapeRegex(value) + '\\b', true, false, true).draw();
      }
    });
  }

  $(function () {
    $('table.js-datatable').each(function () {
      initTable(this);
    });
    wireFilters();
  });
})(jQuery);
