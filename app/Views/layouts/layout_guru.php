<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - V-Lab ASJ</title>
    <link rel="shortcut icon" href="<?= base_url('logo-vlab.png'); ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css'); ?>">
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-terminal me-2 text-primary-custom"></i>V-Lab ASJ
        </div>

        <div class="sidebar-menu">
            <a href="<?= base_url('guru/dashboard') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
            <a href="<?= base_url('guru/data_siswa') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'data_siswa') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Data Siswa
            </a>
            <a href="<?= base_url('guru/materi') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'data_materi') ? 'active' : '' ?>">
                <i class="fa-solid fa-book"></i> Kelola Materi
            </a>
            <a href="<?= base_url('guru/ujian') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'ujian') ? 'active' : '' ?>">
                <i class="fa-solid fa-file-circle-question"></i> Kelola Tes Sumatif
            </a>
            <a href="<?= base_url('guru/nilai') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'nilai') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-bar"></i> Nilai Siswa
            </a>
            <a href="<?= base_url('guru/progres_praktikum') ?>"
                class="nav-link <?= (isset($active_menu) && $active_menu == 'progres_praktikum') ? 'active' : '' ?>">
                <i class="fa-solid fa-desktop"></i> Progres Praktikum
            </a>
        </div>

        <div class="sidebar-footer">
            <a href="<?= base_url('auth/logout') ?>" class="nav-link text-danger fw-bold">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-light me-3 border text-secondary shadow-sm" id="btnToggleSidebar"
                    style="position: relative; z-index: 9999;">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark"><?= $title ?? 'Dashboard' ?></h5>
            </div>

            <div class="dropdown">
                <div class="d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="dropdown">
                    <div class="text-end me-3 d-none d-md-block">
                        <small class="d-block text-secondary" style="font-size: 11px;">Login sebagai</small>
                        <span class="fw-bold text-dark small"><?= session()->get('nama') ?? 'Guru' ?></span>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                        style="width: 40px; height: 40px;">
                        <?= substr(session()->get('nama') ?? 'G', 0, 1) ?>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                    <li>
                        <a class="dropdown-item small mb-1" href="#" data-bs-toggle="modal"
                            data-bs-target="#detailProfileModal">
                            <i class="fa-solid fa-address-card me-2 text-info"></i> Detail Data Diri
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item small" href="#" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="fa-solid fa-user-gear me-2 text-primary"></i> Edit Profil
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                    <i class="fa-solid fa-check-circle fs-4 me-3"></i>
                    <div><?= session()->getFlashdata('success') ?></div>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <?php
    if (!isset($admin)) {
        $db = \Config\Database::connect();
        $admin = $db->table('users')->where('idUser', session()->get('id'))->get()->getRowArray();
    }
    if (isset($admin) && $admin):
        ?>

        <div class="modal fade" id="detailProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow text-center rounded-4 p-3">
                    <div class="modal-header border-0 pb-0 justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold shadow-sm mb-3"
                            style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <?= substr((string) ($admin['nama_lengkap'] ?? 'G'), 0, 1) ?>
                        </div>
                        <h5 class="mb-1 fw-bold text-dark"><?= esc((string) ($admin['nama_lengkap'] ?? '')) ?></h5>
                        <span
                            class="badge bg-light text-primary border border-primary opacity-75 mb-4 px-3 py-2 rounded-pill">
                            Guru / Admin V-Lab
                        </span>

                        <div class="text-start mt-2">
                            <div class="mb-2 px-4 py-3 bg-light rounded-3 d-flex align-items-center border">
                                <i class="fa-solid fa-user-tag text-secondary me-3 fs-5"></i>
                                <div>
                                    <small class="text-secondary d-block fw-bold mb-1"
                                        style="font-size: 0.7rem;">USERNAME</small>
                                    <span class="fw-medium text-dark"><?= esc((string) ($admin['username'] ?? '')) ?></span>
                                </div>
                            </div>

                            <div class="mb-4 px-4 py-3 bg-light rounded-3 d-flex align-items-center border">
                                <i class="fa-solid fa-envelope text-secondary me-3 fs-5"></i>
                                <div style="overflow-wrap: anywhere;">
                                    <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.7rem;">ALAMAT
                                        EMAIL</small>
                                    <span class="fw-medium text-dark"><?= esc((string) ($admin['email'] ?? '')) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pt-0 pb-3">
                        <button type="button" class="btn btn-light rounded-pill px-5 border"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary rounded-pill px-5 shadow-sm" data-bs-dismiss="modal"
                            data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Profil Saya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="<?= base_url('guru/dashboard/update_profile') ?>" method="post">
                        <div class="modal-body p-4">
                            <?php if (session()->getFlashdata('errors')): ?>
                                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                                    <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal
                                        Memperbarui:</div>
                                    <ul class="mb-0 ps-3 small">
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <li><?= esc((string) $error) ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-light border small text-secondary mb-3">
                                <i class="fa-solid fa-info-circle me-1"></i> Perbarui informasi akun Anda di sini.
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    value="<?= esc((string) ($admin['nama_lengkap'] ?? '')) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fa-regular fa-user text-primary"></i></span>
                                        <input type="text" name="username" class="form-control"
                                            value="<?= esc((string) ($admin['username'] ?? '')) ?>" required minlength="4">
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 0.7rem;">Dapat diubah (min 4
                                        karakter).</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fa-regular fa-envelope text-primary"></i></span>
                                        <input type="email" name="email" class="form-control"
                                            value="<?= esc((string) ($admin['email'] ?? '')) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 border-secondary opacity-25">
                            <h6 class="fw-bold mb-3 text-dark">Verifikasi Keamanan</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Password Lama <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 border-danger"><i
                                            class="fa-solid fa-lock text-danger"></i></span>
                                    <input type="password" name="password_lama" id="passwordLamaGuru"
                                        class="form-control border-start-0 border-end-0 border-danger bg-opacity-10"
                                        placeholder="Masukkan password saat ini" required>
                                    <span class="input-group-text bg-white border-start-0 border-danger"
                                        style="cursor: pointer;"
                                        onclick="togglePassword('passwordLamaGuru', 'eyeLamaGuru')">
                                        <i class="fa-solid fa-eye text-secondary" id="eyeLamaGuru"></i>
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
                                    <input type="password" name="password_baru" id="passwordBaruGuru"
                                        class="form-control border-start-0 border-end-0"
                                        placeholder="Kosongkan jika tidak ingin diubah">
                                    <span class="input-group-text bg-white border-start-0" style="cursor: pointer;"
                                        onclick="togglePassword('passwordBaruGuru', 'eyeBaruGuru')">
                                        <i class="fa-solid fa-eye text-secondary" id="eyeBaruGuru"></i>
                                    </span>
                                </div>
                                <div class="form-text text-muted" style="font-size: 0.7rem;">Isi hanya jika Anda ingin
                                    mengganti password.</div>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                            <button type="button" class="btn btn-light rounded-pill px-4 border"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if (session()->getFlashdata('errors')): ?>
            document.addEventListener("DOMContentLoaded", function () {
                var myModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                myModal.show();
            });
        <?php endif; ?>

        // === PERBAIKAN JS RESPONSIVE SIDEBAR ===
        document.addEventListener("DOMContentLoaded", function () {
            const btnToggle = document.getElementById('btnToggleSidebar');
            const body = document.body;
            const sidebar = document.getElementById('sidebar');

            // 1. Eksekusi klik tombol
            if (btnToggle) {
                btnToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Mencegah bentrok klik
                    body.classList.toggle('sidebar-toggled'); // NAMA INI SUDAH SINKRON DENGAN CSS
                });
            }

            // 2. Klik area di luar sidebar otomatis nutup (khusus tampilan HP)
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 992 && sidebar) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggleBtn = btnToggle ? btnToggle.contains(event.target) : false;

                    if (!isClickInsideSidebar && !isClickOnToggleBtn && body.classList.contains('sidebar-toggled')) {
                        body.classList.remove('sidebar-toggled');
                    }
                }
            });

            // 3. Menghindari reset sembarangan saat resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    body.classList.remove('sidebar-toggled');
                }
            });
        });

        // === FUNGSI LIHAT PASSWORD ===
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input && icon) {
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
        }
    </script>
</body>

</html>