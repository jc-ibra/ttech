<div class="container-fluid mw-1600">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Organigramas personalizados</h5>
          <p class="page-header__subtitle">Crea y gestiona organigramas del equipo</p>
        </div>
        <?php if(session()->get('user')->rol === 'admin'): ?>
        <a href="<?= base_url('custom-organigram/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i>Crear organigrama
        </a>
        <?php endif; ?>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="dt_organigramas">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Fecha creación</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($organigramas)): ?>
              <tr>
                <td colspan="4" class="text-center py-5">
                  <i class="ti ti-sitemap" style="font-size:2.5rem; color:var(--color-neutral-300);"></i>
                  <p class="text-muted mt-2 mb-0">No hay organigramas creados aún</p>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach($organigramas as $organigrama): ?>
                <tr>
                  <td class="fw-semibold"><?= $organigrama->name ?></td>
                  <td><?= $organigrama->description ?? '-' ?></td>
                  <td><?= date('d/m/Y', strtotime($organigrama->created_at)) ?></td>
                  <td>
                        <div class="d-flex gap-2">
                          <a href="<?= base_url('custom-organigram/view/'.$organigrama->id) ?>" 
                             class="btn btn-sm btn-outline-primary" 
                             title="Ver organigrama">
                            <i class="ti ti-eye"></i>
                          </a>
                          <?php // Solo mostrar editar y eliminar si el rol es admin 
                            if(session()->get('user')->rol === 'admin'):
                          ?>

                          <a href="<?= base_url('custom-organigram/edit/'.$organigrama->id) ?>" 
                             class="btn btn-sm btn-outline-secondary" 
                             title="Editar">
                            <i class="ti ti-pencil"></i>
                          </a>
                          <button class="btn btn-sm btn-outline-info btn-clone" 
                                  data-id="<?= $organigrama->id ?>"
                                  data-name="<?= $organigrama->name ?>"
                                  title="Clonar">
                            <i class="ti ti-copy"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger btn-delete" 
                                  data-id="<?= $organigrama->id ?>"
                                  data-name="<?= $organigrama->name ?>"
                                  title="Eliminar">
                            <i class="ti ti-trash"></i>
                          </button>
                            <?php endif; ?>
                        </div>
                      </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Clonar organigrama
  $('.btn-clone').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');

    const newName = prompt(`Ingrese el nombre para el nuevo organigrama clonado de "${name}":`, `${name} (Copia)`);
    
    if (!newName || newName.trim() === '') {
      return;
    }

    showLoader();
    $.ajax({
      url: '<?= base_url('custom-organigram/clone') ?>',
      type: 'POST',
      data: {
        id: id,
        new_name: newName.trim(),
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
      },
      success: function(response) {
        hideLoader();
        if (response.status === 'success') {
          showMessage('alert-success', response.message);
          setTimeout(() => {
            location.reload();
          }, 1500);
        } else {
          showMessage('alert-danger', response.message);
        }
      },
      error: function() {
        hideLoader();
        showMessage('alert-danger', 'Ocurrió un error al clonar el organigrama');
      }
    });
  });

  // Eliminar organigrama
  $('.btn-delete').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');

    if (!confirm(`¿Estás seguro de que deseas eliminar el organigrama "${name}"? Esta acción no se puede deshacer.`)) {
      return;
    }

    showLoader();
    $.ajax({
      url: '<?= base_url('custom-organigram/delete') ?>',
      type: 'POST',
      data: {
        id: id,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
      },
      success: function(response) {
        hideLoader();
        if (response.status === 'success') {
          showMessage('alert-success', response.message);
          setTimeout(() => {
            location.reload();
          }, 1500);
        } else {
          showMessage('alert-danger', response.message);
        }
      },
      error: function() {
        hideLoader();
        showMessage('alert-danger', 'Ocurrió un error al eliminar');
      }
    });
  });
});
</script>
