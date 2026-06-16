<div class="container-fluid">
  <div class="card">
    <div class="card-body p-4">
      <div class="page-header">
        <div>
          <h5 class="page-header__title">Quejas y sugerencias</h5>
          <p class="page-header__subtitle">Queremos escucharte. Rellena el formulario y respondemos lo antes posible.</p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <form action="<?= base_url('suggestion/create') ?>" method="POST">
            <?php if (session('success') === null) : ?>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="author" value="<?= session('user')->id ?>">

              <div class="form-section" style="border-top:none; padding-top:0; margin-top:0;">
                <h6 class="form-section__title">Datos de contacto</h6>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="name">Nombre(s)</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      value="<?= session('user')->name ?> <?= session('user')->lastname ?>"
                      class="form-control bg-light"
                      required=""
                      readonly="true"
                    >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="email">E-mail</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      value="<?= session('user')->email ?>"
                      class="form-control bg-light"
                      required=""
                      readonly="true"
                    >
                  </div>
                </div>
                <div class="col-12">
                  <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="publishCheck" name="publish">
                    <label class="form-check-label" for="publishCheck">Enviar de forma anonima</label>
                  </div>
                </div>
              </div>

              <div class="form-section">
                <h6 class="form-section__title">Tu mensaje</h6>
              </div>
              <div class="mb-3">
                <label class="form-label" for="title">Asunto</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Describe brevemente el asunto" required="">
              </div>
              <div class="mb-4">
                <label class="form-label" for="message">Mensaje</label>
                <textarea
                  id="message"
                  name="message"
                  class="form-control"
                  placeholder="Escribe tu mensaje aquí..."
                  required=""
                  rows="6"
                  style="resize:vertical;"
                ></textarea>
              </div>
            <?php endif; ?>

            <?php if (session('message') !== null) : ?>
              <div class="alert alert-danger mb-3"><?= session('message'); ?></div>
            <?php endif; ?>
            <?php if (session('success') !== null) : ?>
              <div class="alert alert-success mb-3"><?= session('success'); ?></div>
            <?php endif; ?>

            <?php if (session('success') === null) : ?>
              <button type="submit" class="btn btn-primary">Enviar mensaje</button>
            <?php else: ?>
              <a href="<?= base_url('quejas-sugerencias') ?>" class="btn btn-outline-secondary">Enviar otra sugerencia</a>
            <?php endif; ?>
          </form>
        </div>
        <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center p-4">
          <img src="<?= base_url('assets/images/logos/logo-2.png') ?>" alt="Quejas y Sugerencias" height="120" style="opacity:0.5;">
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const publishCheck = document.getElementById('publishCheck');
  const name  = document.getElementById('name');
  const email = document.getElementById('email');

  publishCheck.addEventListener('change', () => {
    if(publishCheck.checked){
      name.value  = 'Anonimo';
      email.value = 'anonimo@anonimo.com';
    } else {
      name.value  = '<?= session('user')->name ?> <?= session('user')->lastname ?>';
      email.value = '<?= session('user')->email ?>';
    }
  });
</script>
