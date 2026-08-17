<?php
  $rolLabel = [
    'admin'    => 'Administrador',
    'operator' => 'Operador',
    'user'     => 'Usuario',
  ][$user->rol] ?? 'Usuario';

  $fmt = function ($dt) {
    return ($dt && $dt !== '0000-00-00 00:00:00') ? date('d/m/Y H:i', strtotime($dt)) : '—';
  };

  // Sistemas externos: administrables desde /configuracion.
  // Devuelve un arreglo vacío si el bloque está apagado.
  $sistemas = external_systems();
?>
<div class="container-fluid mw-1600">

  <!-- Tarjeta informativa del perfil (solo lectura) -->
  <div class="profile-card">
    <div class="profile-card__head">
      <img class="profile-card__avatar"
           src="<?= base_url($user->photo) ?>"
           alt="<?= esc($user->name) ?>"
           onerror="this.onerror=null;this.src='<?= base_url('assets/images/anonimo.jpg') ?>';">
      <div class="profile-card__id">
        <h5 class="profile-card__name"><?= esc($user->name) ?> <?= esc($user->lastname) ?></h5>
        <span class="profile-card__role"><i class="ti ti-shield-check"></i> <?= $rolLabel ?></span>
        <?php if (!empty($user->employee_number)): ?>
          <span class="profile-emp-badge" title="Número de empleado">
            <i class="ti ti-id-badge-2"></i> N.º de empleado: <strong><?= esc($user->employee_number) ?></strong>
          </span>
        <?php endif; ?>
      </div>
    </div>

    <div class="profile-card__grid">
      <div class="profile-info">
        <span class="profile-info__label">Correo electrónico</span>
        <span class="profile-info__value"><?= esc($user->email) ?></span>
      </div>
      <div class="profile-info">
        <span class="profile-info__label">Número de empleado</span>
        <span class="profile-info__value"><?= !empty($user->employee_number) ? esc($user->employee_number) : 'No asignado' ?></span>
      </div>
      <div class="profile-info">
        <span class="profile-info__label">Miembro desde</span>
        <span class="profile-info__value"><?= $fmt($user->created_at) ?></span>
      </div>
    </div>

    <p class="profile-card__note">
      <i class="ti ti-info-circle"></i>
      Tus datos se administran de forma centralizada. Si necesitas actualizar tu información, contacta a tu administrador.
    </p>
  </div>

  <!-- Seguridad -->
  <div class="profile-section-title">
    <h6>Seguridad</h6>
    <span>Administra el acceso a tu cuenta</span>
  </div>

  <div class="security-card">
    <div class="security-card__info">
      <span class="security-card__icon"><i class="ti ti-lock"></i></span>
      <div>
        <span class="security-card__label">Contraseña</span>
        <span class="security-card__desc">Cámbiala periódicamente para mantener tu cuenta segura.</span>
      </div>
    </div>
    <button type="button" class="btn btn-outline-primary security-card__btn" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
      <i class="ti ti-lock"></i> Cambiar contraseña
    </button>
  </div>

  <!-- Sistemas externos -->
  <?php if (!empty($sistemas)): ?>
    <div class="profile-section-title">
      <h6>Sistemas externos</h6>
      <span>Accesos rápidos a las plataformas de la empresa</span>
    </div>

    <div class="row g-3">
      <?php foreach ($sistemas as $s): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <a href="<?= esc($s->url, 'attr') ?>" target="_blank" rel="noopener" class="system-card">
            <i class="ti ti-external-link system-card__ext"></i>
            <div class="system-card__head">
              <span class="system-card__icon"><i class="<?= esc($s->icon, 'attr') ?>"></i></span>
              <span class="system-card__title"><?= esc($s->title) ?></span>
            </div>
            <span class="system-card__desc"><?= esc($s->description) ?></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Modal: cambiar contraseña -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pw-modal">

      <!-- Estado: formulario -->
      <div id="pwFormState">
        <div class="modal-header pw-modal__header">
          <div class="pw-modal__title-wrap">
            <span class="pw-modal__title-icon"><i class="ti ti-lock"></i></span>
            <div>
              <h5 class="modal-title" id="changePasswordModalLabel">Cambiar contraseña</h5>
              <span class="pw-modal__subtitle">Ingresa tu contraseña actual y la nueva</span>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div id="pwAlert" class="pw-alert" role="alert" style="display:none;"></div>

          <form id="changePasswordForm" autocomplete="off" novalidate>
            <div class="pw-field">
              <label class="pw-label" for="oldPassword">Contraseña actual <span class="pw-req">*</span></label>
              <div class="pw-input-wrap">
                <input type="password" class="form-control" id="oldPassword" name="oldPassword" autocomplete="current-password" required>
                <button type="button" class="pw-toggle" data-target="oldPassword" tabindex="-1" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button>
              </div>
            </div>

            <div class="pw-field">
              <label class="pw-label" for="password">Nueva contraseña <span class="pw-req">*</span></label>
              <div class="pw-input-wrap">
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
                <button type="button" class="pw-toggle" data-target="password" tabindex="-1" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button>
              </div>
              <span class="pw-hint">Mínimo 6 caracteres.</span>
            </div>

            <div class="pw-field">
              <label class="pw-label" for="password_confirmation">Confirmar nueva contraseña <span class="pw-req">*</span></label>
              <div class="pw-input-wrap">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                <button type="button" class="pw-toggle" data-target="password_confirmation" tabindex="-1" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button>
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer pw-modal__footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="pwSubmitBtn">
            <span class="pw-btn-label"><i class="ti ti-device-floppy"></i> Guardar nueva contraseña</span>
            <span class="pw-btn-spinner" style="display:none;"><span class="spinner-border spinner-border-sm"></span> Guardando…</span>
          </button>
        </div>
      </div>

      <!-- Estado: éxito -->
      <div id="pwSuccessState" style="display:none;">
        <div class="modal-body pw-success">
          <span class="pw-success__icon"><i class="ti ti-circle-check"></i></span>
          <h5 class="pw-success__title">Contraseña modificada</h5>
          <p class="pw-success__text">Tu contraseña se actualizó exitosamente. Por seguridad, cerraremos tu sesión para que inicies de nuevo.</p>
          <a href="<?= base_url('auth/logout') ?>" class="btn btn-primary">
            <i class="ti ti-login"></i> Iniciar sesión de nuevo
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  (function () {
    var csrfName = '<?= $csrfName ?>';
    var csrfHash = '<?= $csrfHash ?>';

    var $modal   = $('#changePasswordModal');
    var $alert   = $('#pwAlert');
    var $submit  = $('#pwSubmitBtn');
    var $form    = $('#changePasswordForm');

    function showAlert(msg) {
      $alert.text(msg).show();
    }
    function clearAlert() {
      $alert.hide().text('');
    }
    function setLoading(loading) {
      $submit.prop('disabled', loading);
      $submit.find('.pw-btn-label').toggle(!loading);
      $submit.find('.pw-btn-spinner').toggle(loading);
    }
    function resetForm() {
      $form[0].reset();
      clearAlert();
      setLoading(false);
      $('#pwFormState').show();
      $('#pwSuccessState').hide();
    }

    // Reinicia el modal cada vez que se abre
    $modal.on('show.bs.modal', resetForm);

    // Mostrar / ocultar contraseña
    $('.pw-toggle').on('click', function () {
      var id    = $(this).data('target');
      var $inp  = $('#' + id);
      var $icon = $(this).find('i');
      if ($inp.attr('type') === 'password') {
        $inp.attr('type', 'text');
        $icon.removeClass('ti-eye').addClass('ti-eye-off');
      } else {
        $inp.attr('type', 'password');
        $icon.removeClass('ti-eye-off').addClass('ti-eye');
      }
    });

    // Enter envía el formulario
    $form.on('submit', function (e) { e.preventDefault(); $submit.trigger('click'); });

    $submit.on('click', function () {
      clearAlert();

      var oldPassword = $('#oldPassword').val();
      var password    = $('#password').val();
      var confirm     = $('#password_confirmation').val();

      if (!oldPassword || !password || !confirm) {
        showAlert('Todos los campos son obligatorios.');
        return;
      }
      if (password.length < 6) {
        showAlert('La nueva contraseña requiere al menos 6 caracteres.');
        return;
      }
      if (password !== confirm) {
        showAlert('Las contraseñas no coinciden.');
        return;
      }

      var payload = {
        oldPassword: oldPassword,
        password: password,
        password_confirmation: confirm
      };
      payload[csrfName] = csrfHash;

      setLoading(true);

      $.ajax({
        url: '<?= base_url('profile/update/password') ?>',
        type: 'POST',
        dataType: 'json',
        data: payload,
        success: function (res) {
          if (res.csrf_name && res.csrf_token) {
            csrfName = res.csrf_name;
            csrfHash = res.csrf_token;
          }
          if (res.ok) {
            $('#pwFormState').hide();
            $('#pwSuccessState').show();
          } else {
            setLoading(false);
            showAlert(res.error || res.message || 'No se pudo actualizar la contraseña.');
          }
        },
        error: function () {
          setLoading(false);
          showAlert('Ocurrió un error. Inténtalo de nuevo más tarde.');
        }
      });
    });
  })();
</script>

<style>
  .profile-card {
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    margin-bottom: 1.75rem;
  }
  .profile-card__head {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--color-neutral-200);
  }
  .profile-card__avatar {
    width: 84px; height: 84px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--bg-surface);
    box-shadow: 0 0 0 1px var(--color-neutral-200);
    background: var(--bg-surface);
    flex-shrink: 0;
  }
  .profile-card__name { margin: 0 0 0.35rem; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); }
  .profile-card__role {
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-size: 0.8rem; font-weight: 600;
    color: var(--color-blue-700);
    background: var(--color-blue-50);
    padding: 0.25rem 0.7rem;
    border-radius: 50px;
  }
  /* Número de empleado: badge destacado, dato de uso frecuente en la intranet */
  .profile-emp-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    margin-left: 0.5rem;
    font-size: 0.85rem; font-weight: 600;
    color: #92400e;
    background: #fef3c7;
    border: 1px solid #fcd34d;
    padding: 0.3rem 0.8rem;
    border-radius: 50px;
  }
  .profile-emp-badge strong { font-weight: 800; letter-spacing: 0.02em; }
  .profile-emp-badge i { font-size: 1rem; }
  @media (max-width: 575.98px) {
    .profile-emp-badge { margin-left: 0; margin-top: 0.4rem; }
  }
  .profile-card__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.1rem 1.5rem;
    padding: 1.25rem 0;
  }
  .profile-info { display: flex; flex-direction: column; gap: 0.2rem; }
  .profile-info__label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; color: var(--text-disabled); }
  .profile-info__value { font-size: 0.95rem; color: var(--text-primary); word-break: break-word; }
  .profile-card__note {
    margin: 0;
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.82rem;
    color: var(--text-muted);
    background: var(--bg-page);
    border-radius: 10px;
    padding: 0.7rem 0.9rem;
  }
  .profile-card__note i { color: var(--color-blue-500); font-size: 1rem; }

  .profile-section-title { margin-bottom: 1rem; }
  .profile-section-title h6 { margin: 0 0 0.15rem; font-size: 1rem; font-weight: 700; color: var(--text-primary); }
  .profile-section-title span { font-size: 0.85rem; color: var(--text-muted); }

  /* Tarjeta de seguridad */
  .security-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    margin-bottom: 1.75rem;
  }
  .security-card__info { display: flex; align-items: center; gap: 0.9rem; }
  .security-card__icon {
    width: 42px; height: 42px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 11px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.3rem;
  }
  .security-card__label { display: block; font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
  .security-card__desc  { display: block; font-size: 0.83rem; color: var(--text-muted); }
  .security-card__btn { display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap; }

  /* Modal de contraseña */
  .pw-modal { border: none; border-radius: 16px; overflow: hidden; }
  .pw-modal__header { align-items: flex-start; border-bottom: 1px solid var(--color-neutral-200); padding: 1.25rem 1.35rem; }
  .pw-modal__title-wrap { display: flex; align-items: center; gap: 0.8rem; }
  .pw-modal__title-icon {
    width: 44px; height: 44px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 12px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.4rem;
  }
  .pw-modal .modal-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
  .pw-modal__subtitle { font-size: 0.82rem; color: var(--text-muted); }
  .pw-modal .modal-body { padding: 1.35rem; }
  .pw-modal__footer { border-top: 1px solid var(--color-neutral-200); padding: 1rem 1.35rem; }

  .pw-field { margin-bottom: 1.1rem; }
  .pw-field:last-child { margin-bottom: 0; }
  .pw-label { display: block; font-size: 0.83rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.35rem; }
  .pw-req { color: var(--bs-danger); }
  .pw-input-wrap { position: relative; }
  .pw-input-wrap .form-control { padding-right: 2.6rem; }
  .pw-toggle {
    position: absolute;
    top: 50%; right: 0.5rem;
    transform: translateY(-50%);
    border: none; background: transparent;
    color: var(--text-disabled);
    font-size: 1.1rem;
    line-height: 1;
    padding: 0.25rem;
    cursor: pointer;
  }
  .pw-toggle:hover { color: var(--color-blue-600); }
  .pw-hint { display: block; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.3rem; }

  .pw-alert {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.85rem;
    color: var(--bs-danger-text);
    background: var(--bs-danger-bg-subtle);
    border: 1px solid rgba(215,44,13,0.2);
    border-radius: 10px;
    padding: 0.65rem 0.85rem;
    margin-bottom: 1.1rem;
  }

  .pw-btn-label, .pw-btn-spinner { display: inline-flex; align-items: center; gap: 0.4rem; }

  /* Estado de éxito */
  .pw-success { text-align: center; padding: 2.25rem 1.75rem; }
  .pw-success__icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 64px; height: 64px;
    border-radius: 50%;
    background: var(--color-success-surface);
    color: var(--color-success-default);
    font-size: 2.1rem;
    margin-bottom: 1rem;
  }
  .pw-success__title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem; }
  .pw-success__text { font-size: 0.88rem; color: var(--text-muted); line-height: 1.5; margin: 0 0 1.4rem; }

  .system-card {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
    height: 100%;
    min-height: 148px;
    padding: 1.15rem;
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 14px;
    text-decoration: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .system-card:hover {
    border-color: var(--color-blue-300);
    box-shadow: 0 6px 18px rgba(23,115,200,0.08);
  }
  .system-card__ext {
    position: absolute;
    top: 1rem; right: 1rem;
    color: var(--text-disabled);
    font-size: 1rem;
  }
  .system-card:hover .system-card__ext { color: var(--color-blue-500); }
  .system-card__head {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding-right: 1.5rem;
  }
  .system-card__icon {
    width: 42px; height: 42px;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border-radius: 11px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.3rem;
  }
  .system-card__title { font-size: 0.98rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
  .system-card__desc {
    font-size: 0.83rem;
    color: var(--text-muted);
    line-height: 1.45;
  }
</style>
