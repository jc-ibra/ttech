<div class="min__h__100">
  <div class="container__organization">
    <div class="row">
      <div class="col-12">
        <div class="text-center py-2 container">
          <div class="row">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-semibold mb-0"><?= $organigrama->name ?></h3>
                <?php if($organigrama->description): ?>
                    <p class="text-muted mt-2"><?= $organigrama->description ?></p>
                <?php endif; ?>
                <div class="d-flex gap-2">
                    <?php if(session()->get('user')->rol === 'admin'): ?>
                  <a href="<?= base_url('custom-organigram/edit/'.$organigrama->id) ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-pencil"></i> Editar
                  </a>
                    <?php endif; ?>
                  <a href="<?= base_url('custom-organigram') ?>" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left"></i> Volver
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card b-s-none orgchart-offset">
    <div id="chart-container" class="min__h__100">
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-3 text-muted">Cargando organigrama...</p>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  const organigramaId = <?= $organigrama->id ?>;

  // Cargar datos del organigrama
  $.ajax({
    url: `<?= base_url('custom-organigram/data/') ?>${organigramaId}`,
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      if (data && data.id) {
        renderOrganigram(data);
      } else {
        $('#chart-container').html(`
          <div class="text-center py-5">
            <i class="ti ti-alert-circle fs-1 text-warning"></i>
            <p class="mt-3 text-muted">No hay empleados en este organigrama</p>
            <a href="<?= base_url('custom-organigram/edit/'.$organigrama->id) ?>" class="btn btn-primary mt-2">
              Agregar Empleados
            </a>
          </div>
        `);
      }
    },
    error: function() {
      $('#chart-container').html(`
        <div class="text-center py-5">
          <i class="ti ti-alert-circle fs-1 text-danger"></i>
          <p class="mt-3 text-muted">Error al cargar el organigrama</p>
        </div>
      `);
    }
  });

  function renderOrganigram(data) {
    // Verificar si existe la biblioteca orgchart (jQuery)
    if (typeof $.fn.orgchart === 'undefined') {
      console.error('jQuery OrgChart library not loaded');
      $('#chart-container').html(`
        <div class="text-center py-5">
          <p class="text-danger">Error: Biblioteca de organigrama no cargada</p>
        </div>
      `);
      return;
    }

    try {
      // Limpiar el contenedor primero
      $('#chart-container').empty();
      
      $('#chart-container').orgchart({
        'data': data,
        'nodeContent': 'title',
        'createNode': function($node, data) {
          if (data.img) {
            $node.prepend('<img class="node-img" src="' + data.img + '" alt="' + data.name + '">');
          }
          if (data.ghost) {
            $node.addClass('ghost__node');
            $node.addClass('ghost__node__niveles__' + data.niveles);
          } else {
            $node.addClass('normal__node');
          }
        }
      });

    } catch (error) {
      console.error('Error rendering orgchart:', error);
      $('#chart-container').html(`
        <div class="text-center py-5">
          <p class="text-danger">Error al renderizar el organigrama</p>
        </div>
      `);
    }
  }
});
</script>
