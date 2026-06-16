<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Nueva area</h5>
          <p class="page-header__subtitle">Completa el nombre para crear el area</p>
        </div>
        <a href="<?= base_url('area') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>
      <form method="post" action="<?= base_url('area/new') ?>">
        <?php echo csrf_field(); ?>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-4">
              <label class="form-label" for="name">Nombre del area</label>
              <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                placeholder="Ej. Ventas Norte"
                required=""
              >
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
          <button type="submit" class="btn btn-primary">Crear area</button>
          <a href="<?= base_url('area') ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
