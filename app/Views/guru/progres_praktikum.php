<?= $this->extend('layouts/layout_guru') ?>

<?php
/**
 * Deklarasi variabel untuk menghindari error Intelephense
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
            <span class="text-secondary small fw-bold">Total: <?= count($progres_praktikum) ?> Aktivitas pengerjaan
                tercatat</span>
        </div>

        <div class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                    <i class="fa-solid fa-search text-secondary"></i>
                </span>
                <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill search-input"
                    placeholder="Cari Nama atau Kelas..." style="width: 250px;">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-modern w-100" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">NO</th>
                    <th style="width: 25%;">NAMA SISWA / KELAS</th>
                    <th style="width: 25%;" class="text-center">DAFTAR MODUL PRAKTIKUM</th>
                    <th style="width: 20%;" class="text-center">STATUS MESIN (PROXMOX)</th>
                    <th style="width: 25%; text-align: center;">AKSI UTAMA</th>
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
                    // PROSES GROUPING DATA BERDASARKAN ID USER DI LEVEL VIEW
                    $grouped_students = [];
                    foreach ($progres_praktikum as $log) {
                        $grouped_students[$log['idUser']]['nama'] = $log['nama'];
                        $grouped_students[$log['idUser']]['kelas'] = $log['kelas'];

                        // Ambil VMID aktif jika ada pengerjaan yang masih memiliki VMID aktif
                        if (!isset($grouped_students[$log['idUser']]['vmid']) || $grouped_students[$log['idUser']]['vmid'] == 0) {
                            $grouped_students[$log['idUser']]['vmid'] = $log['vmid'];
                            $grouped_students[$log['idUser']]['idVlabCT'] = $log['idVlabCT'];
                        }

                        $grouped_students[$log['idUser']]['aktivitas'][] = $log;
                    }

                    $page = isset($_GET['page_progres_group']) ? (int) $_GET['page_progres_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($grouped_students as $idUser => $student):
                        $total_modul = count($student['aktivitas']);
                        $has_active_os = ($student['vmid'] != 0);
                        ?>

                        <tr class="student-row">
                            <td class="text-center fw-bold text-secondary align-middle">
                                <?= $nomor++ ?>
                            </td>

                            <td class="align-middle">
                                <span class="fw-bold text-dark d-block fs-6">
                                    <?= esc((string) $student['nama']) ?>
                                </span>
                                <span class="badge bg-soft-primary px-2 py-1 rounded mt-1">
                                    Kelas: <?= esc((string) ($student['kelas'] ?? '-')) ?>
                                </span>
                            </td>

                            <td class="align-middle text-center">
                                <?php if ($total_modul > 1): ?>
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-student-<?= $idUser ?>"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-folder-open me-1"></i> Tampilkan <?= $total_modul ?> Modul <i
                                            class="fa-solid fa-chevron-down small ms-1"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted" type="button" disabled>
                                        <i class="fa-solid fa-file me-1"></i> 1 Modul Terbuka
                                    </button>
                                <?php endif; ?>
                            </td>

                            <td class="align-middle text-center">
                                <?php if ($has_active_os): ?>
                                    <span class="badge bg-light text-primary border border-primary px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-microchip me-1"></i> VMID: <?= $student['vmid'] ?> (Active)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-soft-danger text-danger px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-trash-can me-1"></i> OS Tidak Ada / Dihapus
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center align-middle">
                                <?php if ($has_active_os): ?>
                                    <a href="<?= base_url('guru/hapus_mesin_siswa/' . $student['idVlabCT']) ?>"
                                        class="btn btn-sm btn-danger shadow-sm fw-bold px-3 py-1 rounded-pill"
                                        onclick="return confirm('PENTING!\nAnda akan MENGHANCURKAN mesin server (VMID: <?= $student['vmid'] ?>) milik siswa ini dari Proxmox untuk menghemat resource VPS.\n\nYakin ingin melanjutkan?');">
                                        <i class="fa-solid fa-power-off me-1"></i> Hapus OS Siswa
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">Resource Bersih</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr class="bg-light">
                            <td colspan="5" class="p-0 border-0">
                                <div class="collapse <?= ($total_modul == 1) ? 'show' : '' ?>"
                                    id="collapse-student-<?= $idUser ?>">
                                    <div class="px-4 py-3 bg-light border-start border-primary border-4">
                                        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-graduation-cap me-1"></i>
                                            Rincian Capaian Modul Praktikum :</h6>

                                        <div class="row g-3">
                                            <?php foreach ($student['aktivitas'] as $log):
                                                $skor = $log['progres'] ?? 0;
                                                $bg_color = 'bg-danger';
                                                if ($skor >= 50)
                                                    $bg_color = 'bg-warning';
                                                if ($skor == 100)
                                                    $bg_color = 'bg-success';
                                                ?>

                                                <div class="col-md-12">
                                                    <div
                                                        class="p-3 bg-white border rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                                                        <div style="width: 35%;">
                                                            <span
                                                                class="fw-bold text-dark d-block mb-1"><?= esc((string) $log['judul_materi']) ?></span>
                                                            <small class="text-muted d-block">
                                                                <i class="fa-regular fa-calendar-plus me-1"></i>Mulai:
                                                                <?= !empty($log['waktu_mulai']) ? date('d M Y, H:i', strtotime((string) $log['waktu_mulai'])) : '-' ?>
                                                            </small>
                                                            <small class="text-muted d-block">
                                                                <i class="fa-regular fa-calendar-check me-1"></i>Selesai:
                                                                <?= !empty($log['waktu_selesai']) ? date('d M Y, H:i', strtotime((string) $log['waktu_selesai'])) : '<span class="text-warning fw-medium">--Proses--</span>' ?>
                                                            </small>
                                                        </div>

                                                        <div style="width: 45%;" class="px-3">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-1 small fw-bold">
                                                                <span class="text-secondary">Progress Skor</span>
                                                                <span
                                                                    class="<?= str_replace('bg-', 'text-', $bg_color) ?>"><?= $skor ?>%</span>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar <?= $bg_color ?>" role="progressbar"
                                                                    style="width: <?= $skor ?>%;"></div>
                                                            </div>
                                                        </div>

                                                        <div style="width: 15%;" class="text-end">
                                                            <a href="<?= base_url('guru/hapus_log/' . $log['idVlabCT']) ?>"
                                                                class="btn btn-sm btn-outline-danger border-0"
                                                                onclick="return confirm('Hapus riwayat database untuk modul ini?');"
                                                                title="Hapus Riwayat Log">
                                                                <i class="fa-solid fa-trash-can"></i> Hapus Log
                                                            </a>
                                                        </div>
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