<!-- Sidebar Start -->
<aside class="left-sidebar left-sidebar-collapse" id="left__sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="<?= base_url('trantor-technologies') ?>" class="text-nowrap logo-img py-4">
        <img src="<?= base_url('assets/images/logos/logo-1.png') ?>" height="45" alt="" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <nav class="sidebar-nav scroll-sidebar mt-2" data-simplebar="">
      <div class="accordion" id="accordionSidebar">
        <!-- Intranet -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingIntranet">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIntranet" aria-expanded="false" aria-controls="collapseIntranet">
              Intranet
            </button>
          </h2>
          <div id="collapseIntranet" class="accordion-collapse collapse" aria-labelledby="headingIntranet" data-bs-parent="#accordionSidebar">
            <div class="accordion-body px-0 py-1">
              <ul class="list-unstyled mb-0">
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('trantor-technologies') ?>">
                    <i class="ti ti-world"></i> Trantor Technologies
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('trantor-informa') ?>">
                    <i class="ti ti-flag"></i> Trantor Informa
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('documentos') ?>">
                    <i class="ti ti-files"></i> Políticas internas
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('directorio') ?>">
                    <i class="ti ti-phone-call"></i> Directorio
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('organization') ?>">
                    <i class="ti ti-directions"></i> Organigramas
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('quejas-sugerencias') ?>">
                    <i class="ti ti-mailbox"></i> Buzón de quejas y sugerencias
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?=base_url('/auth/logout')?>" aria-expanded="false">
                    <span>
                        <i class="ti ti-login"></i>
                    </span>
                    <span class="hide-menu">Salir</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- HELP DESK -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingHelpDesk">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHelpDesk" aria-expanded="false" aria-controls="collapseHelpDesk">
              Help Desk
            </button>
          </h2>
          <div id="collapseHelpDesk" class="accordion-collapse collapse" aria-labelledby="headingHelpDesk" data-bs-parent="#accordionSidebar">
            <div class="accordion-body px-0 py-1">
              <ul class="list-unstyled mb-0">
                <li class="sidebar-item">
                  <a class="sidebar-link" href="https://nstrantor.com.mx/glpi/" aria-expanded="false" target="_blank">
                    <i class="ti ti-ticket"></i> GLPI
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- ADMINISTRADOR -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingAdmin">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
              Administrador
            </button>
          </h2>
          <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin" data-bs-parent="#accordionSidebar">
            <div class="accordion-body px-0 py-1">
              <ul class="list-unstyled mb-0">
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('documents') ?>">
                    <i class="ti ti-file-analytics"></i> Políticas internas
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('ocupation') ?>">
                    <i class="ti ti-topology-complex"></i> Puestos
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('department') ?>">
                    <i class="ti ti-directions"></i> Departamentos
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('area') ?>">
                    <i class="ti ti-directions"></i> Areas
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('custom-organigram') ?>">
                    <i class="ti ti-hierarchy-2"></i> Organigramas Personalizados
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('suggestions') ?>">
                    <i class="ti ti-mail-opened"></i> Buzón de quejas y sugerencias
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url('user') ?>">
                    <i class="ti ti-users"></i> Empleados
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- Collapse Sidebar -->
      <!-- <div class="sidebar__collapse" id="sidebar__collapse__container">
        <div id="" class="accordion-collapse">
          <div class="accordion-body py-1">
            <ul class="list-unstyled mb-0">
              <li class="sidebar-item">
                <a class="sidebar-link" href="#" id="sidebar__collapse__btn">
                  <i class="ti ti-layout-sidebar-left-collapse"></i> Ocultar
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div> -->
      <!-- Expand Sidebar -->
      <!-- <div class="sidebar__expand" id="sidebar__expand__container">
        <div id="" class="accordion-collapse">
          <div class="accordion-body py-1">
            <ul class="list-unstyled mb-0">
              <li class="sidebar-item">
                <a class="sidebar-link sidebar-link-expand" href="#" id="sidebar__expand__btn">
                  <i class="ti ti-layout-sidebar-left-expand"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div> -->
    </nav>
  </div>
</aside>
<div class="body-wrapper body-wrapper__min body-wrapper-collapse" id="body__wrapper">