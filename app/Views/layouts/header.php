<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>V-Lab ASJ-Platform Praktikum Virtual</title>
    <link rel="shortcut icon" href="<?= base_url('logo-vlab.png'); ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-glass transition-all" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary-custom fs-4" href="#">
                <i class="fa-solid fa-terminal me-2 text-primary-custom"></i> V-Lab ASJ
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center text-center">

                    <li class="nav-item">
                        <a class="nav-link mx-2 active-link" href="#hero" id="link-hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2" href="#materi" id="link-materi">Materi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2" href="#tentang" id="link-tentang">Tentang</a>
                    </li>

                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3 w-100 w-lg-auto">
                        <a href="<?= base_url('login') ?>"
                            class="btn btn-outline-blue btn-sm px-4 w-100 w-lg-auto d-block">Masuk</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2 w-100 w-lg-auto">
                        <a href="<?= base_url('register') ?>"
                            class="btn btn-blue btn-sm px-4 w-100 w-lg-auto d-block">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const nav = document.getElementById('mainNav');
            const sections = document.querySelectorAll('#hero, #materi, #tentang');
            const navLinks = document.querySelectorAll('.nav-link');

            function activateMenu() {
                let current = "";

                sections.forEach((section) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (window.scrollY >= (sectionTop - 300)) {
                        current = section.getAttribute("id");
                    }
                });

                navLinks.forEach((link) => {
                    link.classList.remove("active-link");
                    if (link.getAttribute("href").includes(current)) {
                        link.classList.add("active-link");
                    }
                });

                // Efek Glass Navbar
                if (window.scrollY > 10) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            }

            window.addEventListener("scroll", activateMenu);
            activateMenu();
        });
    </script>