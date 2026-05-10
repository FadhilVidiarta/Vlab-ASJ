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
    header,
    nav,
    footer,
    .navbar,
    .sidebar,
    #sidebar {
        display: none !important;
    }

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

    .vlab-body {
        display: flex;
        flex-grow: 1;
        overflow: hidden;
    }

    .panel-kiri {
        width: 40%;
        background-color: #f8f9fa;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .panel-kanan {
        flex-grow: 1;
        background-color: #000000;
        height: 100%;
        position: relative;
        padding: 10px;
    }

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

    #terminal-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

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
    // XTERM.JS & WEBSOCKET PROXMOX
    const vmid = '<?= $vmid ?>';
    const node = '<?= $node_name ?>';
    const ticket = encodeURIComponent('<?= $ticket ?>');
    const port = '<?= $port ?>';

    if (ticket && port) {
        const term = new window.Terminal({
            cursorBlink: true,
            theme: { background: '#000000', foreground: '#ffffff' },
            fontFamily: 'Consolas, monospace',
            fontSize: 14
        });

        const fitAddon = new window.FitAddon.FitAddon();
        term.loadAddon(fitAddon);

        term.open(document.getElementById('terminal-container'));
        fitAddon.fit();
        term.writeln('\x1b[32m[*] Menghubungkan ke server praktikum V-Lab...\x1b[0m');

        // WebSocket langsung mengarah ke Domain Cloudflare Proxmox
        const wsProtocol = 'wss://';
        const wsHost = 'proxmox.vlabasj.biz.id:8006';
        const wsUrl = `${wsProtocol}${wsHost}/api2/json/nodes/${node}/lxc/${vmid}/vncwebsocket?port=${port}&vncticket=${ticket}`;

        const socket = new window.WebSocket(wsUrl);
        socket.binaryType = 'arraybuffer';

        socket.onopen = () => {
            term.clear();
            term.writeln('\x1b[32m[+] Berhasil terhubung. Tekan ENTER untuk memulai terminal.\x1b[0m\r\n');
        };

        term.onData(data => {
            if (socket.readyState === window.WebSocket.OPEN) {
                socket.send(data);
            }
        });

        socket.onmessage = event => {
            if (typeof event.data === 'string') {
                term.write(event.data);
            } else {
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

        window.addEventListener('resize', () => fitAddon.fit());

        document.addEventListener('mouseup', () => fitAddon.fit());
    }

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

            if (persentaseKiri > 20 && persentaseKiri < 80) {
                kiri.style.width = persentaseKiri + '%';
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

    function akhiriSesiPraktikum(urlTarget) {
        if (confirm('Akhiri sesi praktikum? Mesin V-Lab Anda akan dimatikan.')) {
            window.onbeforeunload = null;
            let iframes = document.querySelectorAll('iframe');
            iframes.forEach(iframe => iframe.remove());

            window.location.href = urlTarget;
        }
    }

    setInterval(function () {
        fetch("<?= base_url('siswa/praktikum/keep_alive') ?>")
            .then(response => response.json())
            .then(data => console.log("Sinyal Heartbeat terkirim: V-Lab Aktif"))
            .catch(error => console.error("Gagal mengirim heartbeat:", error));
    }, 240000); // 4 menit sekali
</script>

<?= $this->endSection() ?>