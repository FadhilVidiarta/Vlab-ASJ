<?= $this->extend('layouts/layout_guru') ?>

<?= $this->section('content') ?>

<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div>
                <p class="text-secondary fw-bold mb-1">SISWA TERDAFTAR</p>
                <h2 class="fw-bold text-dark mb-0"><?= $total_siswa ?? 0 ?></h2>
            </div>
            <div class="icon-box bg-blue-soft"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div>
                <p class="text-secondary fw-bold mb-1">TOTAL MATERI</p>
                <h2 class="fw-bold text-dark mb-0"><?= $total_materi ?? 0 ?></h2>
            </div>
            <div class="icon-box bg-purple-soft"><i class="fa-solid fa-book-open"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div>
                <p class="text-secondary fw-bold mb-1">TES SUMATIF</p>
                <h2 class="fw-bold text-dark mb-0"><?= $total_tes ?? 0 ?></h2>
            </div>
            <div class="icon-box bg-orange-soft"><i class="fa-solid fa-clipboard-check"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div>
                <p class="text-secondary fw-bold mb-1">TOTAL MESIN (CT)</p>
                <h2 class="fw-bold text-dark mb-0"><?= $total_vms ?? 0 ?></h2>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fa-solid fa-server"></i></div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-dark">List CT Praktikum Siswa
    </h6>
</div>

<div class="card-modern">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>VMID & OS</th>
                    <th>PEMILIK (SISWA)</th>
                    <th class="text-center">STATUS</th>
                    <th>IP ADDRESS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($active_vms)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Mesin Aktif</h6>
                            <p class="small mb-0">Belum ada siswa yang membuat mesin praktikum saat ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($active_vms as $vm): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                    $raw_os_name = strtolower($vm['nama_os'] ?? 'linux');
                                    $image_file = 'linux.png';

                                    if (strpos($raw_os_name, 'ubuntu') !== false) {
                                        $image_file = 'ubuntu.png';
                                    } elseif (strpos($raw_os_name, 'debian') !== false) {
                                        $image_file = 'debian.png';
                                    } elseif (strpos($raw_os_name, 'centos') !== false) {
                                        $image_file = 'centos.png';
                                    }

                                    $image_url = base_url("images/{$image_file}");
                                    ?>

                                    <div class="avatar-circle bg-light border shadow-sm fs-5 text-dark overflow-hidden p-0 d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;"> <img src="<?= $image_url ?>"
                                            alt="<?= esc($raw_os_name) ?> Logo" class="img-fluid"
                                            style="max-width: 70%; max-height: 70%; object-fit: contain;"> </div>

                                    <div class="ms-3">
                                        <span class="fw-bold text-dark d-block">VM-<?= esc((string) $vm['vmid']) ?></span>
                                        <span
                                            class="text-secondary small fw-medium"><?= esc(ucfirst((string) $raw_os_name)) ?></span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="fw-bold text-dark d-block"><?= esc((string) $vm['nama_siswa']) ?></span>
                                <span class="text-secondary small">Kelas <?= esc((string) $vm['kelas']) ?? '-' ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                $status_vm = $vm['status'] ?? 'stopped';

                                if ($status_vm == 'running'):
                                    ?>
                                    <span class="badge bg-success px-3 py-1 rounded-pill fw-normal">Running</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-1 rounded-pill fw-normal">Stopped</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span
                                    class="text-dark fw-medium font-monospace bg-light px-2 py-1 border rounded"><?= $vm['ip_address'] ?? 'DHCP' ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>