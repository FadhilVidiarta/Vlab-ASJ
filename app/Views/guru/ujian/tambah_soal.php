<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * Memberitahu VS Code bahwa variabel ini dikirim dari Controller
 * @var array $ujian
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Tambah Soal Baru</h4>
        <p class="text-secondary small mb-0">Ujian:
            <?= esc((string) $ujian['judul_ujian']) ?>
        </p>
    </div>
    <!-- REVISI: Ubah $ujian['id'] menjadi $ujian['idUjian'] -->
    <a href="<?= base_url('guru/ujian/soal/' . (string) $ujian['idUjian']) ?>"
        class="btn btn-light border rounded-pill px-4 shadow-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> Batal
    </a>
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

    <!-- REVISI: Ubah $ujian['id'] menjadi $ujian['idUjian'] -->
    <form action="<?= base_url('guru/ujian/simpan_soal/' . (string) $ujian['idUjian']) ?>" method="post"
        enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label class="form-label fw-bold text-dark">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="pertanyaan" class="form-control" rows="4"
                placeholder="Ketik soal atau pertanyaan di sini..." required><?= old('pertanyaan') ?></textarea>
        </div>

        <div class="mb-4 bg-light p-3 rounded-3 border">
            <label class="form-label fw-bold text-dark small"><i
                    class="fa-solid fa-image text-primary me-2"></i>Lampirkan Gambar (Opsional)</label>
            <input type="file" name="file_gambar" class="form-control" accept="image/png, image/jpeg, image/jpg">
            <small class="text-muted d-block mt-1">Gunakan ini jika soal membutuhkan gambar topologi ASJ, screenshot
                terminal, dll. (Maks 2MB, JPG/PNG).</small>
        </div>

        <hr class="text-secondary opacity-25 my-4">
        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-ul text-warning me-2"></i> Pilihan Jawaban</h6>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white fw-bold">A</span>
                    <input type="text" name="opsi_a" class="form-control" placeholder="Pilihan A"
                        value="<?= old('opsi_a') ?>" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white fw-bold">B</span>
                    <input type="text" name="opsi_b" class="form-control" placeholder="Pilihan B"
                        value="<?= old('opsi_b') ?>" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white fw-bold">C</span>
                    <input type="text" name="opsi_c" class="form-control" placeholder="Pilihan C"
                        value="<?= old('opsi_c') ?>" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white fw-bold">D</span>
                    <input type="text" name="opsi_d" class="form-control" placeholder="Pilihan D"
                        value="<?= old('opsi_d') ?>" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-secondary text-white fw-bold">E</span>
                    <input type="text" name="opsi_e" class="form-control" placeholder="Pilihan E (Opsional)"
                        value="<?= old('opsi_e') ?>">
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-success mt-2">Pilih Kunci Jawaban Benar <span
                        class="text-danger">*</span></label>
                <select name="kunci_jawaban" class="form-select border-success" required>
                    <option value="" selected disabled>-- Pilih Kunci --</option>
                    <option value="A" <?= old('kunci_jawaban') == 'A' ? 'selected' : '' ?>>Jawaban A</option>
                    <option value="B" <?= old('kunci_jawaban') == 'B' ? 'selected' : '' ?>>Jawaban B</option>
                    <option value="C" <?= old('kunci_jawaban') == 'C' ? 'selected' : '' ?>>Jawaban C</option>
                    <option value="D" <?= old('kunci_jawaban') == 'D' ? 'selected' : '' ?>>Jawaban D</option>
                    <option value="E" <?= old('kunci_jawaban') == 'E' ? 'selected' : '' ?>>Jawaban E</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                <i class="fa-solid fa-save me-2"></i> Simpan Soal
            </button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>