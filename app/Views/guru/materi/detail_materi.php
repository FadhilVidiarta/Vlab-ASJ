<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * Memberitahu VS Code bahwa variabel ini dikirim dari Controller
 * @var array $materi
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Detail Materi</h4>
        <p class="text-secondary small mb-0">Review materi sebelum dibagikan ke siswa.</p>
    </div>
    <div>
        <a href="<?= base_url('guru/materi/edit/' . (string) $materi['idMateri']) ?>"
            class="btn btn-warning shadow-sm rounded-pill px-4 fw-bold me-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </a>
        <a href="<?= base_url('guru/materi') ?>" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card-modern p-4 h-100">
            <h5 class="fw-bold text-primary mb-3">
                <?= esc((string) $materi['judul_materi']) ?>
            </h5>

            <div class="mb-3">
                <span class="d-block small fw-bold text-secondary">Sub Materi:</span>
                <span class="text-dark fw-medium">
                    <?= esc((string) $materi['sub_materi']) ?>
                </span>
            </div>

            <div class="mb-3">
                <span class="d-block small fw-bold text-secondary">Status:</span>
                <?php if ($materi['status'] == 'aktif'): ?>
                    <span class="badge bg-soft-success text-success border border-success px-3 py-2 rounded-pill mt-1">Aktif
                        Ditampilkan</span>
                <?php else: ?>
                    <span class="badge bg-soft-danger text-danger border border-danger px-3 py-2 rounded-pill mt-1">Tidak
                        Aktif (Draft)</span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <span class="d-block small fw-bold text-secondary mb-1">Deskripsi:</span>
                <p class="text-muted small" style="text-align: justify;">
                    <?= !empty($materi['deskripsi']) ? nl2br(esc((string) $materi['deskripsi'])) : '<i>Tidak ada deskripsi.</i>' ?>
                </p>
            </div>

            <div class="mt-auto border-top pt-3">
                <span class="d-block small text-secondary">Terakhir diupdate: <br>
                    <?= !empty($materi['updated_at']) ? date('d M Y - H:i', strtotime((string) $materi['updated_at'])) . ' WIB' : '-' ?>
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card-modern p-0 overflow-hidden h-100 border border-light">
            <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold text-secondary"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Preview
                    Dokumen PDF</span>
                <a href="<?= base_url('uploads/materi/' . (string) $materi['file_pdf']) ?>" target="_blank"
                    class="btn btn-sm btn-primary rounded-pill px-3">
                    <i class="fa-solid fa-download me-1"></i> Download
                </a>
            </div>

            <div class="p-0 bg-secondary" style="height: 600px;">
                <?php if (!empty($materi['file_pdf']) && file_exists('uploads/materi/' . (string) $materi['file_pdf'])): ?>
                    <iframe src="<?= base_url('uploads/materi/' . (string) $materi['file_pdf']) ?>" width="100%"
                        height="100%" style="border: none;"></iframe>
                <?php else: ?>
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 text-white">
                        <i class="fa-solid fa-file-circle-xmark fa-3x mb-3 text-light opacity-50"></i>
                        <h5>File PDF tidak ditemukan atau telah dihapus.</h5>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>