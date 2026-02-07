<div class="container-fluid mw-1600">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-semibold m-0">Editar Organigrama: <?= $organigrama->name ?></h5>
            <a href="<?= base_url('custom-organigram') ?>" class="btn btn-outline-secondary">
              <i class="ti ti-arrow-left"></i> Volver
            </a>
          </div>

          <form id="formEditOrganigrama">
            <input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfHash ?>">
            <input type="hidden" name="id" value="<?= $organigrama->id ?>">
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nombre del Organigrama <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $organigrama->name ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="description" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="description" name="description" value="<?= $organigrama->description ?>">
              </div>
              <div class="col-md-12 mb-3">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="show_in_general" name="show_in_general" <?= $organigrama->show_in_general == 1 ? 'checked' : '' ?>>
                  <label class="form-check-label" for="show_in_general">
                    Mostrar en selector general de organigrama
                  </label>
                  <small class="d-block text-muted">Si está marcado, este organigrama estará disponible en el selector "Generales" del organigrama principal</small>
                </div>
              </div>
            </div>

            <div id="usersContainer">
              <div class="table-responsive">
                <table class="table table-bordered" id="tableUsers">
                  <thead>
                    <tr>
                      <th width="35%">Empleado</th>
                      <th width="35%">Jefe Directo</th>
                      <th width="15%">Niveles a bajar</th>
                      <th width="15%">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="userRows">
                  </tbody>
                </table>
              </div>
            </div>

            <div class="mt-4">
                <button type="button" class="btn btn-outline-primary me-2" id="btnAddUser">
                    <i class="ti ti-plus"></i> Agregar Empleado
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Actualizar Organigrama
                </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  const allUsers = <?= json_encode($allUsers) ?>;
  const organigramaUsers = <?= json_encode($organigramaUsers) ?>;
  let userRowCounter = 0;

  // Función para crear opciones de usuarios
  function getUserOptions() {
    return allUsers.map(user => 
      `<option value="${user.id}">${user.name} ${user.lastname} </option>`
    ).join('');
  }

  // Función para crear una fila de usuario
  function addUserRow(userData = null) {
    userRowCounter++;
    const rowId = `user-row-${userRowCounter}`;
    
    const userOptions = getUserOptions();
    const selectedUserId = userData ? userData.user_id : '';
    const selectedParentId = userData ? (userData.parent_id || '') : '';
    const niveles = userData ? (userData.niveles || 0) : 0;

    const row = `
      <tr id="${rowId}" data-row-id="${userRowCounter}">
        <td>
          <select class="form-select select2-user" name="users[${userRowCounter}][user_id]" required>
            <option value="">Seleccionar empleado</option>
            ${userOptions}
          </select>
        </td>
        <td>
          <select class="form-select select2-parent" name="users[${userRowCounter}][parent_id]">
            <option value="">Sin jefe directo (Raíz)</option>
            ${userOptions}
          </select>
        </td>
        <td>
          <input type="number" class="form-control" name="users[${userRowCounter}][niveles]" value="${niveles}" min="0" max="5">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-outline-danger btn-remove-user" data-row-id="${userRowCounter}">
            <i class="ti ti-trash"></i>
          </button>
        </td>
      </tr>
    `;

    $('#userRows').append(row);
    
    // Inicializar Select2 y establecer valores
    const $row = $(`#${rowId}`);
    $row.find('.select2-user, .select2-parent').select2({
      placeholder: 'Seleccionar...',
      allowClear: true,
      width: '100%'
    });

    if (selectedUserId) {
      $row.find('.select2-user').val(selectedUserId).trigger('change');
    }
    if (selectedParentId) {
      $row.find('.select2-parent').val(selectedParentId).trigger('change');
    }
  }

  // Cargar usuarios existentes
  if (organigramaUsers && organigramaUsers.length > 0) {
    organigramaUsers.forEach(userData => {
      addUserRow(userData);
    });
  } else {
    addUserRow(); // Agregar al menos una fila vacía
  }

  // Agregar empleado
  $('#btnAddUser').on('click', function() {
    addUserRow();
  });

  // Eliminar fila de usuario
  $(document).on('click', '.btn-remove-user', function() {
    const rowId = $(this).data('row-id');
    $(`#user-row-${rowId}`).remove();
  });

  // Submit del formulario
  $('#formEditOrganigrama').on('submit', function(e) {
    e.preventDefault();

    const formData = $(this).serializeArray();
    const data = {};
    const users = [];
    
    // Organizar datos
    formData.forEach(item => {
      if (item.name.startsWith('users[')) {
        const match = item.name.match(/users\[(\d+)\]\[(\w+)\]/);
        if (match) {
          const index = match[1];
          const field = match[2];
          
          if (!users[index]) {
            users[index] = {};
          }
          users[index][field] = item.value;
        }
      } else {
        data[item.name] = item.value;
      }
    });

    // Filtrar usuarios válidos
    data.users = users.filter(user => user && user.user_id);

    // Validar que haya al menos un usuario
    if (data.users.length === 0) {
      showMessage('alert-warning', 'Debe agregar al menos un empleado al organigrama');
      return;
    }

    // Enviar datos
    $.ajax({
      url: '<?= base_url('custom-organigram/update') ?>',
      type: 'POST',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        showLoader();
      },
      success: function(response) {
        hideLoader();
        if (response.status === 'success') {
          showMessage('alert-success', response.message);
          setTimeout(() => {
            window.location.href = '<?= base_url('custom-organigram') ?>';
          }, 1500);
        } else {
          showMessage('alert-danger', response.message);
        }
      },
      error: function(xhr) {
        hideLoader();
        console.error(xhr);
        showMessage('alert-danger', 'Ocurrió un error al actualizar el organigrama');
      }
    });
  });
});
</script>
