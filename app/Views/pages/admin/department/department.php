<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Departamentos</h5>
          <p class="page-header__subtitle">Gestión de departamentos de la empresa</p>
        </div>
        <a href="<?= base_url('department/new') ?>" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i>Nuevo
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="dt_table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($departments as $department): ?>
              <tr>
                <td><?= $department->id ?></td>
                <td><?= $department->name ?></td>
                <td>
                  <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('department/edit/'.$department->id) ?>" aria-label="Editar">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger removeItem" itemId="<?= $department->id ?>" aria-label="Eliminar">
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
  var deleteURL     = "<?= base_url('department/delete'); ?>"
  var csrfName      = '<?= $csrfName ?>';
  var csrfHash      = '<?= $csrfHash ?>';
</script>