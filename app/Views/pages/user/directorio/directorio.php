<?php
usort($users, function($a, $b) {
    $nameA = strtolower($a->name . ' ' . $a->lastname);
    $nameB = strtolower($b->name . ' ' . $b->lastname);
    return strcmp($nameA, $nameB);
});

// Avatar por defecto cuando la foto no existe o no carga.
$defaultPhoto = base_url('assets/images/anonimo.jpg');
?>

<div class="container-fluid mw-1600 directorio__wrapper">
  <!-- Barra de búsqueda principal -->
  <div class="directory-search mt-4">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h3 class="directory-search__title mb-2 fw-bold">
          <i class="ti ti-users me-2"></i>Directorio
        </h3>
        <input 
          type="text" 
          class="form-control mt-3" 
          id="searchInput" 
          placeholder="Buscar por nombre, puesto, correo..."
        >
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 d-none d-lg-block">
        <div class="view-toggle d-inline-flex">
          <button class="active" id="gridViewBtn" title="Vista de tarjetas">
            <i class="ti ti-layout-grid"></i>
          </button>
          <button id="listViewBtn" title="Vista de lista">
            <i class="ti ti-list"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="stats-bar">
    <div class="stat-item">
      <i class="ti ti-users"></i>
      <div>
        <div class="stat-number" id="visibleCount">0</div>
        <div class="stat-label">Resultados</div>
      </div>
    </div>
    <div class="stat-item">
      <i class="ti ti-search"></i>
      <div class="stat-label" id="searchStatus">Mostrando todos</div>
    </div>
  </div>

  <!-- tarjetas -->
  <div id="gridView" class="row">
    <?php foreach($users as $user): ?>
      <?php if(!$user->ghost): ?>
        <div class="col-sm-6 col-md-4 col-xl-3 mb-3 employee-item fade-in"
             data-name="<?= strtolower($user->name . ' ' . $user->lastname) ?>"
             data-position="<?= strtolower($user->ocupation_name ?? '') ?>"
             data-email="<?= strtolower($user->email) ?>"
             data-employee-number="<?= $user->employee_number ?>">
          <div class="employee-card">
            <div class="employee-card-header">
              <img
                src="<?= $user->photo ? base_url($user->photo) : $defaultPhoto ?>"
                alt="<?= $user->name ?>"
                class="employee-avatar"
                loading="lazy"
                onerror="this.onerror=null;this.src='<?= $defaultPhoto ?>';"
              >
              <div class="employee-name"><?= $user->name ?> <?= $user->lastname ?></div>
              <div class="employee-position"><?= $user->ocupation_name ?></div>
              <div class="employee-badge">No. <?= $user->employee_number ?></div>
            </div>
            <div class="employee-card-body">
              <?php if($user->hide_emails != 1): ?>
              <div class="contact-item">
                <i class="ti ti-mail"></i>
                <div class="contact-item-text">
                  <small class="d-block text-muted">Email</small>
                  <a href="mailto:<?= $user->email ?>" class="text-decoration-none"><?= $user->email ?></a>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if($user->telephone): ?>
              <div class="contact-item">
                <i class="ti ti-phone"></i>
                <div class="contact-item-text">
                  <small class="d-block text-muted">Teléfono</small>
                  <?php $formattedTelephone = substr($user->telephone, 0, 2) . '-' . substr($user->telephone, 2, 4) . '-' . substr($user->telephone, 6); ?>
                  <a href="tel:<?= $user->telephone ?>" class="text-decoration-none"><?= $formattedTelephone ?></a>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if($user->ext): ?>
              <div class="contact-item">
                <i class="ti ti-phone-call"></i>
                <div class="contact-item-text">
                  <small class="d-block text-muted">Extensión</small>
                  <?= $user->ext ?>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if(session('user')->rol == 'admin' && $user->cellphone): ?>
              <div class="contact-item">
                <i class="ti ti-device-mobile"></i>
                <div class="contact-item-text">
                  <small class="d-block text-muted">Celular</small>
                  <?php $formattedCellphone = substr($user->cellphone, 0, 2) . '-' . substr($user->cellphone, 2, 4) . '-' . substr($user->cellphone, 6); ?>
                  <a href="tel:<?= $user->cellphone ?>" class="text-decoration-none"><?= $formattedCellphone ?></a>
                </div>
              </div>
              <?php endif; ?>

              <?php
                $hasContact = ($user->hide_emails != 1) || $user->telephone || $user->ext
                    || (session('user')->rol == 'admin' && $user->cellphone);
              ?>
              <?php if(!$hasContact): ?>
              <div class="contact-empty">Sin información de contacto</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- lista -->
  <div id="listView" style="display: none;">
    <?php foreach($users as $user): ?>
      <?php if(!$user->ghost): ?>
        <div class="list-view-item employee-item fade-in"
             data-name="<?= strtolower($user->name . ' ' . $user->lastname) ?>"
             data-position="<?= strtolower($user->ocupation_name ?? '') ?>"
             data-email="<?= strtolower($user->email) ?>"
             data-employee-number="<?= $user->employee_number ?>">
          <img
            src="<?= $user->photo ? base_url($user->photo) : $defaultPhoto ?>"
            alt="<?= $user->name ?>"
            class="list-avatar"
            loading="lazy"
            onerror="this.onerror=null;this.src='<?= $defaultPhoto ?>';"
          >
          <div class="list-content">
            <div class="list-info">
              <h6><?= $user->name ?> <?= $user->lastname ?></h6>
              <span><?= $user->ocupation_name ?></span>
              <div class="employee-badge mt-2">No. <?= $user->employee_number ?></div>
            </div>
            <div class="list-contact">
              <?php if($user->hide_emails != 1): ?>
              <div class="list-contact-item">
                <i class="ti ti-mail"></i>
                <a href="mailto:<?= $user->email ?>" class="text-decoration-none"><?= $user->email ?></a>
              </div>
              <?php endif; ?>
              <?php if($user->telephone): ?>
              <div class="list-contact-item">
                <i class="ti ti-phone"></i>
                <?php $formattedTelephone = substr($user->telephone, 0, 2) . '-' . substr($user->telephone, 2, 4) . '-' . substr($user->telephone, 6); ?>
                <a href="tel:<?= $user->telephone ?>" class="text-decoration-none"><?= $formattedTelephone ?></a>
              </div>
              <?php endif; ?>
            </div>
            <div class="list-contact">
              <?php if($user->ext): ?>
              <div class="list-contact-item">
                <i class="ti ti-phone-call"></i>
                Ext: <?= $user->ext ?>
              </div>
              <?php endif; ?>
              <?php if(session('user')->rol == 'admin' && $user->cellphone): ?>
              <div class="list-contact-item">
                <i class="ti ti-device-mobile"></i>
                <?php $formattedCellphone = substr($user->cellphone, 0, 2) . '-' . substr($user->cellphone, 2, 4) . '-' . substr($user->cellphone, 6); ?>
                <a href="tel:<?= $user->cellphone ?>" class="text-decoration-none"><?= $formattedCellphone ?></a>
              </div>
              <?php endif; ?>
            </div>
            <div>
              <?php if($user->hide_emails != 1 && session('user')->rol == 'admin' && $user->email_secondary): ?>
              <div class="list-contact-item">
                <i class="ti ti-mail"></i>
                <a href="mailto:<?= $user->email_secondary ?>" class="text-decoration-none text-truncate"><?= $user->email_secondary ?></a>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- vacío -->
  <div id="emptyState" class="empty-state" style="display: none;">
    <i class="ti ti-users-off"></i>
    <h5>No se encontraron resultados</h5>
    <p>Intenta ajustar tu búsqueda</p>
  </div>
</div>

<script>
$(document).ready(function() {
  let currentView = 'grid';
  
  // Actualizar estadísticas al cargar
  updateStats();
  
  function updateStats() {
    const visible = $('.employee-item:visible').length;
    $('#visibleCount').text(visible);
    
    if ($('#searchInput').val()) {
      $('#searchStatus').text(`${visible} resultados encontrados`);
    } else {
      $('#searchStatus').text('Mostrando todos');
    }
  }
  
  // Cambiar vista
  $('#gridViewBtn').click(function() {
    currentView = 'grid';
    $('#gridView').show();
    $('#listView').hide();
    $(this).addClass('active');
    $('#listViewBtn').removeClass('active');
  });
  
  $('#listViewBtn').click(function() {
    currentView = 'list';
    $('#gridView').hide();
    $('#listView').show();
    $(this).addClass('active');
    $('#gridViewBtn').removeClass('active');
  });
  
  // Búsqueda en tiempo real
  $('#searchInput').on('keyup', function() {
    const searchTerm = $(this).val().toLowerCase();
    let matchCount = 0;
    
    // Si no hay término de búsqueda, mostrar todos
    if (searchTerm === '') {
      $('.employee-item').show().addClass('fade-in');
      $('#emptyState').hide();
      updateStats();
      return;
    }
    
    // Filtrar elementos
    $('.employee-item').each(function() {
      const name = $(this).data('name');
      const position = $(this).data('position');
      const email = $(this).data('email');
      const empNumber = $(this).data('employee-number').toString();
      
      if (name.includes(searchTerm) || position.includes(searchTerm) || 
          email.includes(searchTerm) || empNumber.includes(searchTerm)) {
        $(this).show().addClass('fade-in');
        matchCount++;
      } else {
        $(this).hide();
      }
    });
    
    checkEmpty();
    updateStats();
  });
  
  function checkEmpty() {
    const visibleItems = $('.employee-item:visible').length;
    if (visibleItems === 0) {
      $('#emptyState').show();
    } else {
      $('#emptyState').hide();
    }
  }
});
</script>