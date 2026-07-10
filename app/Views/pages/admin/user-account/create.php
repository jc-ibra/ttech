<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Nuevo usuario</h5>
          <p class="page-header__subtitle">Cuenta de acceso a la intranet. Los campos con <span class="field-required">*</span> son obligatorios.</p>
        </div>
        <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>

      <form method="post" action="<?= base_url('usuarios/create') ?>">
        <?php echo csrf_field(); ?>

        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="name">Nombre(s) <span class="field-required">*</span></label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Nombre(s)" required>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="lastname">Apellidos</label>
              <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Apellidos">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email">E-mail <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="correo@empresa.com" required>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label">Rol <span class="field-required">*</span></label>
              <select class="form-select" name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="user">Usuario</option>
                <option value="operator">Operador</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="password">Contraseña <span class="field-required">*</span></label>
              <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
          </div>
        </div>

        <?php if (session('message') !== null) : ?>
          <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
        <?php endif; ?>
        <?php if (session('success') !== null) : ?>
          <div class="alert alert-success mb-3"><?= session('success'); ?></div>
        <?php endif; ?>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Crear usuario</button>
          <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
