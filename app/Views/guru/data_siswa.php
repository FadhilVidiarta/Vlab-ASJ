<?= $this->extend('layouts/layout_guru') ?>
<?php
/**
 * Memberitahu VS Code tentang variabel yang dikirim dari Controller
 * @var array $users
 * @var string $keyword
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Data Siswa</h4>
        <p class="text-secondary small mb-0">Kelola data siswa dan akses akun.</p>
    </div>
</div>

<div class="card-modern p-0">

    <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center bg-white">
        <div class="mb-2 mb-md-0">
            <span class="text-secondary small fw-bold">Total: <?= count($users) ?> Data ditampilkan</span>
        </div>

        <form action="" method="get" class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                    <i class="fa-solid fa-search text-secondary"></i>
                </span>
                <input type="text" name="keyword" class="form-control bg-light border-0 rounded-end-pill search-input"
                    placeholder="Cari Nama / Kelas..." value="<?= $keyword ?>" style="width: 250px;">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th class="text-center" width="5%">NO</th>
                    <th>NAMA SISWA</th>
                    <th class="text-center">KELAS</th>
                    <th>USERNAME</th>
                    <th>EMAIL</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center" width="10%">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-secondary fw-bold mt-3">Data siswa tidak ditemukan.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $page = isset($_GET['page_siswa_group']) ? (int) $_GET['page_siswa_group'] : 1;
                    $nomor = ($page - 1) * 5 + 1;

                    foreach ($users as $user):
                        ?>

                        <?php
                        $isOnline = false;
                        if (!empty($user['last_active'])) {
                            date_default_timezone_set('Asia/Jakarta');

                            $waktuTerakhir = strtotime((string) $user['last_active']);
                            $waktuSekarang = time();
                            $selisihDetik = $waktuSekarang - $waktuTerakhir;

                            if ($selisihDetik <= 900 && $selisihDetik >= -60) {
                                $isOnline = true;
                            }
                        }
                        ?>

                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $nomor++ ?></td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-soft-primary">
                                        <?= substr($user['nama_lengkap'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block"><?= $user['nama_lengkap'] ?></span>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <?php if (!empty($user['kelas'])): ?>
                                    <span class="badge bg-soft-primary px-3 py-2 rounded-pill fw-bold border border-primary-subtle">
                                        <?= $user['kelas'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-soft-warning px-3 py-2 rounded-pill border border-warning-subtle">
                                        Belum Set
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="text-secondary fw-medium"><?= $user['username'] ?></span>
                            </td>

                            <td>
                                <span class="text-dark fw-medium"><?= $user['email'] ?></span>
                            </td>

                            <td class="text-center">
                                <?php if ($isOnline): ?>
                                    <span class="badge bg-soft-success text-success border border-success px-3 rounded-pill">
                                        <i class="fa-solid fa-circle small me-1"></i> Online
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-white text-secondary border px-3 rounded-pill">
                                        <i class="fa-solid fa-circle small me-1"></i> Offline
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="<?= base_url('guru/data_siswa/hapus_siswa/' . $user['idUser']) ?>"
                                    class="btn btn-sm btn-light border text-danger shadow-sm"
                                    onclick="return confirm('Hapus siswa ini?');" title="Hapus Data">
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
        <?= isset($pager) ? $pager->links('siswa_group', 'default_full') : '' ?>
    </div>
</div>

<?= $this->endSection() ?>