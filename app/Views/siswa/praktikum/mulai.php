<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Deklarasi variabel agar VS Code Intelephense tidak rewel
 * @var array $materi
 * @var string $os_name
 * @var string|int $vmid
 * @var string $node_name
 * @var string $ticket
 * @var string|int $port
 */
?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@5.3.0/css/xterm.min.css" />
<script src="https://cdn.jsdelivr.net/npm/xterm@5.3.0/lib/xterm.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.min.js"></script>

<style>
    /* 1. MENGHILANGKAN SEMUA ELEMEN BAWAAN LAYOUT */
    header, nav, footer, .navbar, .sidebar, #sidebar {
        display: none !important;
    }

    /* 2. MEMBUAT TAMPILAN FULL SCREEN PENUH */
    .vlab-wrapper {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background-color: #1e1e1e; z-index: 9999; display: flex; flex-direction: column;
    }

    /* 3. HEADER V-LAB */
    .vlab-header {
        height: auto; min-height: 60px; background-color: #ffffff; border-bottom: 1px solid #dee2e6;
        display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; flex-shrink: 0;
    }

    /* 4. AREA SPLIT SCREEN */
    .vlab-body {
        display: flex; flex-grow: 1; overflow: hidden;
    }

    .panel-kiri {
        width: 50%; background-color: #f8f9fa; height: 100%; display: flex; flex-direction: column;
    }

    /* REVISI CSS PANEL KANAN AGAR TIDAK KEPOTONG */
    .panel-kanan {
        flex-grow: 1; 
        background-color: #000000; 
        display: flex; 
        flex-direction: column; /* Mengubah panel kanan jadi tumpukan (Tombol Atas, Terminal Bawah) */
        position: relative;
    }

    .resizer {
        width: 6px; background-color: #dee2e6; cursor: col-resize; display: flex;
        justify-content: center; align-items: center; transition: background-color 0.2s;
    }

    .resizer:hover, .resizer:active {
        background-color: #0d6efd;
    }

    .resizer::after {
        content: "⋮"; color: #6c757d; font-size: 18px;
    }

    /* Mencegah baris tombol tertekan (mengecil) */
    .virtual-keys {
        flex-shrink: 0; 
    }

    /* Terminal mengisi sisa ruang yang ada tanpa melebihi batas layar */
    #terminal-container {
        width: 100%; 
        flex-grow: 1; 
        overflow: hidden;
        padding: 5px; 
    }
</style>

<div class="vlab-wrapper">
    <div class="vlab-header shadow-sm">
        <div class="d-flex flex-column justify-content-center">
            <div class="mb-1">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary me-2">V-Lab System</span>
                <span class="fw-bold text-dark fs-6"><?= esc((string) ($materi['judul_materi'] ?? 'Praktikum')) ?></span>
                <span class="text-secondary mx-2">|</span>
                <span class="text-secondary small font-monospace">
                    <i class="fa-brands fa-<?= esc(strtolower((string) ($os_name ?? 'linux'))) ?> me-1"></i> OS:
                    <?= esc(ucfirst((string) ($os_name ?? 'Linux'))) ?>
                </span>
            </div>
        </div>

        <div>
            <button type="button" class="btn btn-danger"
                onclick="akhiriSesiPraktikum('<?= base_url('siswa/praktikum/akhiri_sesi/' . (string) ($vmid ?? '0')) ?>')">
                <i class="fa-solid fa-power-off me-1"></i> Akhiri Praktikum
            </button>
        </div>
    </div>

    <div class="vlab-body">
        <div class="panel-kiri" id="panelKiri">
            <?php $filePdf = isset($materi['file_pdf']) ? (string) $materi['file_pdf'] : ''; ?>
            <?php if (!empty($filePdf)): ?>
                <iframe src="https://docs.google.com/gview?url=<?= urlencode(base_url('uploads/materi/' . $filePdf)) ?>&embedded=true" width="100%" height="100%" style="border: none;"></iframe>
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
            <?php if (!empty($vmid) && !empty($ticket)): ?>
                
                <div class="virtual-keys d-flex gap-2 p-2 bg-dark border-bottom border-secondary overflow-auto" style="white-space: nowrap;">
                    <button class="btn btn-sm btn-secondary py-0" onclick="sendCtrl('C')">Ctrl+C (Stop)</button>
                    <button class="btn btn-sm btn-primary py-0" onclick="sendCtrl('S')">Ctrl+S (Save)</button>
                    <button class="btn btn-sm btn-secondary py-0" onclick="sendKey('\x0D')">Enter (Nano)</button>
                    <button class="btn btn-sm btn-secondary py-0" onclick="sendCtrl('X')">Ctrl+X (Exit Nano)</button>
                    <button class="btn btn-sm btn-secondary py-0" onclick="sendKey('\x1B')">ESC</button>
                    <button class="btn btn-sm btn-secondary py-0" onclick="sendKey('\x09')">TAB</button>
                </div>

                <div id="terminal-container"></div>
                
            <?php else: ?>
                <div class="d-flex flex-column justify-content-center align-items-center h-100 text-secondary font-monospace bg-dark">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3 text-warning"></i>
                    <h4 class="text-light">Gagal memuat mesin V-Lab.</h4>
                    <p>Tiket Terminal gagal didapatkan. Silakan muat ulang halaman.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // === 1. LOGIKA RESIZER PANEL ===
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
                if (typeof fitAddon !== 'undefined') fitAddon.fit();
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

    // === 2. LOGIKA TERMINAL PROXMOX (Xterm.js) ===
    const vmid = '<?= esc((string) ($vmid ?? '')) ?>';
    const node = '<?= esc((string) ($node_name ?? 'vlab')) ?>';
    const ticket = encodeURIComponent('<?= esc((string) ($ticket ?? '')) ?>');
    const port = '<?= esc((string) ($port ?? '')) ?>';
    const apiUser = 'root@pam!vlabci4'; 

    let term, fitAddon, socket, pingInterval;

    if (ticket && port) {
        term = new window.Terminal({
            cursorBlink: true,
            theme: { background: '#000000', foreground: '#ffffff' },
            fontFamily: 'Consolas, monospace',
            fontSize: 14
        });
        fitAddon = new window.FitAddon.FitAddon();
        term.loadAddon(fitAddon);
        term.open(document.getElementById('terminal-container'));
        
        // Jeda sedikit agar flexbox CSS selesai merender ukuran sebelum di-fit
        setTimeout(() => {
            fitAddon.fit();
        }, 100);

        term.writeln('\x1b[33m[*] Menghubungkan ke Server V-Lab...\x1b[0m');

        const wsUrl = `${window.location.protocol === 'https:' ? 'wss://' : 'ws://'}${window.location.host}/api2/json/nodes/${node}/lxc/${vmid}/vncwebsocket?port=${port}&vncticket=${ticket}`;

        socket = new window.WebSocket(wsUrl);
        socket.binaryType = 'arraybuffer';

        socket.onopen = () => {
            // Handshake Wajib
            socket.send(apiUser + ':' + decodeURIComponent(ticket) + '\n');
            
            // Paksa hitung ulang ukuran layar dan laporkan ke Proxmox di detik pertama
            setTimeout(() => {
                fitAddon.fit();
                socket.send('1:' + term.cols + ':' + term.rows + ':');
            }, 500);

            // PING ANTI-MATI
            pingInterval = setInterval(() => {
                if (socket.readyState === window.WebSocket.OPEN) {
                    socket.send("2"); 
                }
            }, 20000);
        };

        socket.onmessage = event => {
            if (typeof event.data === 'string') {
                term.write(event.data);
            } else {
                const data = new window.Uint8Array(event.data);
                term.write(new window.TextDecoder().decode(data));
            }
        };

        socket.onclose = (e) => {
            clearInterval(pingInterval);
            term.writeln(`\r\n\x1b[31m[-] Koneksi Terputus (Code: ${e.code}). Silakan refresh halaman.\x1b[0m`);
        };

        socket.onerror = (err) => {
            term.writeln('\r\n\x1b[31m[!] Error Jaringan Terdeteksi.\x1b[0m');
        };

        term.onData(data => {
            if (socket.readyState === window.WebSocket.OPEN) {
                const byteLength = new window.TextEncoder().encode(data).length;
                socket.send('0:' + byteLength + ':' + data);
            }
        });

        term.onResize(size => {
            if (socket.readyState === window.WebSocket.OPEN) {
                socket.send('1:' + size.cols + ':' + size.rows + ':');
            }
        });

        window.addEventListener('resize', () => fitAddon.fit());
    }

    // === 3. LOGIKA AKHIRI SESI & KEEP ALIVE WEB ===
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
            .catch(error => console.error("Heartbeat error:", error));
    }, 240000);

    // === 4. FUNGSI TOMBOL VIRTUAL HP/DESKTOP ===
    function sendCtrl(char) {
        if (socket && socket.readyState === window.WebSocket.OPEN) {
            // Mengubah huruf (C, O, X) menjadi kode Control (Ctrl)
            const code = String.fromCharCode(char.charCodeAt(0) - 64);
            const byteLength = new window.TextEncoder().encode(code).length;
            socket.send('0:' + byteLength + ':' + code);
        }
    }

    function sendKey(hexCode) {
        if (socket && socket.readyState === window.WebSocket.OPEN) {
            const byteLength = new window.TextEncoder().encode(hexCode).length;
            socket.send('0:' + byteLength + ':' + hexCode);
        }
    }
</script>

<?= $this->endSection() ?>