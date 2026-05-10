<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * Memberitahu VS Code bahwa variabel ini dikirim dari Controller
 * @var array $ujian
 * @var array $soal
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Daftar Soal</h4>
        <p class="text-secondary small mb-0">Ujian: <strong><?= esc((string) $ujian['judul_ujian']) ?></strong></p>
    </div>
    <div>
        <a href="<?= base_url('guru/ujian') ?>" class="btn btn-light border rounded-pill px-3 shadow-sm me-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <!-- REVISI: Ubah $ujian['id'] menjadi $ujian['idUjian'] -->
        <a href="<?= base_url('guru/ujian/tambah_soal/' . (string) $ujian['idUjian']) ?>"
            class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Tambah Soal
        </a>
    </div>
</div>

<div class="row">
    <?php if (empty($soal)): ?>
        <div class="col-12">
            <div class="alert alert-light border border-dashed text-center p-5 rounded-4 text-secondary">
                <i class="fa-solid fa-file-circle-question fa-3x mb-3 opacity-50"></i>
                <h5>Belum ada soal untuk ujian ini</h5>
                <p>Klik tombol "Tambah Soal" di atas untuk mulai membuat pertanyaan.</p>
            </div>
        </div>
    <?php else: ?>
        <?php $no = 1;
        foreach ($soal as $s): ?>
            <div class="col-12 mb-4">
                <div class="card-modern p-4 border-0 shadow-sm rounded-4 bg-white position-relative">

                    <div class="position-absolute top-0 end-0 m-3">
                        <!-- REVISI: Ubah $s['id'] -> idSoal, dan $ujian['id'] -> idUjian -->
                        <a href="<?= base_url('guru/ujian/edit_soal/' . (string) $s['idSoal'] . '/' . (string) $ujian['idUjian']) ?>"
                            class="btn btn-sm btn-warning text-dark shadow-sm me-1 rounded-pill px-3 fw-bold" title="Edit Soal">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                        </a>
                        <a href="<?= base_url('guru/ujian/hapus_soal/' . (string) $s['idSoal'] . '/' . (string) $ujian['idUjian']) ?>"
                            class="btn btn-sm btn-danger text-white shadow-sm rounded-pill px-3 fw-bold"
                            onclick="return confirm('Yakin ingin menghapus soal ini?');" title="Hapus Soal">
                            <i class="fa-solid fa-trash-can me-1"></i> Hapus
                        </a>
                    </div>

                    <div class="d-flex align-items-start mt-2">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold me-3 flex-shrink-0"
                            style="width: 35px; height: 35px;">
                            <?= $no++ ?>
                        </div>
                        <div class="flex-grow-1 pe-5">
                            <p class="fw-bold text-dark mb-3" style="text-align: justify;"><?= nl2br(esc((string) $s['pertanyaan'])) ?></p>

                            <?php if (!empty($s['file_gambar'])): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url('uploads/soal/' . (string) $s['file_gambar']) ?>" alt="Gambar Soal"
                                        class="img-fluid rounded border" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>

                            <div class="row text-secondary small">
                                <div
                                    class="col-md-6 mb-2 <?= $s['kunci_jawaban'] == 'A' ? 'text-success fw-bold bg-soft-success p-1 rounded' : 'p-1' ?>">
                                    A. <?= esc((string) $s['opsi_a']) ?>
                                    <?= $s['kunci_jawaban'] == 'A' ? '<i class="fa-solid fa-check ms-1"></i>' : '' ?></div>
                                <div
                                    class="col-md-6 mb-2 <?= $s['kunci_jawaban'] == 'B' ? 'text-success fw-bold bg-soft-success p-1 rounded' : 'p-1' ?>">
                                    B. <?= esc((string) $s['opsi_b']) ?>
                                    <?= $s['kunci_jawaban'] == 'B' ? '<i class="fa-solid fa-check ms-1"></i>' : '' ?></div>
                                <div
                                    class="col-md-6 mb-2 <?= $s['kunci_jawaban'] == 'C' ? 'text-success fw-bold bg-soft-success p-1 rounded' : 'p-1' ?>">
                                    C. <?= esc((string) $s['opsi_c']) ?>
                                    <?= $s['kunci_jawaban'] == 'C' ? '<i class="fa-solid fa-check ms-1"></i>' : '' ?></div>
                                <div
                                    class="col-md-6 mb-2 <?= $s['kunci_jawaban'] == 'D' ? 'text-success fw-bold bg-soft-success p-1 rounded' : 'p-1' ?>">
                                    D. <?= esc((string) $s['opsi_d']) ?>
                                    <?= $s['kunci_jawaban'] == 'D' ? '<i class="fa-solid fa-check ms-1"></i>' : '' ?></div>
                                <?php if (!empty($s['opsi_e'])): ?>
                                    <div
                                        class="col-md-6 mb-2 <?= $s['kunci_jawaban'] == 'E' ? 'text-success fw-bold bg-soft-success p-1 rounded' : 'p-1' ?>">
                                        E. <?= esc((string) $s['opsi_e']) ?>
                                        <?= $s['kunci_jawaban'] == 'E' ? '<i class="fa-solid fa-check ms-1"></i>' : '' ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>