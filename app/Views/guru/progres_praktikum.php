<?= $this->extend('layouts/layout_guru') ?>
<?php
/**
 * @var array $progres_praktikum
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Progres Praktikum V-Lab</h4>
        <p class="text-secondary small mb-0">Pantau progres pengerjaan modul server dan status mesin virtual siswa
            secara real-time.</p>
    </div>
</div>

<div class="card-modern p-0">
    <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center bg-white">
        <div class="mb-2 mb-md-0">
            <span class="text-secondary small fw-bold">Total: <?= count($progres_praktikum) ?> Aktivitas
                ditampilkan</span>
        </div>

        <div class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                    <i class="fa-solid fa-search text-secondary"></i>
                </span>
                <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill search-input"
                    placeholder="Cari Nama, Kelas, atau Modul..." style="width: 250px;">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern table-hover w-100">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">NO</th>
                    <th style="width: 15%;">NAMA SISWA</th>
                    <th style="width: 15%;">MODUL & MESIN</th>
                    <th style="width: 15%;" class="text-height">WAKTU PELAKSANAAN</th>
                    <th style="width: 15%;" class="text-center">PROGRES PRAKTIKUM</th>
                    <th style="width: 10%; text-align: center; white-space: nowrap;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($progres_praktikum)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-server fs-1 text-muted mb-3 d-block"></i>
                            Belum ada aktivitas praktikum V-Lab yang tercatat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $page = isset($_GET['page_progres_group']) ? (int) $_GET['page_progres_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($progres_praktikum as $log):
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary align-middle">
                                <?= $nomor++ ?>
                            </td>

                            <td class="align-middle">
                                <span class="fw-bold text-dark d-block">
                                    <?= esc((string) $log['nama']) ?>
                                </span>
                                <span class="text-secondary small">Kelas:
                                    <?= esc((string) ($log['kelas'] ?? '-')) ?>
                                </span>
                            </td>

                            <td class="align-middle">
                                <span class="fw-bold text-dark d-block">
                                    <?= esc((string) $log['judul_materi']) ?>
                                </span>
                                <span class="badge bg-light text-secondary border small mt-1">
                                    <?php if ($log['vmid'] != 0): ?>
                                        <i class="text-dark me-1">VMID:</i> <?= $log['vmid'] ?>
                                    <?php else: ?>
                                        <i class="fa-solid fa-trash-can text-danger me-1"></i> <span class="text-danger">OS Telah
                                            Dihapus</span>
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td class="text-height small align-middle">
                                <div class="d-flex flex-column align-items-start">
                                    <span class="text-secondary mb-1">
                                        <i class="text-black me-1">Waktu Mulai</i>
                                        <?= !empty($log['waktu_mulai']) ? date('d M Y, H:i', strtotime((string) $log['waktu_mulai'])) : '-' ?>
                                    </span>
                                    <span class="text-secondary">
                                        <i class="text-black me-1">Waktu Selesai</i>
                                        <?php if (!empty($log['waktu_selesai'])): ?>
                                            <?= date('d M Y, H:i', strtotime((string) $log['waktu_selesai'])) ?>
                                        <?php else: ?>
                                            <span class="fst-italic"> --Proses--</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </td>

                            <td class="text-center px-4 align-middle">
                                <?php
                                $skor = $log['progres'] ?? 0;
                                $bg_color = 'bg-danger';
                                if ($skor >= 50)
                                    $bg_color = 'bg-warning';
                                if ($skor == 100)
                                    $bg_color = 'bg-success';
                                ?>
                                <div class="d-flex justify-content-between align-items-center mb-1 small fw-bold">
                                    <span>Skor Pencapaian</span>
                                    <span class="<?= str_replace('bg-', 'text-', $bg_color) ?>">
                                        <?= $skor ?>%
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar <?= $bg_color ?>" role="progressbar" style="width: <?= $skor ?>%;"
                                        aria-valuenow="<?= $skor ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>

                            <td class="text-center align-middle">
                                <a href="<?= base_url('guru/hapus_log/' . $log['idVlabCT']) ?>"
                                    class="btn btn-sm btn-light border text-danger shadow-sm"
                                    onclick="return confirm('Anda yakin ingin menghapus sesi mesin siswa ini? Data ini akan menghapus riwayat mesin virtual mereka.');"
                                    title="Hapus Data">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="p-3 border-top d-flex justify-content-end bg-white rounded-bottom">
            <?= isset($pager) ? $pager->links('progres_group', 'default_full') : '' ?>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                let rowText = row.textContent.toLowerCase();
                if (rowText.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <?= $this->endSection() ?>