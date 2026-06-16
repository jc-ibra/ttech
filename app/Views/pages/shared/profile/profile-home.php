<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <!-- Cabecera del perfil -->
      <div class="d-flex align-items-center mb-4">
        <img
          id="actualImage"
          alt="<?= $user->name ?>"
          src="<?= base_url($user->photo) ?>"
          class="rounded-circle update__image"
          width="80"
          height="80"
          style="object-fit:cover; cursor:pointer;"
        />
        <div class="ms-3">
          <h5 class="fw-semibold mb-0"><?= $user->name ?> <?= $user->lastname ?></h5>
          <small class="text-muted"><?= $user->email ?></small>
        </div>
      </div>
      <form enctype="multipart/form-data" id="updatePhoto" style="display:none;" class="mb-4 p-3 bg-light rounded">
        <label class="form-label fw-semibold">Foto de perfil</label>
        <input type="file" class="form-control mb-3" id="photo" name="photo" accept=".jpg, .jpeg, .png" required>
        <button type="submit" class="btn btn-primary">Actualizar foto</button>
      </form>

      <div class="row">
        <!-- Columna izquierda: datos personales -->
        <div class="col-md-6">
          <div class="form-section" style="border-top:none; padding-top:0; margin-top:0;">
            <h6 class="form-section__title">Información personal</h6>
          </div>
          <form id="updateProfile">
            <div class="mb-3">
              <label class="form-label">Nombre(s)</label>
              <input type="text" id="name" name="name" value="<?= $user->name ?>" class="form-control bg-light" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Apellidos</label>
              <input type="text" id="lastname" name="lastname" value="<?= $user->lastname ?>" class="form-control bg-light" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label" for="telephone">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" id="telephone" name="telephone" value="<?= $user->telephone ?>" class="form-control" placeholder="10 dígitos" maxlength="10">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="cellphone">Celular <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-device-mobile"></i></span>
                <input type="text" id="cellphone" name="cellphone" value="<?= $user->cellphone ?>" class="form-control" placeholder="10 dígitos" required="" maxlength="10">
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label" for="ext">Extensión</label>
              <input type="text" id="ext" name="ext" value="<?= $user->ext ?>" class="form-control" placeholder="Ej. 1234" maxlength="5">
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
          </form>
        </div>

        <!-- Columna derecha: cambiar contraseña -->
        <div class="col-md-6 mt-4 mt-md-0">
          <div class="form-section" style="border-top:none; padding-top:0; margin-top:0;">
            <h6 class="form-section__title">Cambiar contraseña</h6>
          </div>
          <form id="updatePassword">
            <div class="mb-3">
              <label class="form-label" for="oldPassword">Contraseña actual</label>
              <input type="password" class="form-control" id="oldPassword" name="oldPassword" required placeholder="Contraseña actual">
            </div>
            <div class="mb-3">
              <label class="form-label" for="password">Nueva contraseña</label>
              <input type="password" class="form-control" id="password" name="password" required placeholder="Nueva contraseña">
            </div>
            <div class="mb-4">
              <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Repetir contraseña">
            </div>
            <button type="submit" class="btn btn-outline-secondary w-100">Actualizar contraseña</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var baseUrl  = "<?= base_url('profile/update'); ?>"
  var csrfName = '<?= $csrfName ?>';
  var csrfHash = '<?= $csrfHash ?>';
</script>
