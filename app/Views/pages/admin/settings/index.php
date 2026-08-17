<?php
  // Interruptores de visibilidad de secciones.
  $toggles = [
    [
      'key'   => 'organization_enabled',
      'title' => 'Organigramas',
      'desc'  => 'Muestra u oculta la sección de organigramas (/organization) y su acceso en el menú. El administrador conserva el acceso para poder revisarla.',
      'icon'  => 'ti ti-hierarchy-2',
      'link'  => base_url('organization'),
    ],
    [
      'key'   => 'external_systems_enabled',
      'title' => 'Sistemas externos',
      'desc'  => 'Muestra u oculta el bloque de accesos rápidos (Help Desk, GLPI, Correo Staff, …) en "Mi espacio" y en los atajos del feed.',
      'icon'  => 'ti ti-external-link',
      'link'  => base_url('profile'),
    ],
  ];

  // Iconos sugeridos para los sistemas externos (set Tabler ya incluido).
  $iconOptions = [
    'ti ti-ticket', 'ti ti-book', 'ti ti-mail', 'ti ti-world', 'ti ti-cloud',
    'ti ti-device-laptop', 'ti ti-lifebuoy', 'ti ti-calendar', 'ti ti-chart-bar',
    'ti ti-lock', 'ti ti-users', 'ti ti-briefcase', 'ti ti-external-link',
  ];
?>
<div class="container-fluid mw-1600">

  <div class="page-header mb-4">
    <div>
      <h5 class="page-header__title">Configuración</h5>
      <p class="page-header__subtitle">Controla qué secciones se muestran en la intranet y administra los accesos a sistemas externos</p>
    </div>
  </div>

  <?php if (session('message') !== null) : ?>
    <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
  <?php endif; ?>
  <?php if (session('success') !== null) : ?>
    <div class="alert alert-success mb-3"><?= session('success'); ?></div>
  <?php endif; ?>

  <!-- VISIBILIDAD DE SECCIONES -->
  <div class="card">
    <div class="card-body p-4">
      <h6 class="fw-bold mb-1"><i class="ti ti-eye me-1"></i>Visibilidad de secciones</h6>
      <p class="text-muted small mb-3">Al apagar una sección deja de mostrarse en el menú y su acceso queda bloqueado para los usuarios.</p>

      <form method="post" action="<?= base_url('configuracion/update') ?>">
        <?= csrf_field(); ?>

        <?php foreach ($toggles as $t): ?>
          <?php $on = ($settings[$t['key']] ?? '1') === '1'; ?>
          <div class="setting-row">
            <span class="setting-row__icon"><i class="<?= $t['icon'] ?>"></i></span>
            <div class="setting-row__body">
              <span class="setting-row__title">
                <?= esc($t['title']) ?>
                <?php if (!$on): ?><span class="setting-badge">Oculto</span><?php endif; ?>
              </span>
              <span class="setting-row__desc"><?= esc($t['desc']) ?></span>
            </div>
            <div class="setting-row__actions">
              <a href="<?= $t['link'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver sección">
                <i class="ti ti-external-link"></i>
              </a>
              <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="<?= $t['key'] ?>" name="<?= $t['key'] ?>" value="1" <?= $on ? 'checked' : '' ?>>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="form-actions mt-3">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>Guardar visibilidad
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- SISTEMAS EXTERNOS -->
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Sistemas externos</h5>
          <p class="page-header__subtitle">Enlaces que se muestran en "Mi espacio" y en los atajos del feed</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnNewSystem">
          <i class="ti ti-plus me-1"></i>Nuevo
        </button>
      </div>

      <?php if (($settings['external_systems_enabled'] ?? '1') !== '1'): ?>
        <div class="alert alert-warning">
          <i class="ti ti-eye-off me-1"></i>El bloque está oculto para los usuarios. Actívalo arriba para que estos enlaces se muestren.
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle w-100">
          <thead>
            <tr>
              <th style="width:60px;">Orden</th>
              <th>Nombre</th>
              <th>URL</th>
              <th style="width:110px;">Estado</th>
              <th style="width:110px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($systems)): ?>
              <tr>
                <td colspan="5" class="text-muted text-center py-4">Aún no hay sistemas externos dados de alta.</td>
              </tr>
            <?php endif; ?>
            <?php foreach ($systems as $s): ?>
              <tr>
                <td><?= (int) $s->sort_order ?></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <span class="system-icon-preview"><i class="<?= esc($s->icon, 'attr') ?>"></i></span>
                    <div>
                      <span class="fw-semibold d-block"><?= esc($s->title) ?></span>
                      <span class="text-muted small"><?= esc($s->description) ?></span>
                    </div>
                  </div>
                </td>
                <td class="text-break"><a href="<?= esc($s->url, 'attr') ?>" target="_blank" rel="noopener"><?= esc($s->url) ?></a></td>
                <td>
                  <?php if ((int) $s->active === 1): ?>
                    <span class="badge bg-success-subtle text-success">Visible</span>
                  <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary">Oculto</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary editSystem"
                            data-id="<?= $s->id ?>"
                            data-title="<?= esc($s->title, 'attr') ?>"
                            data-description="<?= esc($s->description, 'attr') ?>"
                            data-icon="<?= esc($s->icon, 'attr') ?>"
                            data-url="<?= esc($s->url, 'attr') ?>"
                            data-sort="<?= (int) $s->sort_order ?>"
                            data-active="<?= (int) $s->active ?>"
                            aria-label="Editar">
                      <i class="ti ti-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger removeItem" itemId="<?= $s->id ?>" aria-label="Eliminar">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal: alta / edición de sistema externo -->
<div class="modal fade" id="systemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="systemModalTitle">Nuevo sistema externo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="systemForm" autocomplete="off" novalidate>
          <input type="hidden" name="id" id="systemId">

          <div class="mb-3">
            <label class="form-label" for="systemTitle">Nombre <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="systemTitle" name="title" maxlength="150" required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="systemUrl">URL <span class="text-danger">*</span></label>
            <input type="url" class="form-control" id="systemUrl" name="url" placeholder="https://" maxlength="500" required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="systemDescription">Descripción</label>
            <textarea class="form-control" id="systemDescription" name="description" rows="2" maxlength="255"></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-7">
              <label class="form-label" for="systemIcon">Icono</label>
              <div class="input-group">
                <span class="input-group-text"><i id="systemIconPreview" class="ti ti-external-link"></i></span>
                <input type="text" class="form-control" id="systemIcon" name="icon" list="iconOptions" placeholder="ti ti-external-link">
              </div>
              <datalist id="iconOptions">
                <?php foreach ($iconOptions as $ic): ?>
                  <option value="<?= $ic ?>"></option>
                <?php endforeach; ?>
              </datalist>
              <span class="text-muted small">Clase de icono Tabler, ej. <code>ti ti-ticket</code>.</span>
            </div>
            <div class="col-md-5">
              <label class="form-label" for="systemSort">Orden</label>
              <input type="number" class="form-control" id="systemSort" name="sort_order" value="0" min="0">
            </div>
          </div>

          <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" role="switch" id="systemActive" name="active" value="1" checked>
            <label class="form-check-label" for="systemActive">Visible para los usuarios</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="systemSubmit">
          <i class="ti ti-device-floppy me-1"></i>Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  var deleteURL = "<?= base_url('configuracion/sistemas/delete'); ?>";
  var saveURL   = "<?= base_url('configuracion/sistemas/save'); ?>";
  var csrfName  = '<?= $csrfName ?>';
  var csrfHash  = '<?= $csrfHash ?>';

  // Bootstrap se carga en shared/footer, es decir después de esta vista: por eso
  // todo se engancha en $(document).ready y la instancia del modal se crea al vuelo.
  $(function () {
    var modalInstance = null;

    function modal() {
      if (!modalInstance) {
        modalInstance = new bootstrap.Modal(document.getElementById('systemModal'));
      }
      return modalInstance;
    }

    function resetForm() {
      $('#systemForm')[0].reset();
      $('#systemId').val('');
      $('#systemIconPreview').attr('class', 'ti ti-external-link');
      $('#systemActive').prop('checked', true);
    }

    $(document).on('click', '#btnNewSystem', function () {
      resetForm();
      $('#systemModalTitle').text('Nuevo sistema externo');
      modal().show();
    });

    $(document).on('click', '.editSystem', function () {
      resetForm();
      var $b = $(this);
      $('#systemModalTitle').text('Editar sistema externo');
      $('#systemId').val($b.data('id'));
      $('#systemTitle').val($b.data('title'));
      $('#systemUrl').val($b.data('url'));
      $('#systemDescription').val($b.data('description'));
      $('#systemIcon').val($b.data('icon'));
      $('#systemSort').val($b.data('sort'));
      $('#systemActive').prop('checked', String($b.data('active')) === '1');
      $('#systemIconPreview').attr('class', $b.data('icon') || 'ti ti-external-link');
      modal().show();
    });

    // Vista previa del icono mientras se escribe.
    $(document).on('input', '#systemIcon', function () {
      $('#systemIconPreview').attr('class', $(this).val() || 'ti ti-external-link');
    });

    $(document).on('click', '#systemSubmit', function () {
      var title = $.trim($('#systemTitle').val());
      var url   = $.trim($('#systemUrl').val());

      if (!title || !url) {
        showMessage('alert-danger', 'El nombre y la URL son obligatorios.');
        return;
      }

      var payload = {
        id:          $('#systemId').val(),
        title:       title,
        url:         url,
        description: $('#systemDescription').val(),
        icon:        $.trim($('#systemIcon').val()),
        sort_order:  $('#systemSort').val() || 0
      };
      if ($('#systemActive').is(':checked')) {
        payload.active = 1;
      }
      payload[csrfName] = csrfHash;

      $.post(saveURL, payload, handleResponseWithReload, 'json')
        .fail(function () { showMessage('alert-danger', 'Error en la solicitud.'); });
    });
  })();
</script>

<style>
  .setting-row {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--color-neutral-200);
  }
  .setting-row:last-of-type { border-bottom: none; }
  .setting-row__icon {
    width: 42px; height: 42px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 11px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.3rem;
  }
  .setting-row__body { flex: 1 1 auto; min-width: 0; }
  .setting-row__title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.98rem; font-weight: 700; color: var(--text-primary);
  }
  .setting-row__desc { display: block; font-size: 0.83rem; color: var(--text-muted); line-height: 1.45; }
  .setting-row__actions { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }
  .setting-row .form-check-input { width: 2.6rem; height: 1.35rem; cursor: pointer; }
  .setting-badge {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
    color: var(--text-muted);
    background: var(--color-neutral-100);
    border-radius: 50px;
    padding: 0.1rem 0.55rem;
  }
  .system-icon-preview {
    width: 34px; height: 34px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 9px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.1rem;
  }
</style>
