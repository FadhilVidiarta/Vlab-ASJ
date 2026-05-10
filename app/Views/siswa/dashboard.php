<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Memberitahu VS Code tentang variabel yang dikirim dari Controller
 * @var string $nama_depan
 * @var array $materiTerbaru
 * @var array $ujianAktif
 * @var array $user
 * @var bool $show_popup
 */
?>

<?= $this->section('content') ?>

<div class="student-banner mb-5 d-flex align-items-center justify-content-between shadow-lg"
    style="border-radius: 20px; padding: 40px; background: linear-gradient(135deg, #0d6efd, #0dcaf0);">
    <div style="z-index: 2; position: relative;">
        <h2 class="fw-bold mb-2 text-white">Halo, <?= esc((string) $nama_depan) ?>!</h2>
        <p class="mb-4 opacity-75 fs-5 text-white">Selamat Datang di Media Pembelajaran Administrasi Sistem Jaringan
        </p>
    </div>
</div>

<div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-fire text-warning me-2"></i> Materi Terbaru</h5>
            <p class="text-secondary small mb-0 mt-1">Modul yang baru saja ditambahkan oleh guru.</p>
        </div>
        <a href="<?= base_url('siswa/materi') ?>" class="text-primary small fw-bold text-decoration-none">Lihat Semua
            Materi<i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row">
        <?php if (empty($materiTerbaru)): ?>
            <div class="col-12">
                <div class="alert alert-light border border-dashed text-center p-4 rounded-4 text-secondary">
                    Belum ada materi baru yang diunggah.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($materiTerbaru as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card-modern h-100 p-4 border-0 shadow-sm rounded-4 bg-white hover-scale d-flex flex-column">
                        <div class="mb-3">
                            <span
                                class="badge bg-soft-primary text-primary rounded-pill small mb-2 px-3"><?= esc((string) $item['judul_materi']) ?></span>
                            <h6 class="fw-bold text-dark mb-1"
                                style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= esc((string) $item['sub_materi']) ?>
                            </h6>
                        </div>
                        <p class="text-secondary small flex-grow-1"
                            style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= !empty($item['deskripsi']) ? esc((string) $item['deskripsi']) : 'Pelajari modul ini untuk memahami dasar-dasar konfigurasi.' ?>
                        </p>
                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">
                                <?= date('d M Y', strtotime((string) $item['created_at'])) ?>
                            </span>
                            <a href="<?= base_url('siswa/materi/baca/' . $item['idMateri']) ?>"
                                class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">
                                Baca <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-clipboard-question text-primary me-2"></i> Tes Sumatif
                </h5>
                <p class="text-secondary small mb-0 mt-1">Uji pemahaman akhir Anda pada tes berikut.</p>
            </div>
            <a href="<?= base_url('siswa/ujian') ?>"
                class="text-decoration-none fw-bold small text-primary hover-opacity">
                Lihat Semua Tes <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <ul class="list-group list-group-flush">
                <?php if (empty($ujianAktif)): ?>
                    <li class="list-group-item text-center py-4 border-0">
                        <p class="text-muted small mb-0">Belum ada tes sumatif yang aktif saat ini.</p>
                    </li>
                <?php else: ?>
                    <?php
                    $limitUjian = array_slice($ujianAktif, 0, 4);
                    foreach ($limitUjian as $u):

                        $sudah_dikerjakan = false;
                        if (isset($u['sudah_dikerjakan']) && $u['sudah_dikerjakan'] == true) {
                            $sudah_dikerjakan = true;
                        } elseif (array_key_exists('nilai', $u) && $u['nilai'] !== null) {
                            $sudah_dikerjakan = true;
                        }
                        ?>

                        <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center hover-bg-light">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width: 45px; height: 45px;">
                                    <i class="fa-solid fa-file-pen"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1"><?= esc((string) $u['judul_ujian']) ?></h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill"
                                            style="font-size: 0.7rem;">
                                            <i class="fa-regular fa-clock me-1 text-secondary"></i>
                                            <?= esc((string) $u['durasi_menit']) ?> Menit
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if ($sudah_dikerjakan): ?>
                                <a href="<?= base_url('siswa/ujian') ?>"
                                    class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm flex-shrink-0">
                                    <i class="fa-solid fa-check-circle me-1"></i> Selesai
                                </a>
                            <?php else: ?>
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalPeringatanUjian<?= $u['idUjian'] ?>"
                                    class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm flex-shrink-0">
                                    Kerjakan <i class="fa-solid fa-arrow-right ms-1"></i>
                                </button>
                            <?php endif; ?>
                        </li>

                        <?php if (!$sudah_dikerjakan): ?>
                            <div class="modal fade" id="modalPeringatanUjian<?= $u['idUjian'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0 justify-content-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center p-4 pt-0">

                                            <div class="mb-3">
                                                <i class="fa-solid fa-triangle-exclamation text-warning"
                                                    style="font-size: 4rem;"></i>
                                            </div>

                                            <h5 class="fw-bold text-dark mb-3">Peringatan Keamanan Sistem</h5>
                                            <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                                                Tes Sumatif <strong><span
                                                        class="text-dark"><?= esc((string) $u['judul_ujian']) ?></span></strong> ini
                                                diawasi secara otomatis.
                                            </p>
                                            <p class="text-secondary mb-5 px-2" style="font-size: 0.95rem;">
                                                Sistem akan mencatat pelanggaran jika Anda <strong>berpindah tab, membuka aplikasi
                                                    lain, atau
                                                    meminimalkan browser</strong>. Pelanggaran berulang akan mengakibatkan ujian
                                                dihentikan paksa.
                                            </p>

                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <a href="<?= base_url('siswa/ujian/kerjakan/' . $u['idUjian']) ?>"
                                                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                                    Ya, Saya Siap!
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php if (isset($show_popup) && $show_popup): ?>
    <div class="modal fade" id="completeProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold">Lengkapi Profil Anda</h5>
                </div>
                <form action="<?= base_url('siswa/dashboard/update_profile') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="from_popup" value="1">
                    <div class="modal-body p-4">
                        <div class="alert alert-light border small mb-3 text-dark">
                            Halo <strong><?= esc((string) ($user['nama_lengkap'] ?? '')) ?></strong>, Tolong Lengkapi Data
                            Profil Anda.
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="<?= esc((string) ($user['nama_lengkap'] ?? '')) ?>"
                                placeholder="Masukkan Nama Lengkap Sesuai Absen" required>
                            <small class="text-muted" style="font-size: 11px;">*Anda dapat menyesuaikan nama jika nama Gmail
                                tidak
                                sesuai.</small>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">Kelas</label>
                                <select name="kelas" class="form-select" required>
                                    <option value="" selected disabled>Pilih...</option>
                                    <option value="X TKJ 1">X TKJ 1</option>
                                    <option value="X TKJ 2">X TKJ 2</option>
                                    <option value="X TKJ 3">X TKJ 3</option>
                                    <option value="XI TKJ 1">XI TKJ 1</option>
                                    <option value="XI TKJ 2">XI TKJ 2</option>
                                    <option value="XI TKJ 3">XI TKJ 3</option>
                                    <option value="XI TKJ 3">XII TKJ 1</option>
                                    <option value="XI TKJ 3">XII TKJ 2</option>
                                    <option value="XI TKJ 3">XII TKJ 3</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">No. Absen</label>
                                <input type="number" name="no_absen" class="form-control" placeholder="Contoh: 15" required
                                    min="1" max="40">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Buat Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fa-solid fa-lock text-secondary"></i></span>
                                <input type="password" name="password" class="form-control border-start-0"
                                    placeholder="Minimal 8 karakter" required minlength="8">
                            </div>
                            <small class="text-muted" style="font-size: 11px;">*Wajib diisi agar Anda bisa login manual
                                nanti.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Data &
                            Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById('completeProfileModal'));
            myModal.show();
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>