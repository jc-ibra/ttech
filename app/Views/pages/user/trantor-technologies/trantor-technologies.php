<?php
  // Contenido administrable (con fallback a defaults en el modelo).
  // Enlaces internos de las tarjetas: son estructura de navegación, no contenido.
  $cardLinks = [
    1 => base_url('trantor-informa'),
    2 => base_url('documentos'),
    3 => base_url('quejas-sugerencias'),
    4 => base_url('directorio'),
  ];

  // "Núcleo de Valores": un valor por línea.
  $values = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $c['values_list'] ?? '')));
?>
<!-- Parallax -->
<section class="parallax-container pt-5" data-parallax-img="<?= base_url($c['hero_image']) ?>">
  <div class="parallax-content section-xl context-dark text-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10 col-xl-9">
          <h2><?= esc($c['hero_title']) ?> <span class="text-light"><?= esc($c['hero_highlight']) ?></span></h2>
          <div class="heading-5 font-weight-normal"><?= esc($c['hero_subtitle']) ?></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Mission-->
<section class="section bg-default section-md">
  <div class="container">
    <div class="row row-30">
      <div class="col-md-6">
        <h2 class="title-icon"><span class="icon icon-default mercury-icon-target-2"></span><span><?= esc($c['mission_title']) ?></span></h2>
        <p class="big"><?= esc($c['mission_text']) ?></p>
        <br><br>
        <h2 class="title-icon"><span class="icon icon-default mercury-icon-lightbulb-gears"></span><span><?= esc($c['vision_title']) ?></span></h2>
        <p class="big"><?= esc($c['vision_text']) ?></p>
        <br><br>
        <h2 class="title-icon"><span class="icon icon-default mercury-icon-group"></span><span><?= esc($c['values_title']) ?></span></h2>
        <ul class="list-marked-2">
          <?php foreach ($values as $value): ?>
            <li><?= esc($value) ?></li>
          <?php endforeach ?>
        </ul>
      </div>
      <div class="col-md-6">
        <div class="row">
          <?php foreach ([1, 2, 3, 4] as $i): ?>
            <div class="col-6">
              <!--Box counter-->
              <div class="box-counter">
                <div class="box-counter-main">
                  <div class="counter"><?= esc($c['counter' . $i . '_value']) ?></div>
                </div>
                <p class="box-counter-title"><?= esc($c['counter' . $i . '_label']) ?></p>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Intranet Secciones-->
<section class="section bg-default section-md">
  <div class="container">
    <h2 class="title-icon"><span class="icon icon-default mercury-icon-cloud-2"></span><span><?= esc($c['sections_title']) ?></span></h2>
    <?php foreach ([1, 2, 3, 4] as $i): ?>
      <?php $side = $i % 2 === 1 ? 'left' : 'right'; ?>
      <?php $anim = $i % 2 === 1 ? 'fadeInRight' : 'fadeInLeft'; ?>
      <div class="box-image-small box-image-small-<?= $side ?>">
        <div class="item-image bg-image novi-background" style="background-image: url(<?= base_url($c['card' . $i . '_image']) ?>)"></div>
        <div class="item-body wow <?= $anim ?>">
          <h4><a href="<?= $cardLinks[$i] ?>"><?= esc($c['card' . $i . '_title']) ?></a></h4>
          <p class="big"><?= esc($c['card' . $i . '_text']) ?></p>
          <a class="button button-primary" href="<?= $cardLinks[$i] ?>"><?= esc($c['card' . $i . '_button']) ?></a>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</section>
