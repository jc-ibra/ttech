<?php
  $user       = $data['user'];
  $users      = $data['users'];
  $ocupations = $data['ocupations'];
  $departments = $data['departments'];
  $areas      = $data['areas'];
?>
<div class="container-fluid mw-1600">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-semibold m-0">Editar empleado</h5>
            <a href="<?= base_url('user') ?>" class="btn btn-outline-primary d-block">Volver</a>
          </div>
          <form method="post" class="py-4" action="<?= base_url('auth/user/update') ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Nombre(s) <small style="color:red;">*</small></label>
                  <input 
                    type="hidden" 
                    id="id" 
                    name="id"
                    value="<?= $user->id ?>"
                  >
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                    <input 
                      placeholder="Nombre(s)"
                      type="text" 
                      id="name" 
                      name="name"
                      value="<?= $user->name ?>"
                      class="form-control" 
                      required=""
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-4">
                  <label class="form-label">Apellidos <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                    <input 
                      placeholder="Apellidos"
                      type="text" 
                      id="lastname" 
                      value="<?= $user->lastname ?>"
                      name="lastname" 
                      class="form-control" 
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">E-mail <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                    <input 
                      placeholder="E-mail"
                      type="email" 
                      id="email" 
                      name="email" 
                      value="<?= $user->email ?>"
                      class="form-control" 
                      required=""
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">E-mail secundario</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                    <input 
                      placeholder="E-mail secundario"
                      type="email" 
                      id="email_secondary" 
                      name="email_secondary"" 
                      value="<?= $user->email_secondary ?>"
                      class="form-control" 
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Teléfono</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input 
                      placeholder="Teléfono"
                      type="text" 
                      id="telephone" 
                      name="telephone" 
                      value="<?= $user->telephone ?>"
                      class="form-control" 
                      maxlength="10"
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Celular <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input 
                      placeholder="Celular"
                      type="text" 
                      id="cellphone" 
                      name="cellphone" 
                      value="<?= $user->cellphone ?>"
                      class="form-control" 
                      required=""
                      maxlength="10"
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Extensión</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input 
                      placeholder="Extensión"
                      type="text" 
                      id="ext" 
                      name="ext" 
                      value="<?= $user->ext ?>"
                      class="form-control" 
                      maxlength="5"
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <label class="form-label">Foto</label>
                <div class="mb-4 d-flex align-items-center">
                  <img
                    class="rounded-circle" width="50" height="50"
                    alt="<?= $user->name ?>"
                    src="<?= base_url( $user->photo) ?>"
                    id="actualImage"
                  />
                  <div class="ms-2 w-100">
                    <input 
                      type="file" 
                      id="photo" 
                      name="photo" 
                      class="form-control" 
                      accept=".png, .jpg, .jpeg"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Número de Empleado <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                    <input 
                      placeholder="Número de Empleado"
                      type="text"
                      maxlength="10" 
                      id="employee_number" 
                      name="employee_number" 
                      class="form-control" 
                      value="<?= $user->employee_number ?>"
                      required=""
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-lg-4">
                  <div class="mb-3">
                    <label class="form-label">Fecha de ingreso <?= $user->reingreso == 1 ? '(Reingreso)' : '' ?> <small style="color:red;">*</small></label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        <input 
                          type="date" 
                          id="date_entry" 
                          name="date_entry" 
                          class="form-control" 
                          value="<?= $user->date_entry ?>"
                          required=""
                          max="<?= date('Y-m-d') ?>"
                        >
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4" id="date_discharge_container" style="<?= $user->active == 1 ? 'display: none;' : '' ?>">
                  <div class="mb-3">
                    <label class="form-label">Fecha de baja <small style="color:red;">*</small></label>
                    <div class="input-group <?= $user->active == 1 ? 'input__error' : '' ?>" id="date_discharge_input_container">
                      <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        <input 
                          type="date" 
                          id="date_discharge" 
                          name="date_discharge" 
                          class="form-control" 
                          value="<?= $user->date_discharge ?>"
                          <?= $user->date_discharge ? 'required' : '' ?>
                          max="<?= date('Y-m-d') ?>"
                        >
                    </div>
                    <small style="color:red;<?= $user->active == 1 ? '' : 'display:none' ?>" id="error__indicator">Seleccione fecha de baja</small>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 d-flex align-items-end">
                  <div class="mb-3 form-check">
                    <input 
                      type="checkbox" 
                      class="form-check-input" 
                      id="hide_emails" 
                      name="hide_emails" 
                      <?= $user->hide_emails == 1 ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="hide_emails">Ocultar E-mails del directorio</label>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 d-flex align-items-end">
                  <div class="mb-3 form-check">
                    <input 
                      type="checkbox" 
                      class="form-check-input" 
                      id="show_in_directory" 
                      name="show_in_directory" 
                      <?= $user->show_in_directory == 1 ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="show_in_directory">Mostrar en directorio</label>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6 col-lg-4">
                  <div class="mb-4">
                    <label class="form-label">Selecciona puesto <small style="color:red;">*</small></label>
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
                    <label class="form-label">Selecciona departamento</label>
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
                    <label class="form-label">Selecciona area</label>
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
                      <div class="">
                        <label class="form-label">Selecciona jefe directo <small style="color:red;">*</small></label>
                        <select class="form-select select2" name="parent" id="parent" required <?= is_null($user->parent) ? 'disabled' : '' ?>>
                          <option value="<?= $user->parent ?>"><?= $user->has_ghost ? $user->real_parent_complete_name : $user->parent_name ?></option>
                          <?php foreach($users as $user_local): ?>
                          <?php if ($user_local->id != $user->id): ?>
                            <?php if( !$user_local->ghost ): ?>
                              <option value="<?= $user_local->id ?>"><?= $user_local->complete_name ?></option>
                            <?php endif; ?>
                          <?php endif; ?>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col d-flex align-items-end">
                      <div class="form-check" style="margin: 0;padding: 0;display: flex;">
                        <input class="form-check" type="checkbox" value="1" id="no_aplica" name="no_aplica" <?= is_null($user->parent) ? 'checked' : '' ?>>
                        <label class="form-check" for="no_aplica" style="margin: 0;padding: 0;padding-left: 5px;">
                          N/A
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 d-flex align-items-end">
                  <div class="mb-3 form-check">
                    <input 
                      type="checkbox" 
                      class="form-check-input" 
                      id="ghost" 
                      name="ghost" 
                      <?php 
                        if($user->has_ghost && $user->has_ghost != 0){
                          echo 'checked';
                        }
                      ?>
                    >
                    <label class="form-check-label" for="ghost">Bajar nivel en organigrama</label>
                  </div>
                </div>
                <?php if($user->has_ghost && $user->has_ghost != 0): ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-end">
                  <div class="mb-3">
                    <label class="form-label">Cantidad de niveles (máximo 5)</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ti ti-number"></i></span>
                        <input 
                          type="number" 
                          class="form-control" 
                          id="niveles" 
                          name="niveles" 
                          max="5"
                          min="1"
                          oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                          value="<?= $user->niveles ?>"
                        >
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              </div>
              <hr class="mt-4">
              <div class="row">
                <div class="col-md-6 col-lg-4">
                  <div class="mb-4">
                    <label class="form-label">Rol <small style="color:red;">*</small></label>
                    <select class="form-select select2" name="rol" required>
                      <option value="<?= $user->rol ?>">
                        <?php
                          if ($user->rol == 'admin') {
                            echo 'Administrador';
                          } elseif ($user->rol == 'operator') {
                            echo 'Operador';
                          } else {
                            echo 'Usuario';
                          }
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
                    <label class="form-label">Restablecer password</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ti ti-lock"></i></span>
                      <input 
                        placeholder="Escribe nueva contraseña"
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="message__response" class="alert alert-success" style="display:none">
            </div>
            <?php if (session('message') !== null) : ?>
              <div class="alert alert-danger">
                <?= session('message'); ?>
              </div>
            <?php endif; ?>
            <?php if (session('success') !== null) : ?>
              <div class="alert alert-success">
                <?= session('success'); ?>
              </div>
            <?php endif; ?>
            <?php if($user->active == 1): ?>
            <div class="d-flex">
              <button type="submit" class="btn btn-outline-primary d-block" id="employeDischargeSave">Actualizar usuario</button>
              <button type="button" class="btn btn-outline-danger d-block ms-2" id="employeDischarge">Dar de baja empleado y desactivar</button>
            </div>
            <?php endif; ?>
            <?php if($user->active == 0): ?>
              <div class="d-flex">
                <!-- <button type="submit" class="btn btn-outline-primary d-block" id="employeDischargeSave">Actualizar usuario</button> -->
                <button type="button" class="btn btn-outline-danger d-block ms-2" id="employeActivate">Reingresar empleado</button>
              </div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="reactivarUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reactivarUsuario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="reactivarUsuarioText">Reingresar empleado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body feed">
        <p>Selecciona fecha de reingreso para el empleado</p>
        <div class="mb-3">
          <label class="form-label">Fecha de ingreso <small style="color:red;">*</small></label>
          <div class="input-group">
            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
              <input 
                type="date" 
                id="date_re_entry" 
                name="date_re_entry" 
                class="form-control" 
                required=""
                max="<?= date('Y-m-d') ?>"
              >
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-outline-danger d-block ms-2" id="employeReactivate">Reingresar empleado y activar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var userId      = "<?= $user->id; ?>"
  var baseUrl     = "<?= base_url('auth/user'); ?>"
  var csrfName    = '<?= $data['csrfName']; ?>';
  var csrfHash    = '<?= $data['csrfHash']; ?>';
</script>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: 'Selecciona',
      allowClear: true
    });

    // Función para habilitar/deshabilitar el select y agregar/quitar el atributo required
    $('#no_aplica').on('change', function() {
      if ($(this).is(':checked')) {
        $('#parent').val(null).trigger('change'); // Limpiar el select2
        $('#parent').prop('disabled', true).removeAttr('required');
      } else {
        $('#parent').prop('disabled', false).attr('required', 'required');
      }
    });

    // Verificar el estado inicial del checkbox y ajustar el select
    if ($('#no_aplica').is(':checked')) {
      $('#parent').val(null).trigger('change'); // Limpiar el select2
      $('#parent').prop('disabled', true).removeAttr('required');
    }

    // Función para mostrar/ocultar el campo de fecha de baja
    $('#employeDischarge').on('click', function() {
      $('#date_discharge_container').toggle();
      if ($('#date_discharge_container').is(':visible')) {
        $('#date_discharge').attr('required', 'required');
        $('#employeDischargeSave').html('Guardar y dar de baja');
        $('#employeDischargeSave').removeClass('btn-outline-primary').addClass('btn-danger');
        $('#employeDischarge').removeClass('btn-outline-danger').addClass('btn-outline-primary');
        $('#employeDischarge').html('Cancelar baja');
      } else {
        $('#date_discharge').removeAttr('required');
        $('#employeDischargeSave').html('Actualizar usuario');
        $('#employeDischargeSave').removeClass('btn-danger').addClass('btn-outline-primary');
        $('#employeDischarge').removeClass('btn-outline-primary').addClass('btn-outline-danger');
        $('#employeDischarge').html('Dar de baja empleado y desactivar');
      }
    });

    // Quita la clase de error al input
    $('#date_discharge').on('change', function(e) {

      if ($('#date_discharge').val() !== '') {
        $('#date_discharge_input_container').removeClass('input__error');
        $('#error__indicator').hide();
      } else {
        $('#date_discharge_container').addClass('input__error');
        $('#error__indicator').show();
      }
      
    });

    // Activar empleado modal
    $('#employeActivate').on('click', function() {
      $('#reactivarUsuario').modal('show');
    });

    // Reingresar empleado y activar
    $('#employeReactivate').on('click', function() {

      var id            = $('#id').val();
      var date_re_entry = $('#date_re_entry').val();

      if (date_re_entry === '') {
        alert('Por favor, selecciona una fecha de reingreso.');
        return;
      }
      
      $.post({
        url: baseUrl + '/reactivate',
        data: {
          id: id,
          date_entry: date_re_entry,
          [csrfName]: csrfHash
        },
        success: function(response) {
          if(response.ok){
            // Cerrar el modal
            $('#reactivarUsuario').modal('hide');
            // Recargar la página o actualizar la interfaz según sea necesario
            location.reload();
          } else {
            alert('Ocurrió un error al reingresar el empleado.');
          }
        },
        error: function(xhr, status, error) {
          // Manejar errores
          alert('Ocurrió un error al reingresar el empleado.');
        }
      });
    });
});

</script>