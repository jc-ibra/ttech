<div class="min__h__100 organization__page org-page">

  <!-- Botón flotante de filtros -->
  <button type="button" class="org-fab" id="orgFiltersToggle" aria-expanded="false" aria-controls="orgFilters">
    <i class="ti ti-adjustments-horizontal"></i>
    <span>Filtros</span>
    <i class="ti ti-chevron-down org-fab__caret"></i>
  </button>

  <!-- Panel flotante (oculto por defecto) -->
  <div class="org-filters is-collapsed" id="orgFilters">
    <div class="org-filters__grid">
      <div class="org-filters__field">
        <label class="form-label" for="area">Área</label>
        <select class="form-select select2area" name="area" id="area">
          <option value="">Todos</option>
          <?php foreach($areas as $area): ?>
            <option value="<?= $area->id ?>"><?= $area->name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="org-filters__field">
        <label class="form-label" for="department">Departamento</label>
        <select class="form-select select2" name="department" id="department">
          <option value="">Todos</option>
          <?php foreach($departments as $department): ?>
            <option value="<?= $department->id ?>"><?= $department->name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="org-filters__field">
        <label class="form-label" for="general">Organigrama general</label>
        <select class="form-select select2general" name="general" id="general">
          <option value="">Selecciona organigrama</option>
          <?php foreach($generalOrganigramas as $organigrama): ?>
            <option value="<?= $organigrama->id ?>"><?= $organigrama->name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="card b-s-none org-chart-card">
    <div id="chart-container" class="min__h__100"></div>
  </div>
</div>

<style>
  .org-page { position: relative; }

  /* Botón flotante */
  .org-fab {
    position: absolute;
    top: 14px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 20;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.15rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
    background: var(--color-blue-600);
    border: none;
    border-radius: 9999px;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(17, 94, 163, 0.35);
    transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
  }
  .org-fab:hover {
    background: var(--color-blue-700, #0f4f8a);
    box-shadow: 0 8px 22px rgba(17, 94, 163, 0.45);
  }
  .org-fab:active { transform: translateX(-50%) scale(0.97); }
  .org-fab i { font-size: 1.05rem; }
  .org-fab__caret { transition: transform 0.2s ease; }
  .org-fab.is-open .org-fab__caret { transform: rotate(180deg); }

  /* Panel flotante */
  .org-filters {
    position: absolute;
    top: 62px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 19;
    width: min(920px, 94vw);
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 14px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.14);
    padding: 1.1rem 1.25rem;
    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
  }
  .org-filters.is-collapsed {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateX(-50%) translateY(-10px);
  }
  .org-filters__grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.9rem;
  }
  .org-filters__field {
    flex: 1 1 200px;
    min-width: 0;
  }
  .org-filters__field .form-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.2rem;
  }

  /* Deja espacio para que el botón no tape el nodo raíz */
  .org-chart-card { padding-top: 3.75rem; }

  @media (max-width: 768px) {
    .org-filters__field { flex-basis: 100%; }
  }
</style>

<script>
  (function () {
    var toggle = document.getElementById('orgFiltersToggle');
    var panel  = document.getElementById('orgFilters');
    if (!toggle || !panel) return;

    function setOpen(open) {
      panel.classList.toggle('is-collapsed', !open);
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', String(open));
    }

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      setOpen(panel.classList.contains('is-collapsed'));
    });

    // Cerrar al hacer clic fuera del panel/botón
    document.addEventListener('click', function (e) {
      if (panel.classList.contains('is-collapsed')) return;
      if (panel.contains(e.target) || toggle.contains(e.target)) return;
      setOpen(false);
    });
  })();
</script>
