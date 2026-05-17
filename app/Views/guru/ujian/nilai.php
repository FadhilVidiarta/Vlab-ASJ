<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * @var array $daftar_nilai
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Nilai Tes Sumatif</h4>
        <p class="text-secondary small mb-0">Pantau hasil evaluasi dan rekap nilai tes seluruh siswa.</p>
    </div>
</div>

<div class="card-modern p-0">
    <div class="table-responsive">
        <table class="table-modern w-100" style="border-collapse: collapse;">
            <thead class="bg-light">
                <tr>
                    <th style="width: 5%;" class="text-center text-secondary">NO</th>
                    <th style="width: 20%;" class="text-secondary">NAMA SISWA</th>
                    <th style="width: 20%;" class="text-secondary">JUDUL UJIAN</th>
                    <th style="width: 15%;" class="text-center text-secondary">BENAR / SALAH</th>
                    <th style="width: 10%;" class="text-center text-secondary">NILAI AKHIR</th>
                    <th style="width: 20%;" class="text-center text-secondary">SELESAI MENGERJAKAN</th>
                    <th style="width: 10%;" class="text-center text-secondary">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_nilai)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-clipboard-question fs-1 text-muted mb-3 d-block"></i>
                            Belum ada siswa yang mengerjakan Tes Sumatif.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $page = isset($_GET['page_nilai_group']) ? (int) $_GET['page_nilai_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($daftar_nilai as $n):
                        ?>
                        <tr class="border-bottom bg-white hover-bg-light">
                            <td class="text-center fw-bold text-secondary align-middle"><?= $nomor++ ?></td>

                            <td class="align-middle p-3">
                                <span class="fw-bold text-dark d-block"><?= esc((string) $n['nama_siswa']) ?></span>
                            </td>

                            <td class="align-middle p-3">
                                <span class="fw-bold text-dark d-block"><?= esc((string) $n['judul_materi']) ?></span>
                                <span class="badge bg-light border text-secondary px-2 py-1 rounded mt-1">Selesai
                                    Dikerjakan</span>
                            </td>

                            <td class="text-center align-middle p-3">
                                <span class="text-success fw-bold"><i class="fa-solid fa-check"></i>
                                    <?= $n['jml_benar'] ?></span>
                                <span class="mx-1 text-secondary">|</span>
                                <span class="text-danger fw-bold"><i class="fa-solid fa-xmark"></i>
                                    <?= $n['jml_salah'] ?></span>
                            </td>

                            <td class="text-center align-middle p-3">
                                <span class="fs-5 fw-bold <?= ($n['nilai_akhir'] >= 70) ? 'text-success' : 'text-danger' ?>">
                                    <?= $n['nilai_akhir'] ?>
                                </span>
                            </td>

                            <td class="text-center align-middle p-3 text-black small">
                                <?= isset($n['tanggal_mengerjakan']) ? date('d M Y, H:i', strtotime((string) $n['tanggal_mengerjakan'])) : 'Telah Dikerjakan' ?>
                            </td>

                            <td class="text-center align-middle p-3">
                                <?php if ($n['nilai_akhir'] >= 70): ?>
                                    <span
                                        class="badge bg-soft-success text-success border border-success px-3 py-1 rounded-pill">Lulus</span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-soft-danger text-danger border border-danger px-3 py-1 rounded-pill">Remedial</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="p-3 border-top d-flex justify-content-end bg-white rounded-bottom">
            <?= isset($pager) ? $pager->links('nilai_group', 'default_full') : '' ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>