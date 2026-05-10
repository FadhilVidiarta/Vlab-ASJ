<?= $this->extend('layouts/layout_siswa') ?>
<?php
/**
 * Diberitahukan kepada VS Code:
 * @var array $materi
 * @var array $history_praktikum
 */
?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h4 class="fw-bold text-dark"><i class="fa-solid fa-laptop-code text-primary me-2"></i> Sistem Praktikum V-Lab</h4>
    <p class="text-secondary small">Sistem menerapkan kebijakan <strong>1 Siswa 1 Sistem Operasi</strong> untuk
        efisiensi.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <h6 class="fw-bold text-secondary mb-3">SISTEM OPERASI (VM) AKTIF</h6>

                <?php if (!empty($mesin_aktif)): ?>
                    <div class="mb-3">
                        <img src="<?= base_url('images/' . strtolower($mesin_aktif['nama_os']) . '.png') ?>" alt="OS Logo"
                            width="70" class="mb-2">
                        <h4 class="fw-bold text-dark mb-0"><?= ucfirst($mesin_aktif['nama_os']) ?> Server</h4>
                        <span class="badge bg-light text-warning border mt-2">
                            <i class="fa-solid fa-server me-1"></i> VMID: <?= $mesin_aktif['vmid'] ?> Aktif
                        </span>
                    </div>
                    <p class="small text-muted mb-4">Anda harus menghapus OS ini jika ingin berganti Sistem Operasi.</p>
                    <a href="<?= base_url('siswa/praktikum/hapus_mesin') ?>"
                        class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm"
                        onclick="return confirm('PERHATIAN! Hancurkan OS ini dari Server Proxmox?\n\nTenang saja, NILAI PROGRESS Anda di tabel bawah TIDAK AKAN HILANG.');">Hapus
                        OS
                    </a>
                <?php else: ?>
                    <div class="mb-3 text-muted">
                        <i class="fa-solid fa-power-off fa-4x opacity-25 mb-3"></i>
                        <h5 class="fw-bold text-dark">Belum Ada OS</h5>
                    </div>
                    <p class="small text-muted mb-0">Silakan pilih Modul dan Sistem Operasi di samping untuk menyalakan
                        mesin.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <form action="#" method="GET" id="formPraktikum">
                    <h6 class="fw-bold text-dark mb-4">Mulai / Buka Modul Praktikum Baru</h6>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold">1. Pilih Modul Pembelajaran</label>
                        <select class="form-select bg-light" id="selectMateri" required>
                            <option value="" selected disabled>-- Pilih Modul --</option>
                            <?php foreach ($materi as $m): ?>
                                <option value="<?= (string) $m['idMateri'] ?>"><?= esc((string) $m['judul_materi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (empty($mesin_aktif)): ?>
                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold">2. Pilih OS Instalasi Baru</label>
                            <div class="d-flex gap-3">
                                <input type="radio" class="btn-check" name="os_pilihan" id="os_ubuntu" value="ubuntu"
                                    required>
                                <label class="btn border w-100 py-3 rounded-3 option-os text-muted fw-semibold"
                                    for="os_ubuntu">
                                    <img src="<?= base_url('images/ubuntu.png') ?>" width="25"
                                        class="mb-2 d-block mx-auto transition-logo">
                                    Ubuntu
                                </label>

                                <input type="radio" class="btn-check" name="os_pilihan" id="os_debian" value="debian">
                                <label class="btn border w-100 py-3 rounded-3 option-os text-muted fw-semibold"
                                    for="os_debian">
                                    <img src="<?= base_url('images/debian.png') ?>" width="25"
                                        class="mb-2 d-block mx-auto transition-logo">
                                    Debian
                                </label>

                                <input type="radio" class="btn-check" name="os_pilihan" id="os_centos" value="centos">
                                <label class="btn border w-100 py-3 rounded-3 option-os text-muted fw-semibold"
                                    for="os_centos">
                                    <img src="<?= base_url('images/centos.png') ?>" width="25"
                                        class="mb-2 d-block mx-auto transition-logo">
                                    CentOS
                                </label>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary rounded-3 mb-4 border-0">
                            <i class=" fa-solid fa-info-circle me-2"></i>
                            Modul ini akan dijalankan pada OS Anda yang sedang aktif
                            (<strong><?= ucfirst($mesin_aktif['nama_os']) ?></strong>).
                            <input type="hidden" name="os_pilihan" value="<?= $mesin_aktif['nama_os'] ?>">
                        </div>
                    <?php endif; ?>

                    <div class="text-end mt-4 pt-2 border-top">
                        <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"
                            onclick="jalankanPraktikum()">Mulai Praktikum
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="m-0 fw-bold text-primary"><i class="fa-solid fa-clock-rotate-left me-2"></i>Riwayat Praktikum</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary" style="font-size: 0.8rem;">
                    <tr>
                        <th class="ps-4 py-3">Materi Dikerjakan</th>
                        <th class="py-3 text-center">Waktu Pelaksanaan</th>
                        <th class="py-3 text-center">Progres (SKOR)</th>
                        <th class="text-center pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history_praktikum)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">Belum ada riwayat praktikum. Silakan
                                kerjakan modul.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history_praktikum as $h): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="text-dark mb-1"><?= esc((string) $h['judul_materi']) ?></div>
                                    <span class="badge bg-soft text-secondary border me-1">
                                        <img src="<?= base_url('images/' . strtolower($h['nama_os']) . '.png') ?>" width="12"
                                            class="text-dark me-1"> OS: <?= ucfirst((string) $h['nama_os']) ?>
                                    </span>
                                </td>

                                <td class="align-middle small py-3 text-start">
                                    <div class="text-dark mb-1 d-flex justify-content-center align-items-center">
                                        <span class="text-dark me-1">Waktu Mulai:</span>
                                        <span
                                            class="text-muted fst-italic"><?= date('d M Y, H:i', strtotime($h['waktu_mulai'])) ?></span>
                                    </div>
                                    <div class="text-dark d-flex justify-content-center align-items-center">
                                        <span class="me-1">Waktu Selesai:</span>
                                        <?php if (!empty($h['waktu_selesai'])): ?>
                                            <span
                                                class="text-secondary mb-1 fst-italic"><?= date('d M Y, H:i', strtotime($h['waktu_selesai'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic"> --Proses--</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="align-middle text-center py-3">
                                    <?php
                                    $bg_badge = 'bg-light text-dark';
                                    if ($h['progres'] >= 100)
                                        $bg_badge = 'bg-soft-success text-muted';
                                    elseif ($h['progres'] > 0)
                                        $bg_badge = 'bg-soft-warning text-muted';
                                    ?>
                                    <span class="badge <?= $bg_badge ?> border px-2 py-2 fs-6">
                                        <?= (int) ($h['progres'] ?? 0) ?>%
                                    </span>
                                </td>

                                <td class="text-center align-middle pe-4 py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if ($h['progres'] < 100): ?>
                                            <a href="<?= base_url('siswa/praktikum/lanjutkan_log/' . (string) $h['id_log']) ?>"
                                                class="btn btn-sm btn-primary shadow-sm fw-bold px-3">
                                                <i class="fa-solid fa-play me-1"></i> Lanjutkan
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('siswa/praktikum/hapus_riwayat/' . (string) $h['id_log']) ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus RIWAYAT NILAI modul ini secara permanen?');"
                                            title="Hapus Riwayat">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .option-os {
        transition: all 0.2s ease-in-out;
        border: 2px solid #e9ecef !important;
    }

    .option-os:hover {
        background-color: #f8f9fa;
        border-color: #cdd4dc !important;
    }

    .transition-logo {
        transition: transform 0.2s ease;
    }

    .btn-check:checked+.option-os {
        border-color: #0d6efd !important;
        background-color: #f0f7ff !important;
        color: #0d6efd !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .btn-check:checked+.option-os .transition-logo {
        transform: scale(1.15);
    }
</style>

<script>
    function jalankanPraktikum() {
        let materiId = document.getElementById('selectMateri').value;
        let osTerpilih = document.querySelector('input[name="os_pilihan"]:checked') || document.querySelector('input[name="os_pilihan"]');

        if (!materiId) return alert('Silakan pilih Modul Pembelajaran terlebih dahulu!');
        if (!osTerpilih) return alert('Silakan pilih Sistem Operasi!');

        window.location.href = "<?= base_url('siswa/praktikum/mulai/') ?>" + materiId + "/" + osTerpilih.value;
    }
</script>
<?= $this->endSection() ?>