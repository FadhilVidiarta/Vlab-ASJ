<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($token) ? 'Reset Password' : 'Lupa Password' ?> - V-Lab ASJ</title>
    <link rel="shortcut icon" href="<?= base_url('logo-vlab.png'); ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="text-center mb-4">
                <h4 class="fw-bold text-dark mb-2">
                    <?= isset($token) ? 'Reset Password 🔑' : 'Lupa Password? 🔒' ?>
                </h4>
                <p class="text-secondary small">
                    <?= isset($token) ? 'Silakan buat password baru Anda.' : 'Masukkan email Anda untuk mereset password.' ?>
                </p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2 small rounded-3 mb-3"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success py-2 small rounded-3 mb-3"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (isset($token)): ?>
                <form action="<?= base_url('auth/process_reset') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">PASSWORD BARU</label>
                        <div class="input-group">
                            <input type="password" name="password" id="pass1" class="form-control"
                                placeholder="Masukan Password Baru" required minlength="8">
                            <span class="input-group-text"><i class="fa-solid fa-eye toggle-pass" data-target="pass1"
                                    style="cursor: pointer;"></i></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">ULANGI PASSWORD</label>
                        <div class="input-group">
                            <input type="password" name="conf_password" id="pass2" class="form-control"
                                placeholder="Ulangi Password Baru" required>
                            <span class="input-group-text"><i class="fa-solid fa-eye toggle-pass" data-target="pass2"
                                    style="cursor: pointer;"></i></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-blue w-100 py-2">Simpan Password</button>
                </form>

            <?php else: ?>
                <form action="<?= base_url('auth/process_forgot') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">EMAIL TERDAFTAR</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope text-secondary"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="email@gmail.com" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-blue w-100 py-2">Kirim Link Reset</button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="<?= base_url('login') ?>" class="text-decoration-none text-secondary small fw-bold">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>

        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-pass').forEach(item => {
            item.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>