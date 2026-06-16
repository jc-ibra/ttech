<?php
  $ocupation = $data['ocupation'];
?>
<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Editar puesto</h5>
          <p class="page-header__subtitle">Modifica el nombre del puesto de trabajo</p>
        </div>
        <a href="<?= base_url('ocupation') ?>" class="btn btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Volver
        </a>
      </div>
      <form method="post" action="<?= base_url('ocupation/edit') ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" id="id" name="id" value="<?= $ocupation->id ?>">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-4">
              <label class="form-label" for="name">Nombre del puesto</label>
              <input
                type="text"
                id="name"
                name="name"
                value="<?= $ocupation->name ?>"
                class="form-control"
                placeholder="Nombre puesto"
                required=""
              >
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
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Actualizar puesto</button>
          <a href="<?= base_url('ocupation') ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
