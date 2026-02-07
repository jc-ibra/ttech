<div class="container-fluid mw-1600">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-semibold m-0">Nuevo empleado <small class="f-12" style="color:red;">Campos con * son obligatorios.</small></h5>
            <a href="<?= base_url('user') ?>" class="btn btn-outline-primary d-block">Volver</a>
          </div>
          <form method="post" class="py-4" action="<?= base_url('auth/register') ?>" enctype="multipart/form-data" id="register__form">
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Nombre(s) <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                    <input 
                      placeholder="Nombre(s)"
                      type="text" 
                      id="name" 
                      name="name" 
                      class="form-control" 
                      required=""
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Apellidos <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                    <input 
                      placeholder="Apellidos"
                      type="text" 
                      id="lastname" 
                      name="lastname" 
                      class="form-control" 
                      required=""
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
                      class="form-control" 
                      maxlength="5"
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Foto</label>
                  <input 
                    type="file" 
                    id="photo" 
                    name="photo" 
                    class="form-control" 
                    accept=".png, .jpg, .jpeg"
                  >
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
                      required=""
                      pattern="\d+"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Fecha de ingreso <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                    <input 
                    type="date" 
                    id="date_entry" 
                    name="date_entry" 
                    class="form-control" 
                    required=""
                    max="<?= date('Y-m-d') ?>"
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4 d-flex align-items-end">
                <div class="mb-3 form-check">
                  <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="hide_emails" 
                    name="hide_emails" 
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
                    <option value="">Selecciona un puesto</option>
                    <?php foreach($ocupations as $ocupation): ?>
                      <option value="<?= $ocupation->id ?>"><?= $ocupation->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-4">
                  <label class="form-label">Selecciona departamento</label>
                  <select class="form-select select2" name="department" >
                    <option value="">Selecciona un departamento</option>
                    <?php foreach($departments as $department): ?>
                      <option value="<?= $department->id ?>"><?= $department->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-4">
                  <label class="form-label">Selecciona area</label>
                  <select class="form-select select2" name="area" >
                    <option value="">Selecciona area</option>
                    <?php foreach($areas as $area): ?>
                      <option value="<?= $area->id ?>"><?= $area->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="row">
                  <div class="col-9">
                    <label class="form-label">Jefe directo <small style="color:red;">*</small></label>
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
                    <div class="d-block">
                      <div class="form-check" style="margin: 0;padding: 0;display: flex;">
                        <input class="form-check" type="checkbox" value="1" id="no_aplica" name="no_aplica">
                        <label class="form-check" for="no_aplica" style="margin: 0;padding: 0;padding-left: 5px;">
                          N/A
                        </label>
                      </div>
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
                  >
                  <label class="form-check-label" for="ghost">Bajar nivel en organigrama</label>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6 col-lg-4">
                <div class="mb-4">
                  <label class="form-label">Selecciona rol <small style="color:red;">*</small></label>
                  <select class="form-select select2" name="rol" required>
                    <option value="">Selecciona un rol</option>
                    <option value="user">Usuario</option>
                    <option value="operator">Operador</option>
                    <option value="admin">Administrador</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Password <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                    <input 
                      placeholder="Password"
                      type="password" 
                      id="password" 
                      name="password" 
                      class="form-control" 
                      required=""
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Confirma Password <small style="color:red;">*</small></label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                    <input 
                      placeholder="Confirma Password"
                      type="password" 
                      id="password-confirm" 
                      name="password-confirm" 
                      class="form-control" 
                      required=""
                    >
                  </div>
                </div>
              </div>
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
            <button type="submit" class="btn btn-outline-primary d-block">Crear usuario</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: 'Selecciona',
      allowClear: true
    });
  });

   // Función para habilitar/deshabilitar el select y agregar/quitar el atributo required
   $('#no_aplica').on('change', function() {
      if ($(this).is(':checked')) {
        $('#parent').val(null).trigger('change');
        $('#parent').prop('disabled', true).removeAttr('required');
      } else {
        $('#parent').prop('disabled', false).attr('required', 'required');
      }
    });
</script>

