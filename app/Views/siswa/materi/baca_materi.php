<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Memberitahu VS Code bahwa variabel ini dikirim dari Controller
 * @var array $materi
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-primary">
            <!-- Penambahan esc() dan (string) -->
            <?= esc((string) $materi['judul_materi']) ?>
        </h4>
        <p class="text-secondary small mb-0 fw-bold">
            <!-- Penambahan esc() dan (string) -->
            <?= esc((string) $materi['sub_materi']) ?>
        </p>
    </div>
    <div>
        <a href="<?= base_url('siswa/materi/') ?>"
            class="btn btn-light border rounded-pill px-4 shadow-sm fw-bold text-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card-modern p-0 overflow-hidden shadow-lg border-0 rounded-4">
    <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
        <span class="fw-bold text-secondary">
            <i class="fa-solid fa-book-open text-primary me-2"></i> Modul Pembelajaran
        </span>
        <!-- Penambahan (string) pada file_pdf -->
        <a href="<?= base_url('uploads/materi/' . (string) $materi['file_pdf']) ?>" target="_blank"
            class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
            <i class="fa-solid fa-download me-1"></i> Unduh PDF
        </a>
    </div>

    <div class="p-0 bg-secondary" style="height: 70vh; min-height: 600px;">
        <?php if (!empty($materi['file_pdf']) && file_exists('uploads/materi/' . (string) $materi['file_pdf'])): ?>
            <iframe src="<?= base_url('uploads/materi/' . (string) $materi['file_pdf']) ?>#toolbar=0" width="100%"
                height="100%" style="border: none;"></iframe>
        <?php else: ?>
            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-white">
                <i class="fa-solid fa-file-circle-xmark fa-4x mb-3 text-light opacity-50"></i>
                <h5 class="fw-bold">Yah, file PDF tidak ditemukan!</h5>
                <p class="text-light opacity-75">Silakan hubungi guru Anda terkait masalah ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4 p-4 bg-white rounded-4 shadow-sm border border-light">
    <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info text-info me-2"></i> Deskripsi Materi:</h6>
    <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify;">
        <!-- Penambahan esc() dan (string) pada deskripsi -->
        <?= !empty($materi['deskripsi']) ? nl2br(esc((string) $materi['deskripsi'])) : '<i>Tidak ada deskripsi tambahan.</i>' ?>
    </p>
</div>

<?= $this->endSection() ?>