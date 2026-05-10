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
        <h4 class="fw-bold mb-0">Edit Materi</h4>
        <p class="text-secondary small mb-0">Perbarui informasi dan file materi Anda.</p>
    </div>
    <div>
        <a href="<?= base_url('guru/materi') ?>" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card-modern p-4">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li>
                        <?= esc((string) $error) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('guru/materi/update/' . (string) $materi['idMateri']) ?>" method="post"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary small">Judul Materi Utama</label>
                <input type="text" name="judul_materi" class="form-control"
                    value="<?= old('judul_materi', (string) $materi['judul_materi']) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary small">Sub Materi</label>
                <input type="text" name="sub_materi" class="form-control"
                    value="<?= old('sub_materi', (string) $materi['sub_materi']) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary small">Deskripsi Singkat</label>
            <textarea name="deskripsi" class="form-control"
                rows="3"><?= old('deskripsi', (string) $materi['deskripsi']) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-8 mb-4">
                <label class="form-label fw-bold text-secondary small">Ganti File PDF (Opsional)</label>
                <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                <small class="text-muted"><i class="fa-solid fa-circle-info mt-2"></i> Biarkan kosong jika tidak ingin
                    mengganti file lama: <strong>
                        <?= esc((string) $materi['file_pdf']) ?>
                    </strong></small>
            </div>

            <div class="col-md-4 mb-4">
                <label class="form-label fw-bold text-secondary small">Status Materi</label>
                <select name="status" class="form-select">
                    <option value="aktif" <?= (old('status', (string) $materi['status']) == 'aktif') ? 'selected' : '' ?>>
                        Aktif
                        (Tampil di Siswa)</option>
                    <option value="tidak aktif" <?= (old('status', (string) $materi['status']) == 'tidak aktif') ? 'selected' : '' ?>>Tidak Aktif (Draft)</option>
                </select>
            </div>
        </div>

        <hr class="text-secondary opacity-25">

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-save me-2"></i> Update Materi
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>