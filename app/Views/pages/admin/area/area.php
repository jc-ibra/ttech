<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <a href="<?= base_url('catalogos') ?>" class="catalog-back">
        <i class="ti ti-arrow-left"></i> Volver a Catálogos
      </a>
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Areas</h5>
          <p class="page-header__subtitle">Gestión de areas de la empresa</p>
        </div>
        <a href="<?= base_url('area/new') ?>" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i>Nuevo
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle w-100 js-datatable" id="dt_table" data-order-col="1" data-order-dir="asc" data-no-export="true">
          <thead>
            <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($areas as $area): ?>
              <tr>
                <td><?= $area->id ?></td>
                <td><?= $area->name ?></td>
                <td>
                  <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('area/edit/'.$area->id) ?>" aria-label="Editar">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger removeItem" itemId="<?= $area->id ?>" aria-label="Eliminar">
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

<script>
  var deleteURL     = "<?= base_url('area/delete'); ?>"
  var csrfName      = '<?= $csrfName ?>';
  var csrfHash      = '<?= $csrfHash ?>';
</script>