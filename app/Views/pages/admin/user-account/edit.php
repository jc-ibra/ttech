<?php $isNexus = !empty($user->nexus_id); ?>
<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Editar usuario</h5>
          <p class="page-header__subtitle">Cuenta de acceso a la intranet. Los campos con <span class="field-required">*</span> son obligatorios.</p>
        </div>
        <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>

      <?php if ($isNexus): ?>
        <div class="alert alert-info d-flex align-items-center mb-3">
          <i class="ti ti-info-circle me-2"></i>
          <span>Este usuario proviene de <strong>Nexus</strong> y se gestiona de forma centralizada. Sus datos son de <strong>solo lectura</strong> y no pueden modificarse desde la intranet.</span>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('usuarios/update') ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" id="id" name="id" value="<?= $user->id ?>">

        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="name">Nombre(s) <span class="field-required">*</span></label>
              <input type="text" id="name" name="name" value="<?= esc($user->name) ?>" class="form-control" required <?= $isNexus ? 'disabled' : '' ?>>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="lastname">Apellidos</label>
              <input type="text" id="lastname" name="lastname" value="<?= esc($user->lastname) ?>" class="form-control" <?= $isNexus ? 'disabled' : '' ?>>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email">E-mail <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email" name="email" value="<?= esc($user->email) ?>" class="form-control" required <?= $isNexus ? 'disabled' : '' ?>>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label">Rol <span class="field-required">*</span></label>
              <select class="form-select" name="rol" required <?= $isNexus ? 'disabled' : '' ?>>
                <option value="user" <?= $user->rol == 'user' ? 'selected' : '' ?>>Usuario</option>
                <option value="operator" <?= $user->rol == 'operator' ? 'selected' : '' ?>>Operador</option>
                <option value="admin" <?= $user->rol == 'admin' ? 'selected' : '' ?>>Administrador</option>
              </select>
            </div>
          </div>
          <?php if (!$isNexus): ?>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="password">Restablecer contraseña</label>
              <input type="password" id="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>
          </div>
          <?php endif; ?>
          <?php if (!empty($user->nexus_id)): ?>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label">ID Nexus</label>
              <input type="text" class="form-control bg-light" value="<?= esc($user->nexus_id) ?>" readonly>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <div id="message__response" class="alert alert-success" style="display:none"></div>
        <?php if (session('message') !== null) : ?>
          <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
        <?php endif; ?>
        <?php if (session('success') !== null) : ?>
          <div class="alert alert-success mb-3"><?= session('success'); ?></div>
        <?php endif; ?>

        <?php if (!$isNexus): ?>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="account_save">Actualizar usuario</button>
          <?php if ($user->active == 1): ?>
            <button type="button" class="btn btn-outline-danger" id="inactive_user">Desactivar</button>
          <?php else: ?>
            <button type="button" class="btn btn-outline-secondary" id="active_user">Activar</button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<script>
  var userId   = "<?= $user->id; ?>";
  var baseUrl  = "<?= base_url('usuarios'); ?>";
  var csrfName = '<?= $csrfName; ?>';
  var csrfHash = '<?= $csrfHash; ?>';

  $(document).ready(function() {
    function toggleAccount(action) {
      $.post(baseUrl + "/" + action, { id: userId, [csrfName]: csrfHash }, function(resp) {
        if (resp.ok === true) {
          location.reload();
        } else {
          alert(resp.error || 'No se pudo completar la acción.');
          if (resp.csrf_name && resp.csrf_token && typeof actualizarCsrfToken === 'function') {
            actualizarCsrfToken(resp.csrf_name, resp.csrf_token);
          }
        }
      }).fail(function() {
        alert('Error en la solicitud.');
      });
    }

    $('#active_user').on('click', function() { toggleAccount('active'); });
    $('#inactive_user').on('click', function() { toggleAccount('inactive'); });
  });
</script>
