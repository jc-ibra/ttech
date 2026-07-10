<?php
    // Atajos: mismos accesos directos que en /profile (sistemas externos)
    $atajos = [
        [ 'title' => 'Help Desk',                'icon' => 'ti ti-ticket', 'url' => 'https://helpdesk.trantortechnologies.mx/' ],
        [ 'title' => 'Documentación GLPI',       'icon' => 'ti ti-book',   'url' => 'https://docs.helpdesk.trantortechnologies.mx/' ],
        [ 'title' => 'Correo Staff',             'icon' => 'ti ti-mail',   'url' => 'https://mail.staff.trantortechnologies.mx/' ],
    ];
?>
<div class="tinf__card_filter feed-side-col">

<div class="feed-side">
    <div class="feed-side__head">
        <h5 class="feed-side__hi">Hola, <?= esc(session('user')->name) ?></h5>
        <a href="<?= base_url('profile') ?>" class="feed-side__avatar" title="Ver mi perfil">
            <img src="<?= base_url(session('user')->photo) ?>" alt="<?= esc(session('user')->name) ?>"
                 onerror="this.onerror=null;this.src='<?= base_url('assets/images/anonimo.jpg') ?>';">
        </a>
    </div>

    <div class="feed-side__filters">
        <button type="button" class="feed-filters-toggle is-collapsed" id="feedFiltersToggle" aria-expanded="false" aria-controls="feedFiltersList">
            <span class="feed-filters-toggle__label"><i class="ti ti-adjustments-horizontal"></i> Filtros</span>
            <i class="ti ti-chevron-up feed-filters-toggle__caret"></i>
        </button>

        <div class="feed-filters-list is-collapsed" id="feedFiltersList">
            <a href="<?= base_url('/trantor-informa/') ?>" class="feed-filter<?= $filter == 'all' ? ' is-active' : '' ?>">
                <span class="feed-filter__ico"><i class="ti ti-layout-grid"></i></span>
                <span class="feed-filter__text">Todas</span>
            </a>
            <a href="<?= base_url('/trantor-informa/text') ?>" class="feed-filter<?= $filter == 'text' ? ' is-active' : '' ?>">
                <span class="feed-filter__ico"><i class="ti ti-message"></i></span>
                <span class="feed-filter__text">Solo Texto</span>
            </a>
            <a href="<?= base_url('/trantor-informa/file') ?>" class="feed-filter<?= $filter == 'file' ? ' is-active' : '' ?>">
                <span class="feed-filter__ico"><i class="ti ti-paperclip"></i></span>
                <span class="feed-filter__text">Con archivos</span>
            </a>
            <a href="<?= base_url('/trantor-informa/image') ?>" class="feed-filter<?= $filter == 'image' ? ' is-active' : '' ?>">
                <span class="feed-filter__ico"><i class="ti ti-photo"></i></span>
                <span class="feed-filter__text">Con Imagen</span>
            </a>
        </div>
    </div>
</div>

<!-- Atajos -->
<div class="feed-side feed-shortcuts">
    <div class="feed-shortcuts__head">
        <span class="feed-side__label">Atajos</span>
        <span class="feed-shortcuts__hint">Accesos a plataformas de la empresa</span>
    </div>
    <div class="feed-shortcuts__list">
        <?php foreach ($atajos as $a): ?>
            <a href="<?= $a['url'] ?>" target="_blank" rel="noopener" class="feed-shortcut">
                <span class="feed-shortcut__icon"><i class="<?= $a['icon'] ?>"></i></span>
                <span class="feed-shortcut__text"><?= esc($a['title']) ?></span>
                <i class="ti ti-external-link feed-shortcut__ext"></i>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</div><!-- /.feed-side-col -->

<style>
    /* Columna: apila las dos cards (sin apariencia de card propia) */
    .feed-side-col {
        padding: 0;
        background: transparent;
        border: none;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .feed-side {
        background: var(--bg-surface);
        border: 1px solid var(--color-neutral-200);
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .feed-side__head {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.85rem;
        padding-bottom: 1.1rem;
        border-bottom: 1px solid var(--color-neutral-200);
    }
    .feed-side__hi {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .feed-side__avatar img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--bg-surface);
        box-shadow: 0 0 0 1px var(--color-neutral-200);
        background: var(--bg-surface);
    }
    .feed-side__filters { padding-top: 1.1rem; }
    .feed-side__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-disabled);
    }

    /* Botón Filtros (colapsable) */
    .feed-filters-toggle {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.55rem 0.7rem;
        margin-bottom: 0.4rem;
        background: var(--color-blue-50);
        border: 1px solid transparent;
        border-radius: 10px;
        color: var(--color-blue-700);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .feed-filters-toggle:hover { border-color: var(--color-blue-200, var(--color-neutral-200)); }
    .feed-filters-toggle__label { display: inline-flex; align-items: center; gap: 0.45rem; }
    .feed-filters-toggle__label i { font-size: 1.05rem; }
    .feed-filters-toggle__caret { transition: transform 0.2s ease; font-size: 1rem; }
    .feed-filters-toggle.is-collapsed .feed-filters-toggle__caret { transform: rotate(180deg); }

    .feed-filters-list {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        overflow: hidden;
        max-height: 320px;
        opacity: 1;
        transition: max-height 0.25s ease, opacity 0.2s ease, margin 0.2s ease;
    }
    .feed-filters-list.is-collapsed {
        max-height: 0;
        opacity: 0;
        margin-top: 0;
    }

    .feed-filter {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.5rem 0.6rem;
        border-radius: 10px;
        color: var(--text-secondary) !important;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        transition: background var(--duration-base), color var(--duration-base);
    }
    .feed-filter:hover { background: var(--color-blue-50); color: var(--color-blue-700) !important; }
    .feed-filter__text { color: inherit; }
    .feed-filter__ico {
        width: 32px; height: 32px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: 9px;
        background: var(--color-neutral-100);
        color: var(--text-muted);
        font-size: 1.05rem;
        transition: background var(--duration-base), color var(--duration-base);
    }
    .feed-filter:hover .feed-filter__ico { background: #fff; color: var(--color-blue-600); }
    .feed-filter.is-active {
        background: var(--color-blue-500);
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(23,115,200,0.18);
    }
    .feed-filter.is-active .feed-filter__ico {
        background: rgba(255,255,255,0.22);
        color: #fff;
    }

    /* Atajos */
    .feed-shortcuts__head {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
        margin-bottom: 0.75rem;
    }
    .feed-shortcuts__hint {
        font-size: 0.78rem;
        color: var(--text-muted);
    }
    .feed-shortcuts__list {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .feed-shortcut {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.55rem 0.6rem;
        border: 1px solid var(--color-neutral-200);
        border-radius: 11px;
        text-decoration: none;
        color: var(--text-primary);
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }
    .feed-shortcut:hover {
        background: var(--color-blue-50);
        border-color: var(--color-blue-200, var(--color-neutral-200));
    }
    .feed-shortcut__icon {
        width: 34px; height: 34px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: 9px;
        background: var(--color-blue-50);
        color: var(--color-blue-600);
        font-size: 1.1rem;
    }
    .feed-shortcut__text {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .feed-shortcut__ext {
        flex-shrink: 0;
        color: var(--text-muted);
        font-size: 1rem;
    }
    .feed-shortcut:hover .feed-shortcut__ext { color: var(--color-blue-600); }
</style>

<script>
    (function () {
        var toggle = document.getElementById('feedFiltersToggle');
        var list   = document.getElementById('feedFiltersList');
        if (!toggle || !list) return;
        toggle.addEventListener('click', function () {
            var collapsed = list.classList.toggle('is-collapsed');
            toggle.classList.toggle('is-collapsed', collapsed);
            toggle.setAttribute('aria-expanded', String(!collapsed));
        });
    })();
</script>
