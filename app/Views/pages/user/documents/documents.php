<div class="container-fluid mw-1600">
  <div class="card documents__wrapper doc-card">
    <div class="card-body">

      <div class="doc-head">
        <div>
          <h5 class="doc-head__title"><i class="ti ti-folders"></i> Documentos</h5>
          <p class="doc-head__sub">Explora las carpetas y consulta los archivos disponibles.</p>
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
            </div>
            <div id="documents__container">
              <ul id="document__list__container">
                <li class="doc-placeholder">
                  <div class="doc-placeholder__icon"><i class="ti ti-folder-open"></i></div>
                  <h6>Selecciona una carpeta</h6>
                  <p>Elige una carpeta del árbol para ver los documentos que contiene.</p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
