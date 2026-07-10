<div class="container-fluid mw-1600">
  <div class="card documents__wrapper doc-card">
    <div class="card-body">

      <div class="doc-head">
        <div>
          <h5 class="doc-head__title"><i class="ti ti-folders"></i> Administrar Documentos</h5>
          <p class="doc-head__sub">Organiza carpetas y administra los archivos de la intranet.</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Árbol de carpetas -->
        <div class="col-lg-4 col-md-5">
          <div class="doc-tree">
            <div class="doc-tree__label"><i class="ti ti-sitemap"></i> Carpetas</div>
            <div id="folderTree"></div>
          </div>
        </div>

        <!-- Contenido de la carpeta -->
        <div class="col">
          <div class="doc-panel">
            <div class="doc-panel__head">
              <h5 class="doc-panel__title" id="title__documents"></h5>
              <span class="doc-panel__count" id="doc__count" style="display:none;"></span>
              <button type="button" class="btn btn-outline-primary btn-sm doc-panel__upload" data-bs-toggle="modal" data-bs-target="#createDocument">
                <i class="ti ti-upload me-1"></i> Cargar
              </button>
            </div>
            <?php include('document-create-modal.php'); ?>
            <div id="documents__container">
              <ul id="document__list__container">
                <li class="doc-placeholder">
                  <div class="doc-placeholder__icon"><i class="ti ti-folder-open"></i></div>
                  <h6>Selecciona una carpeta</h6>
                  <p>Elige una carpeta del árbol para administrar sus documentos.</p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var csrfName      = '<?= $csrfName ?>';
  var csrfHash      = '<?= $csrfHash ?>';
</script>
