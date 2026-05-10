<?= $this->include('layouts/header'); ?>

<div class="bg-section-white position-relative d-flex flex-column pt-5" id="hero" style="min-height: 100vh;">

    <div class="position-absolute top-0 start-0 w-100 h-100 pe-none overflow-hidden">
        <i class="fa-solid fa-network-wired bg-deco-icon fa-10x"
            style="top: 10%; right: -5%; transform: rotate(-15deg);"></i>
        <i class="fa-solid fa-server bg-deco-icon fa-8x" style="bottom: 20%; left: -5%;"></i>
    </div>

    <section class="container flex-grow-1 d-flex align-items-center position-relative z-2 pt-5">
        <div class="row w-100 align-items-center flex-column-reverse flex-md-row mx-0">
            <div class="col-md-6 text-center text-md-start mt-5 mt-md-0">
                <h1 class="display-4 mb-4 lh-sm">
                    Platform Media Pembelajaran <br>
                    <span class="text-gradient">Administrasi Sistem Jaringan</span>
                </h1>

                <p class="lead text-secondary mb-5 pe-md-5">
                    Platform belajar interaktif Administrasi Sistem Jaringan. Kuasai konfigurasi Server OS Debian Ubunut
                    CentOS, serta konfigurasi dasar linux.
                </p>

                <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                    <a href="<?= base_url('register') ?>" class="btn btn-blue shadow-lg">
                        Mulai Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/server-maintenance-4439233-3728460.png"
                    alt="Server Illustration" class="img-fluid animate-float" style="max-height: 480px;">
            </div>
        </div>
    </section>

    <div class="wave-container mt-auto">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none" class="fill-grey">
            <path fill-opacity="1"
                d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,149.3C672,139,768,149,864,170.7C960,192,1056,224,1152,224C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>
</div>

<div class="bg-section-grey position-relative" id="materi">
    <section class="py-5 position-relative z-2">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-6 mb-3">Materi Unggulan</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-hover">
                        <div class="icon-box bg-blue-soft">
                            <i class="fa-brands fa-linux"></i>
                        </div>
                        <h4 class="mb-3">Sistem Operasi</h4>
                        <p class="text-secondary small m-0">
                            Instalasi Debian Server, Manajemen User & Group, Repository Lokal, dan perintah dasar Shell
                            (Bash).
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-hover">
                        <div class="icon-box bg-purple-soft">
                            <i class="fa-solid fa-network-wired"></i>
                        </div>
                        <h4 class="mb-3">Layanan Jaringan</h4>
                        <p class="text-secondary small m-0">
                            Konfigurasi DHCP, DNS Server (Bind9), Web Server (Apache), dan Database Server.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-hover">
                        <div class="icon-box bg-orange-soft">
                            <i class="fa-solid fa-file-pen"></i>
                        </div>
                        <h4 class="mb-3">Tes Sumatif</h4>
                        <p class="text-secondary small m-0">
                            Ukur pemahamanmu melalui evaluasi akhir dengan sistem dan
                            studi kasus.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wave-container">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none" class="fill-white">
            <path fill-opacity="1"
                d="M0,96L48,122.7C96,149,192,203,288,202.7C384,203,480,149,576,138.7C672,128,768,160,864,165.3C960,171,1056,149,1152,133.3C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>
</div>

<div class="bg-section-white position-relative" id="tentang">
    <section class="container py-5 position-relative z-2">
        <div class="row align-items-center py-5">
            <div class="col-md-6 mb-5 mb-md-0 text-center">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/web-hosting-4439232-3728459.png"
                    alt="Why Us" class="img-fluid animate-float" style="max-width: 90%;">
            </div>
            <div class="col-md-6">
                <h2 class="display-6 mb-4">Metode Belajar Modern</h2>

                <div class="d-flex gap-4 mb-4">
                    <div class="icon-box bg-light border flex-shrink-0">
                        <i class="fa-solid fa-laptop-code text-primary-custom"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Virtual Lab Interaktif</h5>
                        <p class="text-secondary">Tidak perlu PC spek tinggi. Akses server virtual langsung dari
                            browser.</p>
                    </div>
                </div>

                <div class="d-flex gap-4">
                    <div class="icon-box bg-light border flex-shrink-0">
                        <i class="fa-solid fa-clipboard-check text-primary-custom"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Test Sumatif</h5>
                        <p class="text-secondary">Evaluasi kompetensi siswa di akhir modul menggunakan sistem ujian.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-custom mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-primary-custom mb-3">VLab-ASJ</h5>
                    <p class="text-secondary small">Platform media pembelajaran interaktif untuk Administrasi Sistem
                        Jaringan (ASJ) berbasis kurikulum SMK.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Materi</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">SSH</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">DNS</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Web Server</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Database</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3">Akses</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="<?= base_url('login') ?>"
                                class="text-decoration-none text-secondary">Login Siswa</a></li>
                        <li class="mb-2"><a href="<?= base_url('register') ?>"
                                class="text-decoration-none text-secondary">Daftar Akun</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">Hubungi Kami</h6>
                    <p class="text-secondary small mb-2"><i class="fa-solid fa-envelope me-2"></i>
                        fdhlvidiarta@gmail.com
                    </p>
                    <p class="text-secondary small"><i class="fa-solid fa-map-pin me-2"></i> Lab TKJ, SMKN 1 Jiwan
                    </p>
                </div>
            </div>
            <div class="border-top pt-4 text-center">
                <p class="small text-secondary mb-0">&copy; <?= date('Y'); ?> V-Lab ASJ - Media Pembelajaran Siswa SMKN
                    1 Jiwan.</p>
            </div>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>