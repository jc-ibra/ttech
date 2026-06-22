    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-12 col-sm-10 col-md-7 col-lg-5" style="max-width: 480px;">
            <div class="card mb-0">
              <div class="card-body">
                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="<?= base_url('assets/images/logos/logo-1.png') ?>" height="80" alt="">
                </a>
                <p class="text-center">Trantor Technologies | Iniciar sesión</p>
                <form method="post" action="<?= base_url('auth/login') ?>">
                  <?php echo csrf_field(); ?>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Usuario</label>
                    <input 
                      type="email" 
                      id="email" 
                      name="email" 
                      class="form-control" 
                      required=""
                    >
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                    <div class="input-group">
                      <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required=""
                      >
                      <button type="button" class="btn bordered" id="togglePassword">
                        <i class="ti ti-eye"></i>
                      </button>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Entrar</button>
                  <?php if (session('message') !== null) : ?>
                    <div class="error text-center">
                      <?= session('message'); ?>
                    </div>
                  <?php endif; ?>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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