<?php
  $unreadCount = 0;
  foreach ($alerts as $a) { if ($a->readed == 0) $unreadCount++; }
  $dateFmt = new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
?>
<div class="container-fluid mw-1600">
  <div class="card alerts-card">
    <div class="card-body p-4">

      <div class="alerts-head">
        <div>
          <h5 class="alerts-head__title"><i class="ti ti-bell"></i> Alertas</h5>
          <p class="alerts-head__sub">Notificaciones y avisos de la plataforma.</p>
        </div>
        <?php if ($unreadCount > 0): ?>
          <span class="alerts-head__count"><?= $unreadCount ?> sin leer</span>
        <?php endif; ?>
      </div>

      <?php if (!empty($alerts)): ?>
        <ul class="alerts-list">
          <?php foreach ($alerts as $alert): ?>
            <?php
              // Decodificamos el JSON
              $alert->data = json_decode($alert->data);
              $action = '';   // URL donde envia la alerta
              $title  = '';   // Titulo de la alerta
              $icon   = 'ti ti-bell';

              if ($alert->type == 'feed_new') {
                $icon = 'ti ti-speakerphone';
                if (is_object($alert->data) && property_exists($alert->data, 'feed')) {
                  $action = base_url("trantor-informa?scrollTo=feed_c_" . $alert->data->feed);
                  $title  = $alert->message . "<b> " . $alert->data->author_name . "</b>" . " publicó algo";
                }
              }
              if ($title === '') { $title = esc($alert->message); }

              $dateTxt = ucfirst($dateFmt->format(new \DateTime($alert->created_at)));
            ?>
            <li class="alert-item <?= $alert->readed == 0 ? 'alert__closed' : '' ?>">
              <span class="alert-item__icon"><i class="<?= $icon ?>"></i></span>
              <div class="alert-item__body">
                <p class="alert-item__title"><?= $title ?></p>
                <span class="alert-item__date"><i class="ti ti-calendar-event"></i> <?= $dateTxt ?></span>
              </div>
              <div class="alert-item__actions">
                <?php if ($alert->readed == 0): ?>
                  <button type="button" alertType="button" class="alert-btn alert-btn--ghost markAsRead" alertId="<?= $alert->id ?>">
                    <i class="ti ti-check"></i> Marcar leído
                  </button>
                <?php endif; ?>
                <a alertId="<?= $alert->id ?>" href="<?= $action ?>" class="alert-btn alert-btn--primary markAsRead scroll-link">
                  Ver <i class="ti ti-arrow-right"></i>
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="alerts-empty">
          <div class="alerts-empty__icon"><i class="ti ti-bell-off"></i></div>
          <h6>Sin alertas</h6>
          <p>No tienes notificaciones nuevas por ahora.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  .alerts-card { border: 1px solid var(--color-neutral-200); border-radius: 16px; }

  .alerts-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .alerts-head__title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 1.2rem; font-weight: 800; color: var(--text-primary); margin: 0;
  }
  .alerts-head__title i { color: var(--color-blue-600); }
  .alerts-head__sub { font-size: 0.88rem; color: var(--text-muted); margin: 0.2rem 0 0; }
  .alerts-head__count {
    flex: 0 0 auto;
    font-size: 0.78rem; font-weight: 700;
    color: var(--color-blue-600); background: var(--color-blue-50);
    padding: 4px 12px; border-radius: 9999px; white-space: nowrap;
  }

  .alerts-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.6rem; }

  .alert-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 0.9rem 1rem;
    border: 1px solid var(--color-neutral-200);
    border-radius: 12px;
    background: var(--bg-surface);
    overflow: hidden;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .alert-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

  /* No leído */
  .alert-item.alert__closed {
    background: var(--color-blue-50);
    border-color: var(--color-blue-200, #BFD6EE);
  }
  .alert-item.alert__closed::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--color-blue-600);
  }

  .alert-item__icon {
    position: relative;
    flex: 0 0 44px;
    width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 11px;
    background: var(--bg-surface-alt);
    color: var(--text-muted);
    font-size: 1.25rem;
  }
  .alert-item.alert__closed .alert-item__icon {
    background: var(--color-blue-100, #DBEAFE);
    color: var(--color-blue-600);
  }
  /* Punto de no leído */
  .alert-item.alert__closed .alert-item__icon::after {
    content: '';
    position: absolute;
    top: -2px; right: -2px;
    width: 11px; height: 11px;
    border-radius: 50%;
    background: var(--color-blue-600);
    border: 2px solid var(--bg-surface);
  }

  .alert-item__body { flex: 1 1 auto; min-width: 0; }
  .alert-item__title {
    margin: 0 0 0.2rem;
    font-size: 0.92rem;
    color: var(--text-primary);
    line-height: 1.4;
  }
  .alert-item.alert__closed .alert-item__title { font-weight: 600; }
  .alert-item__date {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-size: 0.78rem; color: var(--text-muted);
  }
  .alert-item__date i { font-size: 0.9rem; }

  .alert-item__actions { flex: 0 0 auto; display: flex; align-items: center; gap: 0.5rem; }
  .alert-btn {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.45rem 0.85rem;
    border-radius: 9px;
    font-size: 0.82rem; font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
  }
  .alert-btn i { font-size: 1rem; }
  .alert-btn--ghost {
    color: var(--text-secondary);
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
  }
  .alert-btn--ghost:hover { background: var(--bg-surface-alt); color: var(--text-primary); }
  .alert-btn--primary {
    color: #fff;
    background: var(--color-blue-600);
    border: 1px solid var(--color-blue-600);
  }
  .alert-btn--primary:hover { background: var(--color-blue-700, #0f4f8a); border-color: var(--color-blue-700, #0f4f8a); }

  /* Estado vacío */
  .alerts-empty { text-align: center; padding: 3rem 1.25rem; color: var(--text-muted); }
  .alerts-empty__icon {
    width: 72px; height: 72px; margin: 0 auto 1rem;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; font-size: 2rem;
    color: var(--color-blue-600); background: var(--color-blue-50);
  }
  .alerts-empty h6 { color: var(--text-primary); font-weight: 700; margin: 0 0 0.25rem; }
  .alerts-empty p { font-size: 0.88rem; margin: 0; }

  @media (max-width: 640px) {
    .alert-item { flex-wrap: wrap; }
    .alert-item__actions { width: 100%; justify-content: flex-end; }
  }
</style>

<script>
  var csrfName      = '<?= $csrfName ?>';
  var csrfHash      = '<?= $csrfHash ?>';
</script>
