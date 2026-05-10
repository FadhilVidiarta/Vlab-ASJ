<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Deklarasi variabel agar VS Code Intelephense tidak rewel
 * @var array $materi
 * @var string $os_name
 * @var int|string $vmid
 * @var string $node_name
 * @var string $ticket
 * @var string $port
 */
?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@5.3.0/css/xterm.min.css" />
<script src="https://cdn.jsdelivr.net/npm/xterm@5.3.0/lib/xterm.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.min.js"></script>

<style>
    /* 1. MENGHILANGKAN SEMUA ELEMEN BAWAAN LAYOUT */
    header,
    nav,
    footer,
    .navbar,
    .sidebar,
    #sidebar {
        display: none !important;
    }

    /* 2. MEMBUAT TAMPILAN FULL SCREEN PENUH */
    .vlab-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: #1e1e1e;
        z-index: 9999;
        display: flex;
        flex-direction: column;
    }

    /* 3. HEADER V-LAB */
    .vlab-header {
        height: auto;
        min-height: 60px;
        background-color: #ffffff;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        flex-shrink: 0;
    }

    /* 4. AREA SPLIT SCREEN (Bisa Digeser) */
    .vlab-body {
        display: flex;
        flex-grow: 1;
        overflow: hidden;
    }

    /* Panel Kiri (PDF) */
    .panel-kiri {
        width: 40%;
        background-color: #f8f9fa;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Panel Kanan (Terminal xterm.js) */
    .panel-kanan {
        flex-grow: 1;
        background-color: #000000;
        height: 100%;
        position: relative;
        padding: 10px;
        /* Sedikit padding agar teks terminal tidak menempel di tepi */
    }

    /* Garis Pemisah (Resizer) */
    .resizer {
        width: 6px;
        background-color: #dee2e6;
        cursor: col-resize;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.2s;
    }

    .resizer:hover,
    .resizer:active {
        background-color: #0d6efd;
    }

    .resizer::after {
        content: "⋮";
        color: #6c757d;
        font-size: 18px;
    }

    /* Penyesuaian agar terminal xterm memenuhi div */
    #terminal-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* Agar scrollbar terminal berfungsi dengan baik */
    .xterm-viewport {
        overflow-y: auto !important;
    }
</style>

<div class="vlab-wrapper">

    <div class="vlab-header shadow-sm">
        <div class="d-flex flex-column justify-content-center">
            <div class="mb-1">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary me-2">V-Lab System</span>
                <span class="fw-bold text-dark fs-6"><?= esc((string) $materi['judul_materi']) ?></span>
                <span class="text-secondary mx-2">|</span>
                <span class="text-secondary small font-monospace">
                    <i class="fa-brands fa-<?= esc(strtolower((string) $os_name)) ?> me-1"></i> OS:
                    <?= esc(ucfirst((string) $os_name)) ?>
                </span>
            </div>
        </div>

        <div>
            <button type="button" class="btn btn-danger"
                onclick="akhiriSesiPraktikum('<?= base_url('siswa/praktikum/akhiri_sesi/' . (string) $vmid) ?>')">
                <i class="fa-solid fa-power-off me-1"></i> Akhiri Praktikum
            </button>
        </div>
    </div>

    <div class="vlab-body">

        <div class="panel-kiri" id="panelKiri">
            <?php $filePdf = isset($materi['file_pdf']) ? (string) $materi['file_pdf'] : ''; ?>

            <?php if (!empty($filePdf)): ?>
                <iframe src="<?= base_url('uploads/materi/' . $filePdf) ?>#toolbar=0" width="100%" height="100%"
                    style="border: none;"></iframe>
            <?php else: ?>
                <div class="d-flex flex-column justify-content-center align-items-center h-100 text-secondary">
                    <i class="fa-regular fa-file-pdf fa-4x mb-3 text-muted"></i>
                    <h5>File PDF tidak ditemukan</h5>
                    <p class="small">Guru belum mengunggah file PDF untuk modul ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="resizer" id="dragMe"></div>

        <div class="panel-kanan" id="panelKanan">
            <?php if (!empty($sVmid = (string) $vmid) && !empty($ticket)): ?>
                <div id="terminal-container"></div>
            <?php else: ?>
                <div
                    class="d-flex flex-column justify-content-center align-items-center h-100 text-secondary font-monospace bg-dark">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3 text-warning"></i>
                    <h4 class="text-light">Gagal memuat mesin V-Lab.</h4>
                    <p>Tiket Terminal gagal didapatkan. Silakan muat ulang halaman atau hubungi Administrator.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    // =========================================================
    // LOGIKA XTERM.JS & WEBSOCKET PROXMOX
    // =========================================================
    const vmid = '<?= $vmid ?>';
    const node = '<?= $node_name ?>';
    const ticket = encodeURIComponent('<?= $ticket ?>');
    const port = '<?= $port ?>';

    if (ticket && port) {
        // Inisialisasi Terminal dengan menambahkan window. agar IDE VS Code tidak error
        const term = new window.Terminal({
            cursorBlink: true,
            theme: { background: '#000000', foreground: '#ffffff' },
            fontFamily: 'Consolas, monospace',
            fontSize: 14
        });

        // Fit Addon agar terminal meregang sesuai ukuran div layar kanan
        const fitAddon = new window.FitAddon.FitAddon();
        term.loadAddon(fitAddon);

        // Tampilkan terminal di dalam div container
        term.open(document.getElementById('terminal-container'));
        fitAddon.fit();
        term.writeln('\x1b[32m[*] Menghubungkan ke server praktikum V-Lab...\x1b[0m');

        // URL WebSocket lewat jalur Reverse Proxy Apache
        const wsProtocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
        const wsHost = window.location.host;
        const wsUrl = `${wsProtocol}${wsHost}/api2/json/nodes/${node}/lxc/${vmid}/vncwebsocket?port=${port}&vncticket=${ticket}`;

        // Menggunakan window.WebSocket
        const socket = new window.WebSocket(wsUrl);
        socket.binaryType = 'arraybuffer'; // Format data native Proxmox

        socket.onopen = () => {
            term.clear();
            term.writeln('\x1b[32m[+] Berhasil terhubung. Tekan ENTER untuk memulai terminal.\x1b[0m\r\n');
        };

        // Mengirim input keyboard dari browser ke VPS Proxmox
        term.onData(data => {
            if (socket.readyState === window.WebSocket.OPEN) {
                socket.send(data);
            }
        });

        // Menerima output dari VPS Proxmox ke layar browser
        socket.onmessage = event => {
            if (typeof event.data === 'string') {
                term.write(event.data);
            } else {
                // Mengkonversi ArrayBuffer menjadi string menggunakan bawaan window browser
                const data = new window.Uint8Array(event.data);
                const decoder = new window.TextDecoder('utf-8');
                term.write(decoder.decode(data));
            }
        };

        socket.onclose = () => {
            term.writeln('\r\n\x1b[31m[-] Koneksi terputus dari mesin. Praktikum telah diakhiri.\x1b[0m');
        };

        socket.onerror = () => {
            term.writeln('\r\n\x1b[31m[!] Terjadi kesalahan jaringan pada koneksi WebSocket.\x1b[0m');
        };

        // Menyesuaikan ukuran terminal jika ukuran jendela browser diubah
        window.addEventListener('resize', () => fitAddon.fit());

        // Menyesuaikan ukuran terminal setelah user menggeser resizer panel
        document.addEventListener('mouseup', () => fitAddon.fit());
    }

    // =========================================================
    // LOGIKA RESIZER (PEMISAH LAYAR KIRI KANAN)
    // =========================================================
    document.addEventListener('DOMContentLoaded', function () {
        const resizer = document.getElementById('dragMe');
        const kiri = document.getElementById('panelKiri');
        const kanan = document.getElementById('panelKanan');
        let isDragging = false;

        resizer.addEventListener('mousedown', function (e) {
            isDragging = true;
            document.body.style.cursor = 'col-resize';
            kiri.style.pointerEvents = 'none';
            kanan.style.pointerEvents = 'none';
        });

        document.addEventListener('mousemove', function (e) {
            if (!isDragging) return;

            let lebarLayar = document.body.clientWidth;
            let posisiMouse = e.clientX;
            let persentaseKiri = (posisiMouse / lebarLayar) * 100;

            // Batasi geseran minimal 20% dan maksimal 80% dari lebar layar
            if (persentaseKiri > 20 && persentaseKiri < 80) {
                kiri.style.width = persentaseKiri + '%';
                // Trigger fit addon jika terminal sudah ada, agar teksnya menyesuaikan diri
                if (typeof fitAddon !== 'undefined') {
                    fitAddon.fit();
                }
            }
        });

        document.addEventListener('mouseup', function (e) {
            if (isDragging) {
                isDragging = false;
                document.body.style.cursor = 'default';
                kiri.style.pointerEvents = 'auto';
                kanan.style.pointerEvents = 'auto';
            }
        });
    });

    // =========================================================
    // FUNGSI UMUM (AKHIRI SESI & HEARTBEAT)
    // =========================================================
    function akhiriSesiPraktikum(urlTarget) {
        if (confirm('Akhiri sesi praktikum? Mesin V-Lab Anda akan dimatikan.')) {
            // Matikan warning onbeforeunload agar langsung direct
            window.onbeforeunload = null;
            // Hapus iframe PDF (opsional) agar loading lebih ringan saat redirect
            let iframes = document.querySelectorAll('iframe');
            iframes.forEach(iframe => iframe.remove());

            window.location.href = urlTarget;
        }
    }

    // Heartbeat agar sesi login CodeIgniter tidak expired
    setInterval(function () {
        fetch("<?= base_url('siswa/praktikum/keep_alive') ?>")
            .then(response => response.json())
            .then(data => console.log("Sinyal Heartbeat terkirim: V-Lab Aktif"))
            .catch(error => console.error("Gagal mengirim heartbeat:", error));
    }, 240000); // 4 menit sekali
</script>

<?= $this->endSection() ?>