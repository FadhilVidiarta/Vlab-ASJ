<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * @var array $progres_praktikum
 */
?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Progres Praktikum V-Lab</h4>
        <p class="text-secondary small mb-0">Pantau progres pengerjaan materi server dan container virtual siswa
            secara real-time.</p>
    </div>
</div>

<div class="card-modern p-0">
    <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center bg-white">
        <div class="mb-2 mb-md-0">
            <span class="text-secondary small fw-bold">Total: <?= count($progres_praktikum) ?> Aktivitas pengerjaan
                tercatat</span>
        </div>

        <div class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                    <i class="fa-solid fa-search text-secondary"></i>
                </span>
                <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill"
                    placeholder="Cari Nama atau Kelas..." style="width: 250px; box-shadow: none;">
            </div>
        </div>
    </div>

    <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
        <table class="table-modern w-100" style="border-collapse: collapse;">
            <thead class="bg-light">
                <tr>
                    <th style="width: 5%;" class="text-center text-secondary">NO</th>
                    <th style="width: 25%;" class="text-secondary">NAMA SISWA / KELAS</th>
                    <th style="width: 25%;" class="text-center text-secondary">MATERI PRAKTIKUM</th>
                    <th style="width: 20%;" class="text-center text-secondary">VMID (CT PROXMOX)</th>
                    <th style="width: 25%; text-align: center;" class="text-secondary">AKSI UTAMA</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($progres_praktikum)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-server fs-1 text-muted mb-3 d-block"></i>
                            Belum ada aktivitas praktikum V-Lab yang tercatat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $grouped_students = [];
                    foreach ($progres_praktikum as $log) {
                        $grouped_students[$log['idUser']]['nama'] = $log['nama'];
                        $grouped_students[$log['idUser']]['kelas'] = $log['kelas'];

                        if (!isset($grouped_students[$log['idUser']]['vmid']) || $grouped_students[$log['idUser']]['vmid'] == 0) {
                            $grouped_students[$log['idUser']]['vmid'] = $log['vmid'];
                            $grouped_students[$log['idUser']]['idVlabCT'] = $log['idVlabCT'];
                        }

                        $grouped_students[$log['idUser']]['aktivitas'][] = $log;
                    }

                    $page = isset($_GET['page_progres_group']) ? (int) $_GET['page_progres_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($grouped_students as $idUser => $student):
                        $total_materi = count($student['aktivitas']);
                        $has_active_os = ($student['vmid'] != 0);
                        ?>

                        <tr class="student-row border-bottom bg-white">
                            <td class="text-center fw-bold text-secondary align-middle">
                                <?= $nomor++ ?>
                            </td>

                            <td class="align-middle p-3">
                                <span class="fw-bold text-dark d-block mb-1">
                                    <?= esc((string) $student['nama']) ?>
                                </span>
                                <span class="badge bg-light border text-secondary px-2 py-1 rounded">
                                    Kelas: <?= esc((string) ($student['kelas'] ?? '-')) ?>
                                </span>
                            </td>

                            <td class="align-middle text-center p-3">
                                <?php if ($total_materi > 1): ?>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-student-<?= $idUser ?>"
                                        aria-expanded="false" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-folder-open me-1"></i> Detail <?= $total_materi ?> Materi
                                        <i class="fa-solid fa-chevron-down ms-1 icon-chevron"
                                            style="transition: transform 0.3s ease;"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted" type="button" disabled
                                        style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-file me-1"></i> 1 Materi Terbuka
                                    </button>
                                <?php endif; ?>
                            </td>

                            <td class="align-middle text-center p-3">
                                <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill"
                                    style="font-size: 0.85rem;">
                                    <?php if ($has_active_os): ?>
                                        <i class="text-dark fw-bold me-1">VMID:</i> <span
                                            class="text-dark fw-bold"><?= $student['vmid'] ?></span>
                                    <?php else: ?>
                                        <i class="fa-solid fa-trash-can me-1 opacity-50"></i> OS Dihapus
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td class="text-center align-middle p-3">
                                <?php if ($has_active_os): ?>
                                    <a href="<?= base_url('guru/hapus_mesin_siswa/' . $student['idVlabCT']) ?>"
                                        class="btn btn-sm btn-danger shadow-sm fw-bold px-3 py-1 rounded-pill"
                                        style="font-size: 0.8rem;"
                                        onclick="return confirm('PENTING!\nAnda akan Menghapus mesin server (VMID: <?= $student['vmid'] ?>) milik siswa ini dari Proxmox.\n\nYakin ingin melanjutkan?');">
                                        <i class="fa-solid fa-power-off me-1"></i> Hapus OS Siswa
                                    </a>
                                <?php else: ?>
                                    <span class="text-secondary small fst-italic"><i class="fa-solid fa-check me-1"></i> Resource
                                        Bersih</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr class="bg-light border-bottom">
                            <td colspan="5" class="p-0 border-0">
                                <div class="collapse" id="collapse-student-<?= $idUser ?>">
                                    <div class="p-3 bg-light border-start border-secondary border-4 ms-2 me-2 my-2">

                                        <div class="bg-white border rounded-3 shadow-sm overflow-hidden">
                                            <?php foreach ($student['aktivitas'] as $log):
                                                $skor = $log['progres'] ?? 0;
                                                $bg_color = 'bg-danger';
                                                if ($skor >= 50)
                                                    $bg_color = 'bg-warning';
                                                if ($skor == 100)
                                                    $bg_color = 'bg-success';
                                                ?>

                                                <div
                                                    class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-white m-0">
                                                    <div style="width: 35%;">
                                                        <span class="fw-bold text-dark d-block mb-1"
                                                            style="font-size: 0.9rem;"><?= esc((string) $log['judul_materi']) ?></span>
                                                        <small class="text-secondary d-block" style="font-size: 0.75rem;">
                                                            <i class="fa-regular fa-calendar-plus me-1"></i>Mulai:
                                                            <?= !empty($log['waktu_mulai']) ? date('d M Y, H:i', strtotime((string) $log['waktu_mulai'])) : '-' ?>
                                                        </small>
                                                        <small class="text-secondary d-block" style="font-size: 0.75rem;">
                                                            <i class="fa-regular fa-calendar-check me-1"></i>Selesai:
                                                            <?= !empty($log['waktu_selesai']) ? date('d M Y, H:i', strtotime((string) $log['waktu_selesai'])) : '<span class="text-warning fw-medium fst-italic">--Proses--</span>' ?>
                                                        </small>
                                                    </div>

                                                    <div style="width: 45%;" class="px-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-1 fw-bold"
                                                            style="font-size: 0.75rem;">
                                                            <span class="text-secondary">Progress Skor</span>
                                                            <span
                                                                class="<?= str_replace('bg-', 'text-', $bg_color) ?>"><?= $skor ?>%</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar <?= $bg_color ?>" role="progressbar"
                                                                style="width: <?= $skor ?>%;"></div>
                                                        </div>
                                                    </div>

                                                    <div style="width: 15%;" class="text-end">
                                                        <a href="<?= base_url('guru/hapus_log/' . $log['idVlabCT']) ?>"
                                                            class="btn btn-sm btn-outline-danger border-0 p-1 rounded"
                                                            style="font-size: 0.8rem;"
                                                            onclick="return confirm('Hapus riwayat untuk materi ini?');"
                                                            title="Hapus Riwayat Log">
                                                            <i class="fa-solid fa-trash-can"></i> Hapus Log
                                                        </a>
                                                    </div>
                                                </div>

                                            <?php endforeach; ?>
                                        </div>

                                    </div>
                                </div>
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
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('table tbody tr.student-row');

        rows.forEach(row => {
            let rowText = row.textContent.toLowerCase();
            let collapseRow = row.nextElementSibling;
            if (rowText.includes(filter)) {
                row.style.display = '';
                if (collapseRow) collapseRow.style.display = '';
            } else {
                row.style.display = 'none';
                if (collapseRow) collapseRow.style.display = 'none';
            }
        });
    });
</script>

<?= $this->endSection() ?>