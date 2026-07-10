<?php
  $rolLabel = [
    'admin'    => 'Administrador',
    'operator' => 'Operador',
    'user'     => 'Usuario',
  ][$user->rol] ?? 'Usuario';

  $fmt = function ($dt) {
    return ($dt && $dt !== '0000-00-00 00:00:00') ? date('d/m/Y H:i', strtotime($dt)) : '—';
  };

  // Sistemas externos disponibles (por ahora 2).
  $sistemas = [
    [
      'title' => 'Help Desk',
      'desc'  => 'Levanta y da seguimiento a tus tickets de soporte.',
      'icon'  => 'ti ti-ticket',
      'url'   => 'https://helpdesk.trantortechnologies.mx/',
    ],
    [
      'title' => 'Documentación de uso GLPI',
      'desc'  => 'Guias y documentacion para el uso de GLPI',
      'icon'  => 'ti ti-book',
      'url'   => 'https://docs.helpdesk.trantortechnologies.mx/',
    ],
    [
      'title' => 'Correo Staff',
      'desc'  => 'Accede a tu correo corporativo.',
      'icon'  => 'ti ti-mail',
      'url'   => 'https://mail.staff.trantortechnologies.mx/',
    ],
  ];
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
      </div>
    </div>

    <div class="profile-card__grid">
      <div class="profile-info">
        <span class="profile-info__label">Correo electrónico</span>
        <span class="profile-info__value"><?= esc($user->email) ?></span>
      </div>
      <div class="profile-info">
        <span class="profile-info__label">Rol</span>
        <span class="profile-info__value"><?= $rolLabel ?></span>
      </div>
      <div class="profile-info">
        <span class="profile-info__label">Último acceso</span>
        <span class="profile-info__value"><?= $fmt($user->last_login) ?></span>
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

  <!-- Sistemas externos -->
  <div class="profile-section-title">
    <h6>Sistemas externos</h6>
    <span>Accesos rápidos a las plataformas de la empresa</span>
  </div>

  <div class="row g-3">
    <?php foreach ($sistemas as $s): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="<?= $s['url'] ?>" target="_blank" rel="noopener" class="system-card">
          <i class="ti ti-external-link system-card__ext"></i>
          <div class="system-card__head">
            <span class="system-card__icon"><i class="<?= $s['icon'] ?>"></i></span>
            <span class="system-card__title"><?= $s['title'] ?></span>
          </div>
          <span class="system-card__desc"><?= $s['desc'] ?></span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

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
