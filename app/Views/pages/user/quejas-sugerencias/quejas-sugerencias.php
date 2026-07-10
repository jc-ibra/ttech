<div class="container-fluid">
  <div class="suggestion-card">

    <!-- Panel informativo -->
    <div class="suggestion-aside">
      <span class="sg-shape sg-shape--1"></span>
      <span class="sg-shape sg-shape--2"></span>
      <div class="suggestion-aside__inner">
        <div class="suggestion-aside__badge"><i class="ti ti-mailbox"></i></div>
        <h4 class="suggestion-aside__title">Queremos escucharte</h4>
        <p class="suggestion-aside__text">
          Tu opinión nos ayuda a mejorar. Comparte una queja o sugerencia y la revisamos con atención.
        </p>
        <ul class="suggestion-aside__list">
          <li><i class="ti ti-clock-hour-4"></i> Respondemos lo antes posible</li>
          <li><i class="ti ti-lock"></i> Puedes enviarlo de forma anónima</li>
          <li><i class="ti ti-heart-handshake"></i> Cada mensaje es tomado en cuenta</li>
        </ul>
      </div>
    </div>

    <!-- Formulario -->
    <div class="suggestion-form">
      <?php if (session('success') !== null) : ?>
        <div class="suggestion-success">
          <div class="suggestion-success__icon"><i class="ti ti-circle-check"></i></div>
          <h5>¡Mensaje enviado!</h5>
          <p><?= session('success'); ?></p>
          <a href="<?= base_url('quejas-sugerencias') ?>" class="btn btn-outline-secondary">Enviar otra sugerencia</a>
        </div>
      <?php else: ?>

        <div class="suggestion-form__head">
          <h5>Quejas y sugerencias</h5>
          <p>Completa el formulario y nos pondremos en contacto.</p>
        </div>

        <form action="<?= base_url('suggestion/create') ?>" method="POST">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="author" value="<?= session('user')->id ?>">

          <div class="sg-section-label">Datos de contacto</div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="name">Nombre(s)</label>
              <input type="text" id="name" name="name"
                     value="<?= esc(session('user')->name) ?> <?= esc(session('user')->lastname) ?>"
                     class="form-control bg-light" required readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">E-mail</label>
              <input type="email" id="email" name="email"
                     value="<?= esc(session('user')->email) ?>"
                     class="form-control bg-light" required readonly>
            </div>
          </div>

          <label class="sg-anon" for="publishCheck">
            <input type="checkbox" class="form-check-input" id="publishCheck" name="publish">
            <span class="sg-anon__body">
              <span class="sg-anon__title"><i class="ti ti-user-off"></i> Enviar de forma anónima</span>
              <span class="sg-anon__hint">Tu nombre y correo no se compartirán con el mensaje.</span>
            </span>
          </label>

          <div class="sg-section-label mt-4">Tu mensaje</div>
          <div class="mb-3">
            <label class="form-label" for="title">Asunto</label>
            <input type="text" id="title" name="title" class="form-control"
                   placeholder="Describe brevemente el asunto" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="message">Mensaje</label>
            <textarea id="message" name="message" class="form-control"
                      placeholder="Escribe tu mensaje aquí..." required rows="6"
                      style="resize:vertical;"></textarea>
          </div>

          <?php if (session('message') !== null) : ?>
            <div class="alert alert-danger"><?= session('message'); ?></div>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary px-4">
            <i class="ti ti-send me-1"></i> Enviar mensaje
          </button>
        </form>

      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  .suggestion-card {
    display: flex;
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    max-width: 1050px;
    margin: 0 auto;
  }

  /* Panel lateral */
  .suggestion-aside {
    position: relative;
    flex: 0 0 340px;
    overflow: hidden;
    background: linear-gradient(155deg, var(--color-blue-600) 0%, var(--color-blue-800) 100%);
    color: #fff;
    padding: 2.5rem 2rem;
    display: flex;
    align-items: center;
  }
  .suggestion-aside__inner { position: relative; z-index: 2; }
  .suggestion-aside__badge {
    width: 60px; height: 60px;
    border-radius: 16px;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem;
    margin-bottom: 1.4rem;
  }
  .suggestion-aside__title { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.6rem; color: #fff; }
  .suggestion-aside__text { color: rgba(255,255,255,0.85); font-size: 0.95rem; line-height: 1.55; margin: 0 0 1.75rem; }
  .suggestion-aside__list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.85rem; }
  .suggestion-aside__list li { display: flex; align-items: center; gap: 0.65rem; font-size: 0.9rem; color: rgba(255,255,255,0.92); }
  .suggestion-aside__list i { font-size: 1.15rem; opacity: 0.9; }
  .sg-shape { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.07); z-index: 1; }
  .sg-shape--1 { width: 240px; height: 240px; top: -90px; right: -80px; }
  .sg-shape--2 { width: 160px; height: 160px; bottom: -60px; left: -50px; background: rgba(255,255,255,0.05); }

  /* Formulario */
  .suggestion-form { flex: 1 1 auto; padding: 2.25rem 2.25rem; min-width: 0; }
  .suggestion-form__head h5 { font-size: 1.3rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem; }
  .suggestion-form__head p { color: var(--text-muted); font-size: 0.9rem; margin: 0 0 1.5rem; }

  .sg-section-label {
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
    color: var(--text-disabled); margin-bottom: 0.75rem;
  }

  .suggestion-form .form-control {
    border-radius: 10px;
    border: 1px solid var(--color-neutral-200);
    padding: 0.6rem 0.85rem;
  }
  .suggestion-form .form-control:focus {
    border-color: var(--color-blue-400);
    box-shadow: 0 0 0 3px var(--color-blue-50);
  }

  /* Casilla anónimo */
  .sg-anon {
    display: flex; align-items: flex-start; gap: 0.7rem;
    margin: 1.1rem 0 0;
    padding: 0.85rem 1rem;
    border: 1px solid var(--color-neutral-200);
    border-radius: 12px;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
  }
  .sg-anon:hover { border-color: var(--color-blue-200); background: var(--color-blue-50); }
  .sg-anon .form-check-input { margin-top: 0.15rem; }
  .sg-anon__body { display: flex; flex-direction: column; gap: 0.1rem; }
  .sg-anon__title { font-size: 0.92rem; font-weight: 600; color: var(--text-primary); }
  .sg-anon__hint { font-size: 0.8rem; color: var(--text-muted); }

  /* Éxito */
  .suggestion-success { text-align: center; padding: 2.5rem 1rem; max-width: 420px; margin: 0 auto; }
  .suggestion-success__icon { font-size: 3.5rem; color: var(--color-success-strong, #12805c); margin-bottom: 0.75rem; }
  .suggestion-success h5 { font-weight: 800; color: var(--text-primary); }
  .suggestion-success p { color: var(--text-muted); margin-bottom: 1.5rem; }

  @media (max-width: 820px) {
    .suggestion-card { flex-direction: column; }
    .suggestion-aside { flex-basis: auto; padding: 1.75rem; }
    .suggestion-form { padding: 1.5rem; }
  }
</style>

<script>
  (function () {
    var publishCheck = document.getElementById('publishCheck');
    if (!publishCheck) return;
    var name  = document.getElementById('name');
    var email = document.getElementById('email');
    publishCheck.addEventListener('change', function () {
      if (publishCheck.checked) {
        name.value  = 'Anonimo';
        email.value = 'anonimo@anonimo.com';
      } else {
        name.value  = '<?= esc(session('user')->name) ?> <?= esc(session('user')->lastname) ?>';
        email.value = '<?= esc(session('user')->email) ?>';
      }
    });
  })();
</script>
