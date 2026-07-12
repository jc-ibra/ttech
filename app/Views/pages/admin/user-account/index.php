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
      <!-- Filtros -->
      <div class="row g-2 mb-3">
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Rol</label>
          <select class="form-select form-select-sm js-dt-filter" data-table-target="#dt_table_accounts" data-column="2">
            <option value="">Todos</option>
            <option value="Administrador">Administrador</option>
            <option value="Operador">Operador</option>
            <option value="Usuario">Usuario</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Origen</label>
          <select class="form-select form-select-sm js-dt-filter" data-table-target="#dt_table_accounts" data-column="3">
            <option value="">Todos</option>
            <option value="Nexus">Nexus</option>
            <option value="Manual">Manual</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small text-muted mb-1">Estatus</label>
          <select class="form-select form-select-sm js-dt-filter" data-table-target="#dt_table_accounts" data-column="4">
            <option value="">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle w-100 js-datatable" id="dt_table_accounts" data-order-col="0" data-order-dir="asc">
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
