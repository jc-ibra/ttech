<div class="container-fluid mw-1600">
  <form method="post" action="<?= base_url('landing/update') ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="card">
      <div class="card-body p-4">
        <div class="page-header">
          <div>
            <h5 class="page-header__title">Contenido Trantor Technologies</h5>
            <p class="page-header__subtitle">Edita los textos e imágenes de la página. La estructura y los enlaces se mantienen.</p>
          </div>
          <div class="d-flex gap-2">
            <a href="<?= base_url('trantor-technologies') ?>" target="_blank" class="btn btn-outline-secondary">
              <i class="ti ti-external-link me-1"></i>Ver página
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-1"></i>Guardar cambios
            </button>
          </div>
        </div>

        <?php if (session('message') !== null) : ?>
          <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
        <?php endif; ?>
        <?php if (session('success') !== null) : ?>
          <div class="alert alert-success mb-3"><?= session('success'); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- HERO -->
    <div class="card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="ti ti-photo me-1"></i>Encabezado (Hero)</h6>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Imagen de fondo</label>
            <img src="<?= base_url($c['hero_image']) ?>" alt="Hero" class="img-fluid rounded mb-2 d-block" style="max-height:140px;object-fit:cover;" data-preview="hero_image">
            <input type="file" name="hero_image" class="form-control" accept="image/*" data-preview-input="hero_image">
          </div>
          <div class="col-md-8">
            <div class="row g-3">
              <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="hero_title" class="form-control" value="<?= esc($c['hero_title'], 'attr') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label">Palabra destacada</label>
                <input type="text" name="hero_highlight" class="form-control" value="<?= esc($c['hero_highlight'], 'attr') ?>">
              </div>
              <div class="col-12">
                <label class="form-label">Subtítulo</label>
                <input type="text" name="hero_subtitle" class="form-control" value="<?= esc($c['hero_subtitle'], 'attr') ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MISIÓN / VISIÓN / VALORES -->
    <div class="card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="ti ti-target me-1"></i>Misión, Visión y Valores</h6>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Título Misión</label>
            <input type="text" name="mission_title" class="form-control mb-2" value="<?= esc($c['mission_title'], 'attr') ?>">
            <label class="form-label">Texto Misión</label>
            <textarea name="mission_text" class="form-control" rows="5"><?= esc($c['mission_text']) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Título Visión</label>
            <input type="text" name="vision_title" class="form-control mb-2" value="<?= esc($c['vision_title'], 'attr') ?>">
            <label class="form-label">Texto Visión</label>
            <textarea name="vision_text" class="form-control" rows="5"><?= esc($c['vision_text']) ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Título Valores</label>
            <input type="text" name="values_title" class="form-control" value="<?= esc($c['values_title'], 'attr') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Lista de valores <span class="text-muted small">(uno por línea)</span></label>
            <textarea name="values_list" class="form-control" rows="5"><?= esc($c['values_list']) ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTADORES -->
    <div class="card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="ti ti-number me-1"></i>Contadores</h6>
        <div class="row g-3">
          <?php foreach ([1, 2, 3, 4] as $i): ?>
            <div class="col-md-3">
              <label class="form-label">Contador <?= $i ?> — Valor</label>
              <input type="text" name="counter<?= $i ?>_value" class="form-control mb-2" value="<?= esc($c['counter' . $i . '_value'], 'attr') ?>">
              <label class="form-label">Etiqueta</label>
              <input type="text" name="counter<?= $i ?>_label" class="form-control" value="<?= esc($c['counter' . $i . '_label'], 'attr') ?>">
            </div>
          <?php endforeach ?>
        </div>
        <p class="text-muted small mt-2 mb-0">El valor debe ser numérico para que funcione la animación de conteo.</p>
      </div>
    </div>

    <!-- SECCIONES / TARJETAS -->
    <div class="card">
      <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="ti ti-layout-cards me-1"></i>Secciones de la intranet</h6>
        <div class="mb-4">
          <label class="form-label">Título de la sección</label>
          <input type="text" name="sections_title" class="form-control" value="<?= esc($c['sections_title'], 'attr') ?>">
        </div>

        <?php
          $cardTargets = [1 => 'Trantor Informa', 2 => 'Documentos', 3 => 'Quejas y Sugerencias', 4 => 'Directorio'];
        ?>
        <div class="row g-3">
          <?php foreach ([1, 2, 3, 4] as $i): ?>
            <div class="col-md-6">
              <div class="border rounded p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-semibold">Tarjeta <?= $i ?></span>
                  <span class="badge-neutral">Enlaza a: <?= esc($cardTargets[$i]) ?></span>
                </div>
                <img src="<?= base_url($c['card' . $i . '_image']) ?>" alt="Tarjeta <?= $i ?>" class="img-fluid rounded mb-2 d-block" style="max-height:120px;object-fit:cover;" data-preview="card<?= $i ?>_image">
                <input type="file" name="card<?= $i ?>_image" class="form-control mb-2" accept="image/*" data-preview-input="card<?= $i ?>_image">
                <label class="form-label">Título</label>
                <input type="text" name="card<?= $i ?>_title" class="form-control mb-2" value="<?= esc($c['card' . $i . '_title'], 'attr') ?>">
                <label class="form-label">Descripción</label>
                <textarea name="card<?= $i ?>_text" class="form-control mb-2" rows="3"><?= esc($c['card' . $i . '_text']) ?></textarea>
                <label class="form-label">Texto del botón</label>
                <input type="text" name="card<?= $i ?>_button" class="form-control" value="<?= esc($c['card' . $i . '_button'], 'attr') ?>">
              </div>
            </div>
          <?php endforeach ?>
        </div>

        <div class="form-actions mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>Guardar cambios
          </button>
        </div>
      </div>
    </div>

  </form>
</div>

<script>
  // Previsualización de imágenes al seleccionar un archivo.
  $(document).on('change', '[data-preview-input]', function () {
    var key = $(this).data('preview-input');
    var file = this.files && this.files[0];
    if (!file) return;
    var img = document.querySelector('[data-preview="' + key + '"]');
    if (img) img.src = URL.createObjectURL(file);
  });
</script>
