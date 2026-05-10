<?= $this->extend('layouts/layout_siswa') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-layer-group text-primary me-2"></i> Daftar Materi
        </h4>
        <p class="text-secondary small mb-0 mt-1">Pilih dan pelajari modul materi di bawah ini.</p>
    </div>
</div>

<div class="mb-5">
    <?php if (empty($materiGrouped)): ?>
        <div class="alert alert-light border text-center p-5 rounded-4 shadow-sm">
            <i class="fa-solid fa-folder-open fa-3x text-secondary opacity-50 mb-3"></i>
            <h5 class="fw-bold text-secondary">Belum ada materi</h5>
            <p class="text-muted mb-0">Guru belum membagikan materi pembelajaran untuk saat ini.</p>
        </div>
    <?php else: ?>

        <div class="accordion" id="accordionMateri">
            <?php $i = 1; ?>
            <?php foreach ($materiGrouped as $judulUtama => $subMateriList): ?>

                <div class="accordion-item border-0 shadow-sm mb-3 rounded-4 overflow-hidden bg-white">
                    <h2 class="accordion-header" id="heading<?= $i ?>">
                        <button class="accordion-button collapsed fw-bold text-dark fs-6 p-4" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false"
                            aria-controls="collapse<?= $i ?>">
                            <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <?= esc((string) $judulUtama) ?>
                            <span class="badge bg-light text-secondary border ms-3 fs-8 rounded-pill">
                                <?= count($subMateriList) ?> Sub Materi
                            </span>
                        </button>
                    </h2>

                    <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>"
                        data-bs-parent="#accordionMateri">
                        <div class="accordion-body bg-light border-top p-4">
                            <div class="list-group list-group-flush rounded-3">

                                <?php foreach ($subMateriList as $sub): ?>
                                    <!-- REVISI: Mengubah $sub['id'] menjadi $sub['idMateri'] -->
                                    <a href="<?= base_url('siswa/materi/baca/' . (string) $sub['idMateri']) ?>"
                                        class="list-group-item list-group-item-action p-3 mb-2 rounded-3 shadow-sm border-0 hover-scale">
                                        <h6 class="fw-bold mb-1 text-primary">
                                            <i class="fa-regular fa-file-pdf text-danger me-2"></i>
                                            <?= esc((string) $sub['sub_materi']) ?>
                                        </h6>
                                        <p class="text-secondary small mb-0"
                                            style="display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= !empty($sub['deskripsi']) ? esc((string) $sub['deskripsi']) : 'Klik untuk mulai membaca modul ini.' ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <?php $i++; ?>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>