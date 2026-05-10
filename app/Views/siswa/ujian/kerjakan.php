<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Deklarasi variabel dari Controller untuk menghindari peringatan Intelephense
 * @var array $ujian
 * @var array $soal
 * @var int $sisa_waktu
 */
?>

<?= $this->section('content') ?>

<style>
    header,
    nav,
    footer,
    .navbar,
    .sidebar,
    #sidebar {
        display: none !important;
    }

    main,
    .main-content,
    #main {
        margin-left: 0 !important;
        padding-top: 0 !important;
        width: 100% !important;
        background-color: #f3f6f9 !important;
        min-height: 100vh;
    }

    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: #f3f6f9 !important;
    }

    .option-box {
        display: flex;
        align-items: flex-start;
        padding: 14px 20px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }

    .option-box:hover {
        background-color: #f8f9fa;
        border-color: #babbbc;
    }

    .option-box input[type="radio"] {
        margin-top: 4px;
        margin-right: 15px;
        transform: scale(1.2);
        cursor: pointer;
    }

    .option-box:has(input[type="radio"]:checked) {
        border-color: #0d6efd;
        background-color: #f0f7ff;
        box-shadow: 0 0 0 1px #0d6efd;
    }

    .card-error {
        border-color: #dc3545 !important;
        border-width: 2px !important;
        background-color: #fff5f5 !important;
        transition: all 0.3s ease;
    }
</style>

<div class="container pt-4 pb-5" style="max-width: 850px;">

    <div class="bg-white shadow-sm py-3 px-4 mb-4 rounded-4 border d-flex justify-content-between align-items-center sticky-top"
        style="z-index: 1020; top: 15px;" id="headerUjian">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded px-3 py-2 me-3 fs-4">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">
                    <?= esc((string) $ujian['judul_ujian']) ?>
                </h5>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill fw-medium"
                    style="font-size: 0.7rem;">
                    <i class="fa-solid fa-shield-halved me-1"></i> Pengawasan Sistem Aktif
                </span>
            </div>
        </div>
        <div class="text-end">
            <div class="text-secondary small fw-bold mb-1 text-uppercase"
                style="font-size: 0.7rem; letter-spacing: 1px;">Sisa Waktu</div>
            <div class="fs-5 fw-bold text-dark bg-light border rounded-pill px-4 py-1" id="timerDisplay">
                <i class="fa-regular fa-clock me-1 text-secondary timer-icon"></i> Memuat...
            </div>
        </div>
    </div>

    <!-- REVISI: Mengubah $ujian['id'] menjadi $ujian['idUjian'] -->
    <form id="formUjian" action="<?= base_url('siswa/ujian/submit/' . (string) $ujian['idUjian']) ?>" method="post">
        <?php $no = 1;
        foreach ($soal as $s): ?>
            <div class="card border border-light-subtle shadow-sm rounded-4 mb-4 question-card">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex justify-content-between mb-4">
                        <div class="d-flex w-100">
                            <div class="fw-bold me-3 text-dark flex-shrink-0" style="min-width: 25px; font-size: 1.05rem;">
                                <?= $no++ ?>.
                            </div>
                            <div class="text-dark w-100" style="font-size: 1.05rem; line-height: 1.6; text-align: justify;">
                                <?= nl2br(esc((string) $s['pertanyaan'])) ?>
                            </div>
                        </div>
                        <div class="text-danger small fw-bold d-none warning-text flex-shrink-0 ms-3">
                            <i class="fa-solid fa-circle-exclamation"></i> Belum dijawab!
                        </div>
                    </div>

                    <?php if (!empty($s['file_gambar'])): ?>
                        <div class="mb-4 text-center">
                            <img src="<?= base_url('uploads/soal/' . (string) $s['file_gambar']) ?>" alt="Gambar Soal"
                                class="img-fluid border rounded-3 shadow-sm" style="max-height: 300px; pointer-events: none;">
                        </div>
                    <?php endif; ?>

                    <!-- REVISI: Mengubah $s['id'] menjadi $s['idSoal'] pada semua opsi jawaban -->
                    <div class="ps-md-5">
                        <label class="option-box text-dark">
                            <input type="radio" name="jawaban[<?= (string) $s['idSoal'] ?>]" value="A">
                            <span><strong>A.</strong> <?= esc((string) $s['opsi_a']) ?></span>
                        </label>
                        <label class="option-box text-dark">
                            <input type="radio" name="jawaban[<?= (string) $s['idSoal'] ?>]" value="B">
                            <span><strong>B.</strong> <?= esc((string) $s['opsi_b']) ?></span>
                        </label>
                        <label class="option-box text-dark">
                            <input type="radio" name="jawaban[<?= (string) $s['idSoal'] ?>]" value="C">
                            <span><strong>C.</strong> <?= esc((string) $s['opsi_c']) ?></span>
                        </label>
                        <label class="option-box text-dark">
                            <input type="radio" name="jawaban[<?= (string) $s['idSoal'] ?>]" value="D">
                            <span><strong>D.</strong> <?= esc((string) $s['opsi_d']) ?></span>
                        </label>
                        <?php if (!empty($s['opsi_e'])): ?>
                            <label class="option-box text-dark">
                                <input type="radio" name="jawaban[<?= (string) $s['idSoal'] ?>]" value="E">
                                <span><strong>E.</strong> <?= esc((string) $s['opsi_e']) ?></span>
                            </label>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-end mt-4 mb-5">
            <button type="submit"
                class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center hover-scale">
                <i class="fa-solid fa-paper-plane me-2"></i> Kumpulkan Jawaban
            </button>
        </div>
    </form>
</div>

<script>
    const formUjian = document.getElementById('formUjian');
    let isSubmitting = false;
    let isAlerting = false;
    // REVISI: Mengubah $ujian['id'] menjadi $ujian['idUjian']
    const violationKey = 'pelanggaran_ujian_<?= $ujian['idUjian'] ?>';

    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
            let card = this.closest('.question-card');
            card.classList.remove('card-error');
            let warning = card.querySelector('.warning-text');
            if (warning) warning.classList.add('d-none');
        });
    });

    let sisaWaktu = <?= $sisa_waktu ?>;
    const timerDisplay = document.getElementById('timerDisplay');

    function updateTimer() {
        if (sisaWaktu <= 0) {
            timerDisplay.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> WAKTU HABIS';
            timerDisplay.classList.replace('text-dark', 'text-white');
            timerDisplay.classList.replace('bg-light', 'bg-danger');
            clearInterval(timerInterval);

            isSubmitting = true;
            lepaskanSensor();
            localStorage.removeItem(violationKey);

            alert("Waktu pengerjaan habis! Jawaban Anda dikumpulkan otomatis.");
            formUjian.submit();
            return;
        }

        let jam = Math.floor(sisaWaktu / 3600);
        let menit = Math.floor((sisaWaktu % 3600) / 60);
        let detik = sisaWaktu % 60;

        let txtJam = jam > 0 ? (jam < 10 ? "0" + jam + ":" : jam + ":") : "";
        let txtMenit = menit < 10 ? "0" + menit : menit;
        let txtDetik = detik < 10 ? "0" + detik : detik;

        timerDisplay.innerHTML = `<i class="fa-regular fa-clock me-1 text-secondary timer-icon"></i> ${txtJam}${txtMenit} : ${txtDetik}`;

        if (sisaWaktu <= 60) {
            timerDisplay.classList.replace('bg-light', 'bg-danger');
            timerDisplay.classList.replace('border', 'border-danger');
            timerDisplay.classList.replace('text-dark', 'text-white');
            document.querySelector('.timer-icon').classList.replace('text-secondary', 'text-white');
        }
        sisaWaktu--;
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    let pelanggaran = parseInt(localStorage.getItem(violationKey)) || 0;
    const maxPelanggaran = 3;

    function catatPelanggaran(alasan) {
        if (isSubmitting || isAlerting) return;
        isAlerting = true;

        pelanggaran++;
        localStorage.setItem(violationKey, pelanggaran);

        if (pelanggaran >= maxPelanggaran) {
            isSubmitting = true;
            lepaskanSensor();
            localStorage.removeItem(violationKey);
            alert(`PELANGGARAN FATAL!\nSistem mendeteksi Anda: ${alasan}\n\nBatas maksimal pelanggaran tercapai. Ujian dihentikan paksa.`);
            formUjian.submit();
        } else {
            alert(`PERINGATAN ${pelanggaran}/${maxPelanggaran}!\n\nSistem mendeteksi Anda: ${alasan}\n\nJika mencapai 3 kali, ujian akan dikumpulkan otomatis dengan nilai seadanya.`);
            setTimeout(() => { isAlerting = false; }, 500);
        }
    }

    function handleWindowBlur() {
        if (!isSubmitting) catatPelanggaran("Mengeklik / beralih ke aplikasi atau tab lain");
    }

    function handleVisibilityChange() {
        if (document.hidden && !isSubmitting) catatPelanggaran("Menyembunyikan jendela ujian");
    }

    history.pushState(null, null, location.href);
    function handlePopState() {
        history.pushState(null, null, location.href);
        catatPelanggaran("Menekan tombol kembali (Back) pada browser");
    }

    function handleBeforeUnload(e) {
        if (!isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Ujian sedang berlangsung! Yakin ingin keluar?';
        }
    }

    window.addEventListener("blur", handleWindowBlur);
    document.addEventListener("visibilitychange", handleVisibilityChange);
    window.addEventListener("popstate", handlePopState);
    window.addEventListener("beforeunload", handleBeforeUnload);

    function lepaskanSensor() {
        window.removeEventListener("blur", handleWindowBlur);
        document.removeEventListener("visibilitychange", handleVisibilityChange);
        window.removeEventListener("popstate", handlePopState);
        window.removeEventListener("beforeunload", handleBeforeUnload);
    }

    formUjian.addEventListener('submit', function (e) {
        if (!isSubmitting) {
            e.preventDefault();

            let unanswered = 0;
            let firstUnanswered = null;

            document.querySelectorAll('.question-card').forEach(card => {
                let checked = card.querySelector('input[type="radio"]:checked');
                if (!checked) {
                    unanswered++;
                    card.classList.add('card-error');
                    let warning = card.querySelector('.warning-text');
                    if (warning) warning.classList.remove('d-none');

                    if (!firstUnanswered) firstUnanswered = card;
                }
            });

            if (unanswered > 0) {
                alert(`Peringatan: Terdapat ${unanswered} soal yang belum dijawab!\n\nSilakan periksa kotak yang berwarna merah dan lengkapi jawaban Anda sebelum mengumpulkan.`);
                if (firstUnanswered) {
                    const headerOffset = document.getElementById('headerUjian').offsetHeight + 30;
                    const elementPosition = firstUnanswered.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    window.scrollTo({ top: offsetPosition, behavior: "smooth" });
                }
                return false;
            }

            let konfirmasi = confirm('Anda telah menjawab semua soal. Yakin ingin mengumpulkan sekarang?');
            if (konfirmasi) {
                isSubmitting = true;
                lepaskanSensor();
                localStorage.removeItem(violationKey);
                formUjian.submit();
            }
        }
    });
</script>

<?= $this->endSection() ?>