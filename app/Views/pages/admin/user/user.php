<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Empleados</h5>
          <p class="page-header__subtitle">Gestión de empleados y accesos</p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary" id="btnBuscar">
            <i class="ti ti-search me-1"></i>Buscar
          </button>
          <a href="<?= base_url('empleados/new') ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Nuevo
          </a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="dt_table_users">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Jefe directo</th>
              <th>E-mail</th>
              <th>No. Empleado</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $user): ?>
              <?php if( !$user->ghost ): ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img class="rounded-circle" width="32" height="32" alt="<?= $user->name ?>" src="<?= base_url($user->photo) ?>" style="object-fit:cover;">
                      <a class="fw-semibold text-primary text-decoration-none" href="<?= base_url('empleados/edit/'.$user->id) ?>">
                        <?= $user->name ?> <?= $user->lastname ?>
                      </a>
                    </div>
                  </td>
                  <td><?= $user->parent_name ?? '-' ?></td>
                  <td><?= $user->email ?></td>
                  <td><?= $user->employee_number ?></td>
                  <td>
                    <span class="<?= $user->active == 1 ? 'badge-success' : 'badge-critical' ?>">
                      <?= $user->active == 1 ? 'Activo' : 'Inactivo' ?>
                    </span>
                  </td>
                </tr>
              <?php endif ?>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="searchUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchUser" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="searchUserText">Buscar empleado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body feed">
        <p>Selecciona empleado</p>
        <div class="mb-3">
          <select name="userSearch" id="userSearch" class="form-select select2">
            <option></option>
            <?php foreach($users as $user): ?>
              <?php if( !$user->ghost ): ?>
                <option value="<?= $user->id ?>">
                  <?= $user->name ?> <?= $user->lastname ?> - <?= $user->employee_number ?>
                </option>
              <?php endif ?>
            <?php endforeach ?>
          </select>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-outline-primary d-block ms-2" id="irUsuario">Ver empleado</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $('#dt_table_users').DataTable().destroy();
  $('#dt_table_users').DataTable({
      order: [[0, 'asc']],
      language: {url: 'https://cdn.datatables.net/plug-ins/1.10.10/i18n/Spanish.json'},
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'colvis',
          text: 'Columna personalizada',
        },
        'csv', 'excel'
      ]
  });
  $('#btnBuscar').on('click', function() {
    $('#searchUser').modal('show');
  });
  $('.select2').select2({
    placeholder: 'Empleado',
    allowClear: true,
    dropdownParent: $('#searchUser')
  });
  $('#irUsuario').on('click', function() {
    var userId = $('#userSearch').val();
    if(userId){
      window.location.href = base_url + 'empleados/edit/' + userId;
    }
  });
</script>