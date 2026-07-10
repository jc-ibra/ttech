<!--  Navbar Start -->
<header class="app-header ntx-topbar" id="app__header">
  <div class="ntx-topbar-inner">

    <!-- Izquierda: toggle + saludo -->
    <div class="ntx-topbar-left">
      <a class="ntx-hamburger" id="ntxHamburger" href="javascript:void(0)">
        <i class="ti ti-menu-2"></i>
      </a>
      <div class="ntx-greeting">
        <span class="ntx-greeting-hi">Hola,</span>
        <span class="ntx-greeting-name"><?= esc(session('user')->name) ?></span>
      </div>
    </div>

    <!-- Derecha: alertas + usuario -->
    <div class="ntx-topbar-right">

      <a class="ntx-bell" href="<?= base_url('/alerts') ?>" title="Alertas">
        <div id="bell__icon" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="22" width="22">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
          </svg>
        </div>
        <div id="bell__icon__on" style="display: none;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="#1773C8" viewBox="0 0 24 24" stroke-width="1.5" stroke="#1A1C1E" height="22" width="22">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
          </svg>
          <span id="bell__icon-indicator" class="ntx-bell-dot"></span>
        </div>
      </a>

      <div class="dropdown ntx-user">
        <a class="ntx-user-btn" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?= base_url(session('user')->photo) ?>" alt="" class="ntx-user-avatar"
               onerror="this.onerror=null;this.src='<?= base_url('assets/images/anonimo.jpg') ?>';">
          <span class="ntx-user-name"><?= esc(session('user')->name) ?> <?= esc(session('user')->lastname) ?></span>
          <i class="ti ti-chevron-down ntx-user-caret"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end ntx-user-menu" aria-labelledby="drop2">
          <div class="ntx-user-menu__head">
            <img class="ntx-user-menu__avatar" src="<?= base_url(session('user')->photo) ?>" alt=""
                 onerror="this.onerror=null;this.src='<?= base_url('assets/images/anonimo.jpg') ?>';">
            <div class="ntx-user-menu__id">
              <span class="ntx-user-menu__name"><?= esc(session('user')->name) ?> <?= esc(session('user')->lastname) ?></span>
              <span class="ntx-user-menu__email"><?= esc(session('user')->email) ?></span>
            </div>
          </div>
          <div class="ntx-user-menu__body">
            <a href="<?= base_url('/profile') ?>" class="ntx-user-menu__item">
              <i class="ti ti-user"></i> Mi espacio
            </a>
            <a href="<?= base_url('/alerts') ?>" class="ntx-user-menu__item">
              <i class="ti ti-bell"></i> Alertas
            </a>
          </div>
          <div class="ntx-user-menu__foot">
            <a href="<?= base_url('/auth/logout') ?>" class="ntx-user-menu__logout">
              <i class="ti ti-logout"></i> Cerrar sesión
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</header>
<script>
  (function () {
    var side = document.getElementById('left__sidebar');
    var back = document.getElementById('ntxBackdrop');
    var burger = document.getElementById('ntxHamburger');
    function toggle() {
      if (!side) return;
      var open = side.classList.toggle('ntx-mobile-open');
      if (back) back.classList.toggle('show', open);
    }
    function close() {
      if (side) side.classList.remove('ntx-mobile-open');
      if (back) back.classList.remove('show');
    }
    if (burger) burger.addEventListener('click', toggle);
    if (back) back.addEventListener('click', close);
  })();
</script>
<!--  Navbar End -->
