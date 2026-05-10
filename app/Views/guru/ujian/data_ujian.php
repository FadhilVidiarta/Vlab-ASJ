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
        <h4 class="fw-bold mb-0">Kelola Tes Sumatif</h4>
        <p class="text-secondary small mb-0">Buat Tes, atur durasi, dan kelola bank soal untuk siswa.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
        data-bs-target="#modalTambahUjian">
        <i class="fa-solid fa-plus me-2"></i> Buat Tes Sumatif Baru
    </button>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= esc((string) session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="card-modern p-0">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="5%" class="text-center">NO</th>
                    <th>JUDUL UJIAN</th>
                    <th class="text-center">DURASI</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center" width="25%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ujian)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">Belum ada data Tes Sumatif.</td>
                    </tr>
                <?php else: ?>
                    <?php
                    // JALUR PINTAS: MENGHITUNG NOMOR OTOMATIS
                    $page = isset($_GET['page_ujian_group']) ? (int) $_GET['page_ujian_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($ujian as $u):
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $nomor++ ?></td>
                            <td>
                                <span class="fw-bold text-dark d-block"><?= esc((string) $u['judul_ujian']) ?></span>
                                <span class="text-secondary small">
                                    <?= !empty($u['deskripsi']) ? esc((string) $u['deskripsi']) : 'Tidak ada deskripsi' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 rounded-pill"><i
                                        class="fa-regular fa-clock me-1"></i> <?= esc((string) $u['durasi_menit']) ?>
                                    Menit</span>
                            </td>
                            <td class="text-center">
                                <?php if ($u['status'] == 'aktif'): ?>
                                    <span
                                        class="badge bg-soft-success text-success border border-success px-3 rounded-pill">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-soft-danger text-danger border border-danger px-3 rounded-pill">Tidak
                                        Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('guru/ujian/soal/' . $u['idUjian']) ?>"
                                    class="btn btn-sm btn-info text-white shadow-sm me-1 rounded-pill px-3" title="Kelola Soal">
                                    <i class="fa-solid fa-list-check me-1"></i> Kelola Soal
                                </a>

                                <button type="button" class="btn btn-sm btn-light border text-warning shadow-sm me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEditUjian<?= $u['idUjian'] ?>"
                                    title="Edit Pengaturan & Status">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <a href="<?= base_url('guru/ujian/hapus/' . $u['idUjian']) ?>"
                                    class="btn btn-sm btn-light border text-danger shadow-sm"
                                    onclick="return confirm('YAKIN HAPUS? Seluruh soal di dalam tes ini juga akan ikut terhapus permanen!');"
                                    title="Hapus Ujian">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Edit Ujian -->
                        <div class="modal fade" id="modalEditUjian<?= $u['idUjian'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4 text-start">
                                    <div class="modal-header bg-warning text-dark border-0">
                                        <h5 class="modal-title fw-bold">Edit Pengaturan Tes</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="<?= base_url('guru/ujian/update/' . $u['idUjian']) ?>" method="post">
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Judul Tes / Kuis</label>
                                                <input type="text" name="judul_ujian" class="form-control"
                                                    value="<?= esc((string) $u['judul_ujian']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Deskripsi
                                                    (Opsional)</label>
                                                <textarea name="deskripsi" class="form-control"
                                                    rows="2"><?= esc((string) $u['deskripsi']) ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Durasi
                                                        (Menit)</label>
                                                    <input type="number" name="durasi_menit" class="form-control"
                                                        value="<?= esc((string) $u['durasi_menit']) ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small fw-bold text-secondary">Status Ujian</label>
                                                    <select name="status" class="form-select">
                                                        <option value="draft" <?= $u['status'] == 'draft' ? 'selected' : '' ?>>
                                                            Draft (Sembunyikan)</option>
                                                        <option value="aktif" <?= $u['status'] == 'aktif' ? 'selected' : '' ?>>
                                                            Aktif (Tampilkan)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light rounded-pill px-4"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Update
                                                Tes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="p-3 border-top d-flex justify-content-end bg-white rounded-bottom">
            <?= isset($pager) ? $pager->links('ujian_group', 'default_full') : '' ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUjian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Buat Tes Sumatif Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="<?= base_url('guru/ujian/simpan') ?>" method="post">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Judul Tes / Kuis</label>
                        <input type="text" name="judul_ujian" class="form-control"
                            placeholder="Contoh: Tes Sumatif ASJ Bab 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="2"
                            placeholder="Tuliskan petunjuk pengerjaan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Durasi Pengerjaan (Menit)</label>
                        <input type="number" name="durasi_menit" class="form-control" value="60" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan & Lanjut</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>