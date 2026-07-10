<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Usuarios</h5>
          <p class="page-header__subtitle">Cuentas que inician sesión en la intranet. Los viewers los administra Nexus.</p>
        </div>
        <div class="d-flex gap-2">
          <a href="<?= base_url('usuarios/new') ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Nuevo
          </a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="dt_table_accounts">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>E-mail</th>
              <th>Rol</th>
              <th>Origen</th>
              <th>Estatus</th>
              <th>Última conexión</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $user): ?>
              <tr>
                <td>
                  <a class="fw-semibold text-primary text-decoration-none" href="<?= base_url('usuarios/edit/'.$user->id) ?>">
                    <?= esc($user->name) ?> <?= esc($user->lastname) ?>
                  </a>
                </td>
                <td><?= esc($user->email) ?></td>
                <td>
                  <?php
                    if ($user->rol == 'admin') echo 'Administrador';
                    elseif ($user->rol == 'operator') echo 'Operador';
                    else echo 'Usuario';
                  ?>
                </td>
                <td>
                  <?php if (!empty($user->nexus_id)): ?>
                    <span class="badge-info">Nexus</span>
                  <?php else: ?>
                    <span class="badge-neutral">Manual</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="<?= $user->active == 1 ? 'badge-success' : 'badge-critical' ?>">
                    <?= $user->active == 1 ? 'Activo' : 'Inactivo' ?>
                  </span>
                </td>
                <td><?= $user->last_login ?? 'Sin actividad' ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  $('#dt_table_accounts').DataTable().destroy();
  $('#dt_table_accounts').DataTable({
      order: [[0, 'asc']],
      language: {url: 'https://cdn.datatables.net/plug-ins/1.10.10/i18n/Spanish.json'},
      dom: 'Bfrtip',
      buttons: [
        { extend: 'colvis', text: 'Columna personalizada' },
        'csv', 'excel'
      ]
  });
</script>
