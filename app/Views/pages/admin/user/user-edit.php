<?php
  $user       = $data['user'];
  $users      = $data['users'];
  $ocupations = $data['ocupations'];
  $departments = $data['departments'];
  $areas      = $data['areas'];
?>
<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Editar empleado</h5>
          <p class="page-header__subtitle">Los campos marcados con <span class="field-required">*</span> son obligatorios</p>
        </div>
        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>

      <form method="post" action="<?= base_url('auth/user/update') ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" id="id" name="id" value="<?= $user->id ?>">

        <!-- Sección 1: Datos personales -->
        <div class="form-section">
          <h6 class="form-section__title">Datos personales y contacto</h6>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="name">Nombre(s) <span class="field-required">*</span></label>
              <input type="text" id="name" name="name" value="<?= $user->name ?>" class="form-control" placeholder="Nombre(s)" required="">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="lastname">Apellidos <span class="field-required">*</span></label>
              <input type="text" id="lastname" name="lastname" value="<?= $user->lastname ?>" class="form-control" placeholder="Apellidos">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email">E-mail <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email" name="email" value="<?= $user->email ?>" class="form-control" placeholder="E-mail" required="">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="email_secondary">E-mail secundario</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="email_secondary" name="email_secondary" value="<?= $user->email_secondary ?>" class="form-control" placeholder="E-mail secundario">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="telephone">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" id="telephone" name="telephone" value="<?= $user->telephone ?>" class="form-control" placeholder="10 dígitos" maxlength="10" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="cellphone">Celular <span class="field-required">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-device-mobile"></i></span>
                <input type="text" id="cellphone" name="cellphone" value="<?= $user->cellphone ?>" class="form-control" placeholder="10 dígitos" required="" maxlength="10" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="ext">Extensión</label>
              <input type="text" id="ext" name="ext" value="<?= $user->ext ?>" class="form-control" placeholder="Ej. 1234" maxlength="5" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <label class="form-label">Foto de perfil</label>
            <div class="mb-3 d-flex align-items-center gap-3">
              <img class="rounded-circle" width="48" height="48" alt="<?= $user->name ?>" src="<?= base_url($user->photo) ?>" id="actualImage" style="object-fit:cover;">
              <input type="file" id="photo" name="photo" class="form-control" accept=".png, .jpg, .jpeg">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="employee_number">No. de empleado <span class="field-required">*</span></label>
              <input type="text" maxlength="10" id="employee_number" name="employee_number" value="<?= $user->employee_number ?>" class="form-control" placeholder="Número de empleado" required="" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="date_entry">
                Fecha de ingreso <?= $user->reingreso == 1 ? '<span class="badge-info ms-1">Reingreso</span>' : '' ?> <span class="field-required">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                <input type="date" id="date_entry" name="date_entry" value="<?= $user->date_entry ?>" class="form-control" required="" max="<?= date('Y-m-d') ?>">
              </div>
            </div>
          </div>
          <div id="date_discharge_container" class="col-md-6 col-lg-4" style="<?= $user->active == 1 ? 'display: none;' : '' ?>">
            <div class="mb-3">
              <label class="form-label" for="date_discharge">Fecha de baja <span class="field-required">*</span></label>
              <div class="input-group <?= $user->active == 1 ? 'input__error' : '' ?>" id="date_discharge_input_container">
                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                <input type="date" id="date_discharge" name="date_discharge" value="<?= $user->date_discharge ?>" class="form-control" <?= $user->date_discharge ? 'required' : '' ?> max="<?= date('Y-m-d') ?>">
              </div>
              <small class="field-required <?= $user->active == 1 ? 'd-none' : '' ?>" id="error__indicator">Seleccione fecha de baja</small>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="hide_emails" name="hide_emails" <?= $user->hide_emails == 1 ? 'checked' : '' ?>>
              <label class="form-check-label" for="hide_emails">Ocultar e-mails en directorio</label>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="show_in_directory" name="show_in_directory" <?= $user->show_in_directory == 1 ? 'checked' : '' ?>>
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
                <option value="<?= $user->ocupation ?>"><?= $user->ocupation_name ?></option>
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
                <option value="<?= $user->department ?>"><?= $user->department_name ?></option>
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
                <option value="<?= $user->area ?>"><?= $user->area_name ?></option>
                <?php foreach($areas as $area): ?>
                  <option value="<?= $area->id ?>"><?= $area->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="row">
              <div class="col-8">
                <label class="form-label">Jefe directo <span class="field-required">*</span></label>
                <select class="form-select select2" name="parent" id="parent" required <?= is_null($user->parent) ? 'disabled' : '' ?>>
                  <option value="<?= $user->parent ?>"><?= $user->has_ghost ? $user->real_parent_complete_name : $user->parent_name ?></option>
                  <?php foreach($users as $user_local): ?>
                    <?php if ($user_local->id != $user->id && !$user_local->ghost): ?>
                      <option value="<?= $user_local->id ?>"><?= $user_local->complete_name ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col d-flex align-items-end">
                <div class="d-flex align-items-center gap-1 mb-3">
                  <input class="form-check-input" type="checkbox" value="1" id="no_aplica" name="no_aplica" <?= is_null($user->parent) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="no_aplica">N/A</label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 d-flex align-items-end">
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="ghost" name="ghost"
                <?php if($user->has_ghost && $user->has_ghost != 0) echo 'checked'; ?>>
              <label class="form-check-label" for="ghost">Bajar nivel en organigrama</label>
            </div>
          </div>
          <?php if($user->has_ghost && $user->has_ghost != 0): ?>
          <div class="col-md-6 col-lg-4">
            <div class="mb-3">
              <label class="form-label" for="niveles">Cantidad de niveles (máximo 5)</label>
              <input type="number" class="form-control" id="niveles" name="niveles" max="5" min="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= $user->niveles ?>">
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Sección 3: Acceso al sistema -->
        <div class="form-section">
          <h6 class="form-section__title">Acceso al sistema</h6>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="mb-4">
              <label class="form-label">Rol <span class="field-required">*</span></label>
              <select class="form-select select2" name="rol" required>
                <option value="<?= $user->rol ?>">
                  <?php
                    if ($user->rol == 'admin') echo 'Administrador';
                    elseif ($user->rol == 'operator') echo 'Operador';
                    else echo 'Usuario';
                  ?>
                </option>
                <option value="user">Usuario</option>
                <option value="operator">Operador</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="mb-4">
              <label class="form-label" for="password">Restablecer contraseña</label>
              <input type="password" id="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>
          </div>
        </div>

        <div id="message__response" class="alert alert-success" style="display:none"></div>
        <?php if (session('message') !== null) : ?>
          <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
        <?php endif; ?>
        <?php if (session('success') !== null) : ?>
          <div class="alert alert-success mb-3"><?= session('success'); ?></div>
        <?php endif; ?>

        <?php if($user->active == 1): ?>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="employeDischargeSave">Actualizar empleado</button>
          <button type="button" class="btn btn-outline-danger" id="employeDischarge">Dar de baja</button>
        </div>
        <?php endif; ?>
        <?php if($user->active == 0): ?>
        <div class="form-actions">
          <button type="button" class="btn btn-outline-secondary" id="employeActivate">Reingresar empleado</button>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<!-- Modal reingreso -->
<div class="modal" id="reactivarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reactivarUsuario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reactivarUsuarioText">Reingresar empleado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">Selecciona la fecha de reingreso del empleado.</p>
        <div class="mb-3">
          <label class="form-label" for="date_re_entry">Fecha de ingreso <span class="field-required">*</span></label>
          <div class="input-group">
            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
            <input type="date" id="date_re_entry" name="date_re_entry" class="form-control" required="" max="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="employeReactivate">Reingresar y activar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var userId   = "<?= $user->id; ?>"
  var baseUrl  = "<?= base_url('auth/user'); ?>"
  var csrfName = '<?= $data['csrfName']; ?>';
  var csrfHash = '<?= $data['csrfHash']; ?>';
</script>

<script>
  $(document).ready(function() {
    $('.select2').select2({ placeholder: 'Selecciona', allowClear: true });

    $('#no_aplica').on('change', function() {
      if ($(this).is(':checked')) {
        $('#parent').val(null).trigger('change');
        $('#parent').prop('disabled', true).removeAttr('required');
      } else {
        $('#parent').prop('disabled', false).attr('required', 'required');
      }
    });

    if ($('#no_aplica').is(':checked')) {
      $('#parent').val(null).trigger('change');
      $('#parent').prop('disabled', true).removeAttr('required');
    }

    $('#employeDischarge').on('click', function() {
      $('#date_discharge_container').toggle();
      if ($('#date_discharge_container').is(':visible')) {
        $('#date_discharge').attr('required', 'required');
        $('#employeDischargeSave').html('Guardar y dar de baja');
        $('#employeDischargeSave').removeClass('btn-primary').addClass('btn-danger');
        $('#employeDischarge').removeClass('btn-outline-danger').addClass('btn-outline-secondary');
        $('#employeDischarge').html('Cancelar baja');
      } else {
        $('#date_discharge').removeAttr('required');
        $('#employeDischargeSave').html('Actualizar empleado');
        $('#employeDischargeSave').removeClass('btn-danger').addClass('btn-primary');
        $('#employeDischarge').removeClass('btn-outline-secondary').addClass('btn-outline-danger');
        $('#employeDischarge').html('Dar de baja');
      }
    });

    $('#date_discharge').on('change', function() {
      if ($('#date_discharge').val() !== '') {
        $('#date_discharge_input_container').removeClass('input__error');
        $('#error__indicator').hide();
      } else {
        $('#date_discharge_container').addClass('input__error');
        $('#error__indicator').show();
      }
    });

    $('#employeActivate').on('click', function() {
      $('#reactivarUsuario').modal('show');
    });

    $('#employeReactivate').on('click', function() {
      var id            = $('#id').val();
      var date_re_entry = $('#date_re_entry').val();
      if (date_re_entry === '') { alert('Por favor, selecciona una fecha de reingreso.'); return; }
      $.post({
        url: baseUrl + '/reactivate',
        data: { id: id, date_entry: date_re_entry, [csrfName]: csrfHash },
        success: function(response) {
          if(response.ok) { $('#reactivarUsuario').modal('hide'); location.reload(); }
          else { alert('Ocurrió un error al reingresar el empleado.'); }
        },
        error: function() { alert('Ocurrió un error al reingresar el empleado.'); }
      });
    });
  });
</script>
