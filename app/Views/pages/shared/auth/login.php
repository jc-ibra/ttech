    <div class="auth">

      <!-- Panel de marca -->
      <div class="auth__brand">
        <span class="auth__shape auth__shape--1"></span>
        <span class="auth__shape auth__shape--2"></span>
        <span class="auth__shape auth__shape--3"></span>
        <div class="auth__brand-inner">
          <div class="auth__badge">
            <img src="<?= base_url('assets/images/logos/logo-2.png') ?>" alt="Trantor Technologies">
          </div>
          <h1 class="auth__brand-title">Intranet Corporativa</h1>
          <p class="auth__brand-text">
            Tu espacio para mantenerte al día con Trantor Technologies: comunicados,
            documentos, directorio y más, en un solo lugar.
          </p>
        </div>
      </div>

      <!-- Panel de formulario -->
      <div class="auth__form">
        <div class="auth__form-inner">
          <img class="auth__logo" src="<?= base_url('assets/images/logos/logo-1.png') ?>" alt="Trantor Technologies">

          <h2 class="auth__title">Bienvenido de nuevo</h2>
          <p class="auth__subtitle">Ingresa tus credenciales para acceder a la intranet.</p>

          <form method="post" action="<?= base_url('auth/login') ?>" class="auth__fields">
            <?php echo csrf_field(); ?>

            <div class="auth__field">
              <label for="email">Correo electrónico</label>
              <div class="auth__input">
                <i class="ti ti-mail"></i>
                <input type="email" id="email" name="email" placeholder="tucorreo@trantortechnologies.mx" required autofocus>
              </div>
            </div>

            <div class="auth__field">
              <label for="password">Contraseña</label>
              <div class="auth__input">
                <i class="ti ti-lock"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="auth__toggle" id="togglePassword" tabindex="-1" aria-label="Mostrar u ocultar contraseña">
                  <i class="ti ti-eye"></i>
                </button>
              </div>
            </div>

            <?php if (session('message') !== null) : ?>
              <div class="auth__error">
                <i class="ti ti-alert-circle"></i>
                <span><?= session('message'); ?></span>
              </div>
            <?php endif; ?>

            <button type="submit" class="auth__submit">Entrar</button>
          </form>

          <p class="auth__foot">© <?= date('Y') ?> Trantor Technologies</p>
        </div>
      </div>

    </div>
  </div>

  <style>
    .auth {
      min-height: 100vh;
      display: flex;
      background: var(--bg-surface);
      font-family: inherit;
    }

    /* ----- Panel de marca (izquierda) ----- */
    .auth__brand {
      position: relative;
      flex: 1 1 50%;
      overflow: hidden;
      background: linear-gradient(150deg, var(--color-blue-600) 0%, var(--color-blue-800) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem;
      color: #fff;
    }
    .auth__brand-inner {
      position: relative;
      z-index: 2;
      max-width: 420px;
    }
    .auth__badge {
      width: 84px; height: 84px;
      border-radius: 22px;
      background: rgba(255,255,255,0.14);
      backdrop-filter: blur(4px);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 1.75rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .auth__badge img { width: 52px; height: 52px; object-fit: contain; }
    .auth__brand-title {
      font-size: 2.4rem;
      font-weight: 800;
      line-height: 1.1;
      margin: 0 0 1rem;
      letter-spacing: -0.02em;
      color: #fff;
    }
    .auth__brand-text {
      font-size: 1.02rem;
      line-height: 1.6;
      color: rgba(255,255,255,0.85);
      margin: 0;
    }
    .auth__shape {
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,0.07);
      z-index: 1;
    }
    .auth__shape--1 { width: 420px; height: 420px; top: -140px; right: -120px; }
    .auth__shape--2 { width: 260px; height: 260px; bottom: -90px; left: -80px; background: rgba(255,255,255,0.05); }
    .auth__shape--3 { width: 130px; height: 130px; bottom: 18%; right: 12%; background: rgba(255,255,255,0.06); }

    /* ----- Panel de formulario (derecha) ----- */
    .auth__form {
      flex: 1 1 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 1.5rem;
      background: var(--bg-surface);
    }
    .auth__form-inner { width: 100%; max-width: 380px; }
    .auth__logo { height: 46px; width: auto; margin-bottom: 2.25rem; }
    .auth__title { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.4rem; }
    .auth__subtitle { font-size: 0.95rem; color: var(--text-muted); margin: 0 0 2rem; }

    .auth__fields { display: flex; flex-direction: column; gap: 1.1rem; }
    .auth__field { display: flex; flex-direction: column; gap: 0.4rem; }
    .auth__field label { font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); }
    .auth__input {
      position: relative;
      display: flex;
      align-items: center;
    }
    .auth__input > i {
      position: absolute; left: 14px;
      color: var(--text-disabled);
      font-size: 1.1rem;
      pointer-events: none;
    }
    .auth__input input {
      width: 100%;
      height: 50px;
      border: 1px solid var(--color-neutral-200);
      border-radius: 12px;
      background: var(--bg-page);
      padding: 0 2.75rem;
      font-size: 0.95rem;
      color: var(--text-primary);
      transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }
    .auth__input input::placeholder { color: var(--text-disabled); }
    .auth__input input:focus {
      outline: none;
      border-color: var(--color-blue-400);
      background: var(--bg-surface);
      box-shadow: 0 0 0 3px var(--color-blue-50);
    }
    .auth__toggle {
      position: absolute; right: 8px;
      border: none; background: transparent;
      width: 36px; height: 36px;
      border-radius: 8px;
      color: var(--text-muted);
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }
    .auth__toggle:hover { background: var(--color-neutral-100); color: var(--color-blue-600); }

    .auth__error {
      display: flex; align-items: center; gap: 0.5rem;
      background: var(--color-critical-surface, #fdecea);
      color: var(--color-critical-strong, #b42318);
      border-radius: 10px;
      padding: 0.65rem 0.85rem;
      font-size: 0.85rem;
    }
    .auth__error i { font-size: 1.05rem; flex-shrink: 0; }

    .auth__submit {
      margin-top: 0.4rem;
      height: 50px;
      border: none;
      border-radius: 12px;
      background: var(--color-blue-500);
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
      box-shadow: 0 8px 20px rgba(23,115,200,0.28);
    }
    .auth__submit:hover { background: var(--color-blue-600); }
    .auth__submit:active { transform: translateY(1px); }

    .auth__foot { text-align: center; color: var(--text-disabled); font-size: 0.8rem; margin: 2rem 0 0; }

    /* ----- Responsive ----- */
    @media (max-width: 900px) {
      .auth__brand { display: none; }
      .auth__form { flex: 1 1 100%; }
    }
  </style>

  <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>

<script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    var passwordInput = document.getElementById('password');
    var icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.remove('ti-eye');
      icon.classList.add('ti-eye-off');
    } else {
      passwordInput.type = 'password';
      icon.classList.remove('ti-eye-off');
      icon.classList.add('ti-eye');
    }
  });
</script>
