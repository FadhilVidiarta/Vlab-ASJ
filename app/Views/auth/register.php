<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Siswa - V-Lab ASJ</title>
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
                <h3 class="fw-bold text-dark mb-1">Registrasi Siswa SMKN 1 Jiwan</h3>
                <p class="text-secondary small">Isi data diri dengan benar untuk membuat akun.</p>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger py-2 small rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc((string) $error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/process_register') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">NAMA LENGKAP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-id-card text-secondary"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control" 
                               value="<?= old('nama_lengkap') ?>" placeholder="Masukan Nama Anda" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope text-secondary"></i></span>
                        <input type="email" name="email" class="form-control" 
                               value="<?= old('email') ?>" placeholder="email@gmail.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary small">KELAS</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-graduation-cap text-secondary"></i></span>
                            <select name="kelas" class="form-select" required>
                                <option value="" disabled selected>Pilih Kelas</option>
                                <option value="X TKJ 1" <?= old('kelas') == 'X TKJ 1' ? 'selected' : '' ?>>X TKJ 1</option>
                                <option value="X TKJ 2" <?= old('kelas') == 'X TKJ 2' ? 'selected' : '' ?>>X TKJ 2</option>
                                <option value="X TKJ 3" <?= old('kelas') == 'X TKJ 3' ? 'selected' : '' ?>>X TKJ 3</option>
                                <option value="XI TKJ 1" <?= old('kelas') == 'XI TKJ 1' ? 'selected' : '' ?>>XI TKJ 1</option>
                                <option value="XI TKJ 2" <?= old('kelas') == 'XI TKJ 2' ? 'selected' : '' ?>>XI TKJ 2</option>
                                <option value="XI TKJ 3" <?= old('kelas') == 'XI TKJ 3' ? 'selected' : '' ?>>XI TKJ 3</option>
                                <option value="XII TKJ 1" <?= old('kelas') == 'XII TKJ 1' ? 'selected' : '' ?>>XII TKJ 1</option>
                                <option value="XII TKJ 2" <?= old('kelas') == 'XII TKJ 2' ? 'selected' : '' ?>>XII TKJ 2</option>
                                <option value="XII TKJ 3" <?= old('kelas') == 'XII TKJ 3' ? 'selected' : '' ?>>XII TKJ 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary small">NO. ABSEN</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-list-ol text-secondary"></i></span>
                            <input type="number" name="no_absen" class="form-control" 
                                   value="<?= old('no_absen') ?>" placeholder="Masukan no absen" required min="2" max="36">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">USERNAME</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-at text-secondary"></i></span>
                        <input type="text" name="username" class="form-control" 
                               value="<?= old('username') ?>" placeholder="Username" required minlength="4">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock text-secondary"></i></span>
                        <input type="password" name="password" id="passReg" class="form-control" 
                               placeholder="Minimal 8 karakter" required minlength="8">
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye" id="toggleReg" style="cursor: pointer;"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-blue w-100 py-2 fs-6 shadow-sm">
                    DAFTAR
                </button>

            </form>

            <div class="text-center mt-4">
                <p class="text-secondary small">Sudah punya akun?
                    <a href="<?= base_url('login') ?>" class="text-decoration-none fw-bold text-primary-custom">Login disini</a>
                </p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleReg = document.querySelector('#toggleReg');
        const passReg = document.querySelector('#passReg');

        toggleReg.addEventListener('click', function (e) {
            const type = passReg.getAttribute('type') === 'password' ? 'text' : 'password';
            passReg.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>