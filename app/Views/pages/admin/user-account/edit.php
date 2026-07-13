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

      <?php if (!$isNexus): ?>
        <div class="user-photo">
          <img id="photoPreview" class="user-photo__avatar"
               src="<?= base_url($user->photo) ?>"
               alt="<?= esc($user->name) ?>"
               onerror="this.onerror=null;this.src='<?= base_url('assets/images/anonimo.jpg') ?>';">
          <div class="user-photo__body">
            <span class="user-photo__label">Foto de perfil</span>
            <span class="user-photo__hint">JPG o PNG.</span>
            <div class="user-photo__actions">
              <button type="button" class="btn btn-outline-primary btn-sm" id="photoPick">
                <i class="ti ti-camera me-1"></i>Cambiar foto
              </button>
              <button type="button" class="btn btn-primary btn-sm" id="photoSave" style="display:none;">
                <span class="photo-save__label"><i class="ti ti-device-floppy me-1"></i>Guardar foto</span>
                <span class="photo-save__spinner" style="display:none;"><span class="spinner-border spinner-border-sm"></span> Guardando…</span>
              </button>
            </div>
            <div id="photo__response" class="user-photo__msg" style="display:none;"></div>
          </div>
          <input type="file" id="photoInput" accept="image/jpeg,image/png,image/jpg" hidden>
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

    /* ------- Foto de perfil ------- */
    var selectedFile = null;
    var $photoMsg    = $('#photo__response');
    var $photoSave   = $('#photoSave');

    function showPhotoMsg(text, ok) {
      $photoMsg.text(text)
               .toggleClass('is-error', !ok)
               .toggleClass('is-success', !!ok)
               .show();
    }
    function setPhotoLoading(loading) {
      $photoSave.prop('disabled', loading);
      $photoSave.find('.photo-save__label').toggle(!loading);
      $photoSave.find('.photo-save__spinner').toggle(loading);
    }

    $('#photoPick').on('click', function() { $('#photoInput').trigger('click'); });

    $('#photoInput').on('change', function() {
      var file = this.files && this.files[0];
      if (!file) return;

      if (['image/jpeg', 'image/png', 'image/jpg'].indexOf(file.type) === -1) {
        showPhotoMsg('La imagen no es válida. Usa JPG o PNG.', false);
        this.value = '';
        return;
      }

      selectedFile = file;
      $photoMsg.hide();
      $('#photoPreview').attr('src', URL.createObjectURL(file));
      $photoSave.show();
    });

    $photoSave.on('click', function() {
      if (!selectedFile) return;

      var formData = new FormData();
      formData.append('id', userId);
      formData.append('photo', selectedFile);
      formData.append(csrfName, csrfHash);

      setPhotoLoading(true);

      $.ajax({
        url: baseUrl + '/update/photo',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(resp) {
          if (resp.csrf_name && resp.csrf_token) {
            csrfName = resp.csrf_name;
            csrfHash = resp.csrf_token;
            if (typeof actualizarCsrfToken === 'function') {
              actualizarCsrfToken(resp.csrf_name, resp.csrf_token);
            }
          }
          setPhotoLoading(false);
          if (resp.ok) {
            selectedFile = null;
            $('#photoInput').val('');
            $photoSave.hide();
            showPhotoMsg(resp.message || 'Foto de perfil actualizada', true);
          } else {
            showPhotoMsg(resp.message || 'No se pudo actualizar la foto.', false);
          }
        },
        error: function() {
          setPhotoLoading(false);
          showPhotoMsg('Ocurrió un error. Inténtalo de nuevo más tarde.', false);
        }
      });
    });
  });
</script>

<style>
  .user-photo {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding: 1.1rem 1.25rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--color-neutral-200, #e5e7eb);
    border-radius: 14px;
    background: var(--bg-page, #f8f9fa);
  }
  .user-photo__avatar {
    width: 76px; height: 76px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px var(--color-neutral-200, #e5e7eb);
    background: #fff;
  }
  .user-photo__body { display: flex; flex-direction: column; gap: 0.25rem; }
  .user-photo__label { font-size: 0.95rem; font-weight: 700; color: var(--text-primary, #1f2937); }
  .user-photo__hint  { font-size: 0.78rem; color: var(--text-muted, #6b7280); }
  .user-photo__actions { display: flex; gap: 0.5rem; margin-top: 0.4rem; flex-wrap: wrap; }
  .photo-save__label, .photo-save__spinner { display: inline-flex; align-items: center; gap: 0.35rem; }
  .user-photo__msg { font-size: 0.82rem; margin-top: 0.35rem; }
  .user-photo__msg.is-error   { color: var(--bs-danger, #d72c0d); }
  .user-photo__msg.is-success { color: var(--color-success-default, #198754); }
</style>
