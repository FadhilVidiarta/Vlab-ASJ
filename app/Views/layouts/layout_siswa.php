<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc((string) ($title ?? 'Dashboard Siswa')) ?> - V-Lab ASJ</title>
    <link rel="shortcut icon" href="<?= base_url('logo-vlab.png'); ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css'); ?>">
</head>

<body style="background-color: #f8fafc;">

    <nav class="navbar navbar-expand-lg navbar-student sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary-custom fs-4" href="<?= base_url('siswa/dashboard') ?>">
                <i class="fa-solid fa-terminal me-2 text-primary-custom"></i> V-Lab ASJ
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navSiswa">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navSiswa">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_menu) && $active_menu == 'beranda') ? 'active' : '' ?>"
                            href="<?= base_url('siswa/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_menu) && $active_menu == 'materi') ? 'active' : '' ?>"
                            href="<?= base_url('siswa/materi') ?>">Materi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_menu) && $active_menu == 'ujian') ? 'active' : '' ?>"
                            href="<?= base_url('siswa/ujian') ?>">Tes Sumatif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active_menu) && $active_menu == 'praktikum') ? 'active' : '' ?>"
                            href="<?= base_url('siswa/praktikum') ?>">V-Lab Praktikum</a>
                    </li>
                </ul>

                <div class="dropdown">
                    <div class="profile-pill ps-3 pe-1 py-1 bg-white border rounded-pill d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <span class="fw-bold text-dark small ms-2">
                            <?= esc((string) ($user['nama_lengkap'] ?? session()->get('nama') ?? 'Siswa')) ?>
                        </span>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                            style="width: 35px; height: 35px; font-size: 0.9rem;">
                            <?= substr((string) ($user['nama_lengkap'] ?? session()->get('nama') ?? 'S'), 0, 1) ?>
                        </div>
                    </div>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 rounded-4 p-2"
                        style="min-width: 220px;">
                        <li>
                            <div class="px-3 py-2 border-bottom mb-2">
                                <span class="d-block fw-bold text-dark text-truncate">
                                    <?= esc((string) ($user['nama_lengkap'] ?? session()->get('nama') ?? 'Siswa')) ?>
                                </span>
                                <small class="text-muted">Siswa -
                                    <?= esc((string) ($user['kelas'] ?? 'Umum')) ?></small>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 mb-1" href="#" data-bs-toggle="modal"
                                data-bs-target="#detailProfileSiswaModal">
                                <i class="fa-solid fa-address-card me-2 text-info"></i> Detail Data Diri
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 mb-1" href="#" data-bs-toggle="modal"
                                data-bs-target="#editProfileSiswa">
                                <i class="fa-solid fa-user-pen me-2 text-primary"></i> Edit Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 text-danger" href="<?= base_url('auth/logout') ?>">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                <i class="fa-solid fa-check-circle fs-4 me-3"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <ul class="mb-0 ps-3">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <?php
    if (!isset($user)) {
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('idUser', session()->get('id'))->get()->getRowArray();
    }

    if (isset($user) && $user):
        ?>

        <div class="modal fade" id="detailProfileSiswaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow text-center rounded-4 p-3">
                    <div class="modal-header border-0 pb-0 justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-0 px-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm mb-3"
                            style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <?= substr((string) ($user['nama_lengkap'] ?? session()->get('nama') ?? 'S'), 0, 1) ?>
                        </div>
                        <h5 class="mb-1 fw-bold text-dark">
                            <?= esc((string) ($user['nama_lengkap'] ?? session()->get('nama') ?? 'Siswa')) ?>
                        </h5>
                        <span
                            class="badge bg-light text-primary border border-primary opacity-75 mb-4 px-3 py-2 rounded-pill">
                            Siswa - <?= esc((string) ($user['kelas'] ?? 'Umum')) ?>
                        </span>

                        <div class="text-start mt-2">
                            <div class="mb-2 px-4 py-3 bg-light rounded-3 d-flex align-items-center border">
                                <i class="fa-solid fa-user-tag text-secondary me-3 fs-5"></i>
                                <div>
                                    <small class="text-secondary d-block fw-bold mb-1"
                                        style="font-size: 0.7rem;">USERNAME</small>
                                    <span class="fw-medium text-dark"><?= esc((string) ($user['username'] ?? '')) ?></span>
                                </div>
                            </div>
                            <div class="mb-2 px-4 py-3 bg-light rounded-3 d-flex align-items-center border">
                                <i class="fa-solid fa-envelope text-secondary me-3 fs-5"></i>
                                <div style="overflow-wrap: anywhere;">
                                    <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.7rem;">ALAMAT
                                        EMAIL</small>
                                    <span class="fw-medium text-dark"><?= esc((string) ($user['email'] ?? '')) ?></span>
                                </div>
                            </div>
                            <div class="mb-4 px-4 py-3 bg-light rounded-3 d-flex align-items-center border">
                                <i class="fa-solid fa-graduation-cap text-secondary me-3 fs-5"></i>
                                <div>
                                    <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.7rem;">KELAS &
                                        NO. ABSEN</small>
                                    <span class="fw-medium text-dark">Kelas <?= esc((string) ($user['kelas'] ?? '-')) ?> /
                                        Absen <?= esc((string) ($user['no_absen'] ?? '-')) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 justify-content-center pt-0 pb-3">
                        <button type="button" class="btn btn-light rounded-pill px-5 border"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary rounded-pill px-5 shadow-sm" data-bs-dismiss="modal"
                            data-bs-toggle="modal" data-bs-target="#editProfileSiswa">
                            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProfileSiswa" tabindex="-1" aria-hidden="true">
            <form action="<?= base_url('siswa/dashboard/update_profile') ?>" method="post">
                <input type="hidden" name="nama_lengkap" value="<?= esc((string) ($user['nama_lengkap'] ?? '')) ?>">
                <input type="hidden" name="kelas" value="<?= esc((string) ($user['kelas'] ?? '')) ?>">
                <input type="hidden" name="no_absen" value="<?= esc((string) ($user['no_absen'] ?? '')) ?>">
                <input type="hidden" name="from_popup" value="1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Perbarui Data Diri</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= base_url('siswa/dashboard/update_profile') ?>" method="post">
                            <input type="hidden" name="from_popup" value="1">

                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                    <input type="text" class="form-control bg-light text-muted"
                                        value="<?= esc((string) ($user['nama_lengkap'] ?? '')) ?>" readonly
                                        style="cursor: not-allowed;">
                                    <div class="form-text text-danger" style="font-size: 0.7rem;">Nama Lengkap tidak dapat
                                        diubah secara mandiri.
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold text-secondary">Kelas</label>
                                        <input type="text" class="form-control bg-light text-muted"
                                            value="<?= esc((string) ($user['kelas'] ?? '-')) ?>" readonly
                                            style="cursor: not-allowed;">
                                        <div class="form-text text-danger" style="font-size: 0.7rem;">Kelas tidak dapat
                                            diubah.
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold text-secondary">No. Absen</label>
                                        <input type="text" class="form-control bg-light text-muted"
                                            value="<?= esc((string) ($user['no_absen'] ?? '-')) ?>" readonly
                                            style="cursor: not-allowed;">
                                        <div class="form-text text-danger" style="font-size: 0.7rem;">No Absen tidak dapat
                                            diubah.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i
                                                class="fa-regular fa-envelope text-secondary"></i></span>
                                        <input type="email" class="form-control bg-light text-muted"
                                            value="<?= esc((string) ($user['email'] ?? '')) ?>" readonly
                                            style="cursor: not-allowed;">
                                    </div>
                                    <div class="form-text text-danger" style="font-size: 0.7rem;">Email tidak dapat diubah.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fa-regular fa-user text-primary"></i></span>
                                        <input type="text" name="username" class="form-control border-start-0"
                                            value="<?= esc((string) ($user['username'] ?? '')) ?>" required minlength="4">
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">Anda dapat mengganti
                                        Username
                                        (minimal 4 karakter).
                                    </div>
                                </div>

                                <hr class="my-4 border-secondary opacity-25">
                                <h6 class="fw-bold mb-3 text-dark">Verifikasi Keamanan
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Password Lama <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 border-danger"><i
                                                class="fa-solid fa-lock text-danger"></i></span>
                                        <input type="password" name="password" id="passwordLama"
                                            class="form-control border-start-0 border-end-0 border-danger bg-opacity-10"
                                            placeholder="Masukkan password saat ini" required>
                                        <span class="input-group-text bg-white border-start-0 border-danger"
                                            style="cursor: pointer;" onclick="togglePassword('passwordLama', 'eyeLama')">
                                            <i class="fa-solid fa-eye text-secondary" id="eyeLama"></i>
                                        </span>
                                    </div>
                                    <div class="form-text text-danger fw-bold" style="font-size: 0.7rem;">Wajib diisi untuk
                                        memverifikasi perubahan!</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fa-solid fa-key text-secondary"></i></span>
                                        <input type="password" name="password_baru" id="passwordBaru"
                                            class="form-control border-start-0 border-end-0"
                                            placeholder="Kosongkan jika tidak ingin diubah">
                                        <span class="input-group-text bg-white border-start-0" style="cursor: pointer;"
                                            onclick="togglePassword('passwordBaru', 'eyeBaru')">
                                            <i class="fa-solid fa-eye text-secondary" id="eyeBaru"></i>
                                        </span>
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">Isi hanya jika Anda ingin
                                        mengganti password.</div>
                                </div>
                            </div>

                            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>

    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
                document.body.style.display = "none";
                window.location.reload();
            }
        });

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>