<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <a href="<?= base_url('catalogos') ?>" class="catalog-back">
        <i class="ti ti-arrow-left"></i> Volver a Catálogos
      </a>
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Puestos</h5>
          <p class="page-header__subtitle">Gestión de puestos de trabajo</p>
        </div>
        <a href="<?= base_url('ocupation/new') ?>" class="btn btn-primary">
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
            <?php foreach($ocupations as $ocupation): ?>
              <tr>
                <td><?= $ocupation->id ?></td>
                <td><?= $ocupation->name ?></td>
                <td>
                  <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('ocupation/edit/'.$ocupation->id) ?>" aria-label="Editar">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger removeItem" itemId="<?= $ocupation->id ?>" aria-label="Eliminar">
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
  var deleteURL     = "<?= base_url('ocupation/delete'); ?>"
  var csrfName      = '<?= $csrfName ?>';
  var csrfHash      = '<?= $csrfHash ?>';
</script>