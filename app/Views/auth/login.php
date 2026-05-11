<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Siswa - V-Lab ASJ</title>
    <link rel="shortcut icon" href="<?= base_url('logo-vlab.png'); ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="text-center mb-4">
                <a href="<?= base_url('/') ?>" class="text-decoration-none fw-bold fs-3 text-primary-custom">
                    <i class="fa-solid fa-terminal me-2"></i>V-Lab ASJ
                </a>
            </div>

            <div class="mb-4 text-center">
                <h3 class="fw-bold text-dark mb-1">Selamat Datang di Halaman Login!</h3>
                <p class="text-secondary small">Silakan Siswa login untuk mengakses praktikum dan materi.</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 small rounded-3 mb-3"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success py-2 small rounded-3 mb-3"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('auth/process_login') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">USERNAME / EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-user text-secondary"></i></span>
                        <input type="text" name="login_id" class="form-control" placeholder="Username atau Email"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock text-secondary"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control"
                            placeholder="Password" required minlength="8">
                        <span class="input-group-text"><i class="fa-solid fa-eye" id="togglePassword"
                                style="cursor: pointer;"></i></span>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <a href="<?= base_url('auth/lupa_password') ?>"
                        class="text-decoration-none fw-bold small text-primary-custom">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-blue w-100 py-2 fs-6 shadow-sm">
                    MASUK <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>

                <div class="text-center position-relative my-4">
                    <hr class="text-secondary opacity-25">
                    <span
                        class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-secondary small">ATAU</span>
                </div>

                <?php if (isset($googleButton)): ?>
                    <a href="<?= $googleButton ?>"
                        class="btn btn-white w-100 py-2 fs-6 d-flex align-items-center justify-content-center text-dark text-decoration-none shadow-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo" width="20"
                            height="20" class="me-2">
                        Masuk dengan Google
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-white w-100 py-2 text-secondary" disabled>Google Login belum
                        aktif</button>
                <?php endif; ?>
            </form>

            <div class="text-center mt-4">
                <p class="text-secondary small">Belum punya akun?
                    <a href="<?= base_url('register') ?>"
                        class="text-decoration-none fw-bold text-primary-custom">Daftar Akun</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>