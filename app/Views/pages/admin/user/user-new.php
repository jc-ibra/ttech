<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Nuevo empleado</h5>
          <p class="page-header__subtitle">Los campos marcados con <span class="field-required">*</span> son obligatorios</p>
        </div>
        <a href="<?= base_url('empleados') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>

      <form method="post" action="<?= base_url('empleados/register') ?>" enctype="multipart/form-data" id="register__form">
        <?php echo csrf_field(); ?>

        <!-- Sección 1: Datos personales -->
        <div class="form-section">
          <h6 class="form-section__title">Datos personales y contacto</h6>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="name">Nombre(s) <span class="field-required">*</span></label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Nombre(s)" required="">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="lastname">Apellidos <span class="field-required">*</span></label>
              <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Apellidos" required="">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email">E-mail <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="correo@empresa.com" required="">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email_secondary">E-mail secundario</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email_secondary" name="email_secondary" class="form-control" placeholder="correo@dominio.com">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="telephone">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" id="telephone" name="telephone" class="form-control" placeholder="10 dígitos" maxlength="10" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="cellphone">Celular <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-device-mobile"></i></span>
                <input type="text" id="cellphone" name="cellphone" class="form-control" placeholder="10 dígitos" required="" maxlength="10" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="ext">Extensión</label>
              <input type="text" id="ext" name="ext" class="form-control" placeholder="Ej. 1234" maxlength="5" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="photo">Foto de perfil</label>
              <input type="file" id="photo" name="photo" class="form-control" accept=".png, .jpg, .jpeg">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="employee_number">No. de empleado <span class="field-required">*</span></label>
              <input type="text" maxlength="10" id="employee_number" name="employee_number" class="form-control" placeholder="Número de empleado" required="" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="date_entry">Fecha de ingreso <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                <input type="date" id="date_entry" name="date_entry" class="form-control" required="" max="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="hide_emails" name="hide_emails">
              <label class="form-check-label" for="hide_emails">Ocultar e-mails en directorio</label>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="show_in_directory" name="show_in_directory">
              <label class="form-check-label" for="show_in_directory">Mostrar en directorio</label>
            </div>
          </div>
        </div>

        <!-- Sección 2: Puesto y organigrama -->
        <div class="form-section">
          <h6 class="form-section__title">Puesto y organigrama</h6>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-4">
              <label class="form-label">Puesto <span class="field-required">*</span></label>
              <select class="form-select select2" name="ocupation" required>
                <option value="">Selecciona un puesto</option>
                <?php foreach($ocupations as $ocupation): ?>
                  <option value="<?= $ocupation->id ?>"><?= $ocupation->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-4">
              <label class="form-label">Departamento</label>
              <select class="form-select select2" name="department">
                <option value="">Selecciona un departamento</option>
                <?php foreach($departments as $department): ?>
                  <option value="<?= $department->id ?>"><?= $department->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-4">
              <label class="form-label">Area</label>
              <select class="form-select select2" name="area">
                <option value="">Selecciona un area</option>
                <?php foreach($areas as $area): ?>
                  <option value="<?= $area->id ?>"><?= $area->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="row">
              <div class="col-9">
                <label class="form-label">Jefe directo <span class="field-required">*</span></label>
                <select class="form-select select2" name="parent" id="parent" required>
                  <option value="">Selecciona jefe directo</option>
                  <?php foreach($users as $user): ?>
                    <?php if( !$user->ghost ): ?>
                      <option value="<?= $user->id ?>"><?= $user->complete_name ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col d-flex align-items-end">
                <div class="d-flex align-items-center gap-1 mb-3">
                  <input class="form-check-input" type="checkbox" value="1" id="no_aplica" name="no_aplica">
                  <label class="form-check-label" for="no_aplica">N/A</label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="ghost" name="ghost">
              <label class="form-check-label" for="ghost">Bajar nivel en organigrama</label>
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
          <button type="submit" class="btn btn-primary">Crear empleado</button>
          <a href="<?= base_url('empleados') ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: 'Selecciona',
      allowClear: true
    });

    $('#no_aplica').on('change', function() {
      if ($(this).is(':checked')) {
        $('#parent').val(null).trigger('change');
        $('#parent').prop('disabled', true).removeAttr('required');
      } else {
        $('#parent').prop('disabled', false).attr('required', 'required');
      }
    });
  });
</script>
