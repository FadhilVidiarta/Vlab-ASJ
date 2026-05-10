<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * Memberitahu VS Code tentang variabel yang dikirim dari Controller
 * @var array $materi
 * @var string $keyword
 * @var int $nomor
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Materi</h4>
        <p class="text-secondary small mb-0">Manajemen data materi dan sub-materi pembelajaran.</p>
    </div>
    <div>
        <a href="<?= base_url('guru/materi/tambah') ?>" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-2"></i> Tambah Materi
        </a>
    </div>
</div>

<div class="card-modern p-0">
    <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-end align-items-center bg-white">
        <form action="" method="get" class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                    <i class="fa-solid fa-search text-secondary"></i>
                </span>
                <input type="text" name="keyword" class="form-control bg-light border-0 rounded-end-pill search-input"
                    placeholder="Cari Materi / Sub Materi..." value="<?= esc((string) ($keyword ?? '')) ?>"
                    style="width: 250px;">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th class="text-center" width="5%">NO</th>
                    <th>MATERI UTAMA</th>
                    <th>SUB MATERI</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center" width="15%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($materi)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-secondary fw-bold mt-3">Data materi tidak ditemukan.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($materi as $item): ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $nomor++ ?></td>

                            <td>
                                <span class="fw-bold text-dark"><?= esc((string) $item['judul_materi']) ?></span>
                            </td>

                            <td>
                                <span class="text-secondary fw-medium"><?= esc((string) $item['sub_materi']) ?></span>
                            </td>

                            <td class="text-center">
                                <?php if ($item['status'] == 'aktif'): ?>
                                    <span class="badge bg-soft-success text-success border border-success px-3 rounded-pill">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-soft-danger text-danger border border-danger px-3 rounded-pill">
                                        Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="<?= base_url('guru/materi/detail/' . (string) $item['idMateri']) ?>"
                                    class="btn btn-sm btn-light border text-info shadow-sm me-1" title="Detail Materi">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="<?= base_url('guru/materi/edit/' . (string) $item['idMateri']) ?>"
                                    class="btn btn-sm btn-light border text-warning shadow-sm me-1" title="Edit Materi">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?= base_url('guru/materi/hapus/' . (string) $item['idMateri']) ?>"
                                    class="btn btn-sm btn-light border text-danger shadow-sm"
                                    onclick="return confirm('Yakin ingin menghapus materi ini?');" title="Hapus Materi">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-end bg-white rounded-bottom">
        <?= $pager->links('materi_group', 'default_full') ?>
    </div>
</div>

<?= $this->endSection() ?>