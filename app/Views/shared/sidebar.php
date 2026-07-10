<?php
  $rol = session('user')->rol ?? 'user';
  $uri = uri_string();

  // Marca el item activo según la URL actual.
  $act = function($needle) use ($uri) {
      return strpos($uri, $needle) !== false ? ' active' : '';
  };
?>
<!-- Sidebar Start -->
<div class="ntx-backdrop" id="ntxBackdrop"></div>
<aside class="left-sidebar ntx-aside" id="left__sidebar">
  <div class="ntx-side">

    <!-- Marca -->
    <div class="ntx-brand">
      <a href="<?= base_url($rol === 'operator' ? 'organization' : 'trantor-informa') ?>">
        <img src="<?= base_url('assets/images/logos/logo-2.png') ?>" alt="Trantor Technologies" />
      </a>
    </div>

    <nav class="ntx-nav">

      <?php if ($rol === 'admin' || $rol === 'user'): ?>
        <a class="ntx-link<?= $act('trantor-informa') ?>" href="<?= base_url('trantor-informa') ?>">
          <span class="ntx-ico"><i class="ti ti-flag"></i></span>
          <span class="ntx-label">Inicio</span>
        </a>
        <a class="ntx-link<?= $act('trantor-technologies') ?>" href="<?= base_url('trantor-technologies') ?>">
          <span class="ntx-ico"><i class="ti ti-world"></i></span>
          <span class="ntx-label">Trantor Tech.</span>
        </a>
      <?php endif; ?>

      <a class="ntx-link<?= $act('profile') ?>" href="<?= base_url('profile') ?>">
        <span class="ntx-ico"><i class="ti ti-user"></i></span>
        <span class="ntx-label">Mi espacio</span>
      </a>

      <?php if ($rol === 'admin' || $rol === 'user'): ?>
        <a class="ntx-link<?= $act('documentos') ?>" href="<?= base_url('documentos') ?>">
          <span class="ntx-ico"><i class="ti ti-files"></i></span>
          <span class="ntx-label">Documentos</span>
        </a>
        <a class="ntx-link<?= $act('directorio') ?>" href="<?= base_url('directorio') ?>">
          <span class="ntx-ico"><i class="ti ti-phone-call"></i></span>
          <span class="ntx-label">Directorio</span>
        </a>
      <?php endif; ?>

      <a class="ntx-link<?= $act('organization') ?>" href="<?= base_url('organization') ?>">
        <span class="ntx-ico"><i class="ti ti-hierarchy-2"></i></span>
        <span class="ntx-label">Organigramas</span>
      </a>

      <?php if ($rol === 'operator'): ?>
        <a class="ntx-link<?= $act('custom-organigram') ?>" href="<?= base_url('custom-organigram') ?>">
          <span class="ntx-ico"><i class="ti ti-topology-complex"></i></span>
          <span class="ntx-label">Personalizados</span>
        </a>
        <a class="ntx-link<?= $act('empleados') ?>" href="<?= base_url('empleados') ?>">
          <span class="ntx-ico"><i class="ti ti-users"></i></span>
          <span class="ntx-label">Empleados</span>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'admin' || $rol === 'user'): ?>
        <a class="ntx-link<?= $act('quejas-sugerencias') ?>" href="<?= base_url('quejas-sugerencias') ?>">
          <span class="ntx-ico"><i class="ti ti-mailbox"></i></span>
          <span class="ntx-label">Comunicación</span>
        </a>
      <?php endif; ?>

      <?php if ($rol === 'admin'): ?>
        <div class="ntx-section">Admin</div>

        <a class="ntx-link<?= $act('empleados') ?>" href="<?= base_url('empleados') ?>">
          <span class="ntx-ico"><i class="ti ti-users"></i></span>
          <span class="ntx-label">Empleados</span>
        </a>
        <a class="ntx-link<?= $act('usuarios') ?>" href="<?= base_url('usuarios') ?>">
          <span class="ntx-ico"><i class="ti ti-user"></i></span>
          <span class="ntx-label">Usuarios</span>
        </a>
        <a class="ntx-link<?= $act('custom-organigram') ?>" href="<?= base_url('custom-organigram') ?>">
          <span class="ntx-ico"><i class="ti ti-topology-complex"></i></span>
          <span class="ntx-label">Personalizados</span>
        </a>
        <?php $catActive = ($act('catalogos') || $act('ocupation') || $act('department') || $act('area')) ? ' active' : ''; ?>
        <a class="ntx-link<?= $catActive ?>" href="<?= base_url('catalogos') ?>">
          <span class="ntx-ico"><i class="ti ti-layout-grid"></i></span>
          <span class="ntx-label">Catálogos</span>
        </a>
        <a class="ntx-link<?= $act('documents') ?>" href="<?= base_url('documents') ?>">
          <span class="ntx-ico"><i class="ti ti-file-analytics"></i></span>
          <span class="ntx-label">Gestión docs</span>
        </a>
        <a class="ntx-link<?= $act('suggestions') ?>" href="<?= base_url('suggestions') ?>">
          <span class="ntx-ico"><i class="ti ti-mail-opened"></i></span>
          <span class="ntx-label">Buzón (admin)</span>
        </a>
      <?php endif; ?>

    </nav>

    <div class="ntx-side-footer">
      <a class="ntx-link ntx-logout" href="<?= base_url('/auth/logout') ?>">
        <span class="ntx-ico"><i class="ti ti-login"></i></span>
        <span class="ntx-label">Salir</span>
      </a>
    </div>

  </div>
</aside>
<div class="body-wrapper ntx-wrapper" id="body__wrapper">
