<?php
  $catalogos = [
    [
      'title' => 'Puestos',
      'desc'  => 'Administra los puestos u ocupaciones de la organización.',
      'icon'  => 'ti ti-briefcase',
      'url'   => base_url('ocupation'),
    ],
    [
      'title' => 'Departamentos',
      'desc'  => 'Administra los departamentos de la organización.',
      'icon'  => 'ti ti-directions',
      'url'   => base_url('department'),
    ],
    [
      'title' => 'Áreas',
      'desc'  => 'Administra las áreas de la organización.',
      'icon'  => 'ti ti-map-2',
      'url'   => base_url('area'),
    ],
  ];
?>
<div class="container-fluid mw-1600">
  <div class="page-header mb-4">
    <div>
      <h5 class="page-header__title">Catálogos</h5>
      <p class="page-header__subtitle">Configuración base usada por empleados y organigramas</p>
    </div>
  </div>

  <div class="row g-4">
    <?php foreach ($catalogos as $cat): ?>
      <div class="col-md-6 col-lg-4">
        <a href="<?= $cat['url'] ?>" class="catalogo-card">
          <span class="catalogo-card__icon"><i class="<?= $cat['icon'] ?>"></i></span>
          <span class="catalogo-card__title"><?= $cat['title'] ?></span>
          <span class="catalogo-card__desc"><?= $cat['desc'] ?></span>
          <span class="catalogo-card__go">Abrir <i class="ti ti-arrow-right"></i></span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<style>
  .catalogo-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 148px;
    padding: 1.15rem;
    background: var(--bg-surface);
    border: 1px solid var(--color-neutral-200);
    border-radius: 14px;
    text-decoration: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .catalogo-card:hover {
    border-color: var(--color-blue-300);
    box-shadow: 0 6px 18px rgba(23,115,200,0.08);
  }
  .catalogo-card__icon {
    width: 42px; height: 42px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 11px;
    background: var(--color-blue-50);
    color: var(--color-blue-600);
    font-size: 1.3rem;
    margin-bottom: 0.7rem;
  }
  .catalogo-card__title {
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
  }
  .catalogo-card__desc {
    font-size: 0.83rem;
    color: var(--text-muted);
    line-height: 1.45;
    flex: 1 1 auto;
    margin-bottom: 0.85rem;
  }
  .catalogo-card__go {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--color-blue-600);
  }
</style>
