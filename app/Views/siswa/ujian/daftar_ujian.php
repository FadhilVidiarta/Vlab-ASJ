<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Deklarasi variabel dari Controller
 * @var array $ujianAktif
 * @var array $nilaiMap
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-clipboard-question text-primary me-2"></i> Daftar Tes Sumatif
        </h4>
        <p class="text-secondary small mb-0 mt-1">Selesaikan tes sumatif di bawah ini untuk mengevaluasi pemahaman Anda.
        </p>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
        <ul class="mb-0 ps-3">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc((string) $error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="table-responsive">
        <table class="table table-borderless align-middle mb-0" style="min-width: 800px;">
            <thead style="border-bottom: 2px solid #dee2e6;">
                <tr>
                    <th class="py-3 px-4 text-uppercase text-secondary fw-bold"
                        style="font-size: 0.8rem; letter-spacing: 0.5px;">Nama Tes Sumatif</th>
                    <th class="py-3 px-3 text-center text-uppercase text-secondary fw-bold"
                        style="font-size: 0.8rem; letter-spacing: 0.5px;" width="15%">Durasi</th>
                    <th class="py-3 px-3 text-center text-uppercase text-secondary fw-bold"
                        style="font-size: 0.8rem; letter-spacing: 0.5px;" width="20%">Status</th>
                    <th class="py-3 px-4 text-end text-uppercase text-secondary fw-bold"
                        style="font-size: 0.8rem; letter-spacing: 0.5px;" width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ujianAktif)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <h6 class="fw-bold text-secondary">Belum Ada Tes Aktif</h6>
                            <p class="text-muted small mb-0">Guru belum membagikan tes sumatif saat ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ujianAktif as $u): ?>
                        <tr style="border-bottom: 1px solid #f1f3f5;">
                            <td class="py-4 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-file-signature fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1"><?= esc((string) $u['judul_ujian']) ?></h6>
                                        <p class="text-secondary small mb-0 text-truncate" style="max-width: 300px;">
                                            <?= !empty($u['deskripsi']) ? esc((string) $u['deskripsi']) : 'Kerjakan tes ini dengan teliti.' ?>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-medium">
                                    <i class="fa-regular fa-clock me-1"></i> <?= esc((string) $u['durasi_menit']) ?> Menit
                                </span>
                            </td>

                            <td class="py-4 px-3 text-center">
                                <!-- REVISI: Mengubah $u['id'] menjadi $u['idUjian'] di percabangan -->
                                <?php if (isset($nilaiMap[$u['idUjian']])): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-check-circle me-1"></i> Selesai
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-hourglass-start me-1"></i> Belum Dikerjakan
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="py-4 px-4 text-end">
                                <!-- REVISI: Mengubah $u['id'] menjadi $u['idUjian'] di tombol aksi -->
                                <?php if (isset($nilaiMap[$u['idUjian']])): ?>
                                    <button type="button" class="btn btn-outline-success rounded-pill px-4 fw-bold btn-sm"
                                        onclick="tampilkanPopUpNilai('<?= esc(addslashes((string) $u['judul_ujian'])) ?>', <?= esc((string) $nilaiMap[$u['idUjian']]) ?>)">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Nilai
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold btn-sm shadow-sm"
                                        onclick="bukaKonfirmasi('<?= esc((string) $u['idUjian']) ?>', '<?= esc(addslashes((string) $u['judul_ujian'])) ?>')">
                                        Kerjakan Tes <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4 pt-0">

                <div class="mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size: 4rem;"></i>
                </div>

                <h5 class="fw-bold text-dark mb-3">Peringatan Keamanan Sistem</h5>
                <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                    Tes Sumatif <strong><span id="konfirmJudul" class="text-dark"></span></strong> ini diawasi secara
                    otomatis.
                </p>
                <p class="text-secondary mb-5 px-2" style="font-size: 0.95rem;">
                    Sistem akan mencatat pelanggaran jika Anda <strong>berpindah tab, membuka aplikasi lain, atau
                        meminimalkan browser</strong>. Pelanggaran berulang akan mengakibatkan ujian dihentikan paksa.
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border"
                        data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="btnLanjutUjian" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        Ya, Saya Siap!
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHasilUjian" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="mb-3 mt-2">
                    <i class="fa-regular fa-circle-check text-success" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Tes Selesai</h5>
                <p class="text-secondary small mb-4" id="teksJudulUjian">Memuat...</p>

                <div class="p-3 bg-light border rounded-3 mb-4">
                    <p class="text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">NILAI AKHIR
                        ANDA</p>
                    <div class="fw-bold text-primary" style="font-size: 3.5rem; line-height: 1;" id="teksNilaiUjian">
                        0
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm"
                    data-bs-dismiss="modal">
                    Tutup Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let modalHasil, modalKonfirm;

    document.addEventListener("DOMContentLoaded", function () {
        modalHasil = new bootstrap.Modal(document.getElementById('modalHasilUjian'));
        modalKonfirm = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));

        // Pengecekan jika baru saja selesai submit ujian
        <?php if (session()->getFlashdata('popup_nilai') !== null): ?>
            tampilkanPopUpNilai(
                '<?= esc(addslashes(session()->getFlashdata('popup_judul'))) ?>',
                <?= session()->getFlashdata('popup_nilai') ?>
            );
        <?php endif; ?>
    });

    // Fungsi memunculkan peringatan sistem
    function bukaKonfirmasi(id, judul) {
        document.getElementById('konfirmJudul').innerText = judul;
        // Atur link tombol "Mengerti & Lanjut"
        document.getElementById('btnLanjutUjian').href = '<?= base_url('siswa/ujian/kerjakan/') ?>' + id;
        modalKonfirm.show();
    }

    // Fungsi menampilkan hasil akhir yang bersih
    function tampilkanPopUpNilai(judul, nilai) {
        document.getElementById('teksJudulUjian').innerText = judul;
        document.getElementById('teksNilaiUjian').innerText = Math.round(nilai);
        if (modalHasil) modalHasil.show();
    }
</script>

<?= $this->endSection() ?>