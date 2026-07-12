<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Empleados</h5>
          <p class="page-header__subtitle">Gestión de empleados y estructura organizacional</p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary" id="btnExport">
            <i class="ti ti-download me-1"></i>Exportar
          </button>
          <a href="<?= base_url('empleados/new') ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Nuevo
          </a>
        </div>
      </div>

      <!-- Filtros -->
      <div class="row g-2 mb-3">
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Estatus</label>
          <select id="filterStatus" class="form-select form-select-sm js-emp-filter">
            <option value="">Todos</option>
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Departamento</label>
          <select id="filterDepartment" class="form-select form-select-sm js-emp-filter">
            <option value="">Todos</option>
            <?php foreach ($departments as $d): ?>
              <option value="<?= $d->id ?>"><?= esc($d->name) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Área</label>
          <select id="filterArea" class="form-select form-select-sm js-emp-filter">
            <option value="">Todas</option>
            <?php foreach ($areas as $a): ?>
              <option value="<?= $a->id ?>"><?= esc($a->name) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Puesto</label>
          <select id="filterOcupation" class="form-select form-select-sm js-emp-filter">
            <option value="">Todos</option>
            <?php foreach ($ocupations as $o): ?>
              <option value="<?= $o->id ?>"><?= esc($o->name) ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle w-100" id="dt_table_users">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>No. Empleado</th>
              <th>E-mail</th>
              <th>Puesto</th>
              <th>Departamento</th>
              <th>Área</th>
              <th>Jefe directo</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    var tabla = $('#dt_table_users').DataTable({
      processing: true,
      serverSide: true,
      language: window.DT_ES,
      order: [[0, 'asc']],
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      ajax: {
        url: base_url + 'empleados/data',
        data: function (d) {
          d.status     = $('#filterStatus').val();
          d.department = $('#filterDepartment').val();
          d.area       = $('#filterArea').val();
          d.ocupation  = $('#filterOcupation').val();
        }
      },
      columns: [
        { data: 0 },                    // Nombre (HTML)
        { data: 1 },                    // No. Empleado
        { data: 2 },                    // E-mail
        { data: 3 },                    // Puesto
        { data: 4 },                    // Departamento
        { data: 5 },                    // Área
        { data: 6 },                    // Jefe directo
        { data: 7 }                     // Estatus (HTML)
      ],
      buttons: [
        { extend: 'colvis', text: '<i class="ti ti-columns"></i> Columnas', className: 'btn btn-sm btn-outline-secondary' }
      ],
      layout: {
        topStart: 'buttons',
        topEnd: 'search',
        bottomStart: ['pageLength', 'info'],
        bottomEnd: 'paging'
      }
    });

    // Al cambiar cualquier filtro, recargar desde el servidor.
    $('.js-emp-filter').on('change', function () {
      tabla.ajax.reload();
    });

    // Exportar CSV respetando filtros y búsqueda activos.
    $('#btnExport').on('click', function () {
      var params = $.param({
        status: $('#filterStatus').val(),
        department: $('#filterDepartment').val(),
        area: $('#filterArea').val(),
        ocupation: $('#filterOcupation').val(),
        'search[value]': tabla.search()
      });
      window.location = base_url + 'empleados/export?' + params;
    });
  });
</script>
