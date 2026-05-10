<?= $this->extend('layouts/layout_siswa') ?>

<?php
/**
 * Deklarasi variabel agar VS Code Intelephense tidak rewel
 * @var array $materi
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
        height: 50px;
        background-color: #ffffff;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
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
        border-right: 2px solid #dee2e6;
    }

    .panel-kanan {
        flex-grow: 1;
        background-color: #000000;
        height: 100%;
        padding: 10px;
    }

    #terminal-container {
        width: 100%;
        height: 100%;
    }
</style>

<div class="vlab-wrapper">
    <div class="vlab-header">
        <div class="fw-bold">V-Lab ASJ | <?= esc((string) ($materi['judul_materi'] ?? 'Praktikum')) ?></div>
        <button class="btn btn-sm btn-danger"
            onclick="window.location.href='<?= base_url('siswa/praktikum/akhiri_sesi/' . (string) ($vmid ?? '0')) ?>'">Akhiri
            Sesi</button>
    </div>

    <div class="vlab-body">
        <div class="panel-kiri">
            <?php $filePdf = isset($materi['file_pdf']) ? (string) $materi['file_pdf'] : ''; ?>
            <?php if (!empty($filePdf)): ?>
                <iframe src="<?= base_url('uploads/materi/' . $filePdf) ?>#toolbar=0" width="100%" height="100%"
                    style="border: none;"></iframe>
            <?php else: ?>
                <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                    <p>PDF Modul tidak tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel-kanan">
            <div id="terminal-container"></div>
        </div>
    </div>
</div>

<script>
    // Ambil data dari PHP dan paksa menjadi string
    const vmid = '<?= esc((string) ($vmid ?? '')) ?>';
    const node = '<?= esc((string) ($node_name ?? 'vlab')) ?>';
    const ticket = encodeURIComponent('<?= esc((string) ($ticket ?? '')) ?>');
    const port = '<?= esc((string) ($port ?? '')) ?>';
    const apiUser = 'root@pam!vlabci4'; // Token ID Mas

    // Inisialisasi xterm.js
    const term = new window.Terminal({
        cursorBlink: true,
        theme: { background: '#000000' },
        fontSize: 14
    });
    const fitAddon = new window.FitAddon.FitAddon();
    term.loadAddon(fitAddon);
    term.open(document.getElementById('terminal-container'));
    fitAddon.fit();

    if (!ticket || !port) {
        term.writeln('\x1b[31m[!] Error: Tiket atau Port tidak didapatkan dari server Proxmox.\x1b[0m');
    } else {
        term.writeln('\x1b[33m[*] Menghubungkan ke WebSocket...\x1b[0m');

        // Alamat WebSocket via Reverse Proxy Apache
        const wsUrl = `${window.location.protocol === 'https:' ? 'wss://' : 'ws://'}${window.location.host}/api2/json/nodes/${node}/lxc/${vmid}/vncwebsocket?port=${port}&vncticket=${ticket}`;

        const socket = new window.WebSocket(wsUrl);
        socket.binaryType = 'arraybuffer';

        socket.onopen = () => {
            // --- HANDSHAKE WAJIB PROXMOX ---
            const decodedTicket = decodeURIComponent(ticket);
            socket.send(apiUser + ':' + decodedTicket + '\n');

            term.writeln('\x1b[32m[+] Koneksi Terbuka. Tekan ENTER untuk masuk ke console.\x1b[0m');
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
            term.writeln(`\r\n\x1b[31m[-] Koneksi Tertutup. Code: ${e.code}\x1b[0m`);
            console.log('WS Close:', e);
        };

        socket.onerror = (err) => {
            term.writeln('\r\n\x1b[31m[!] WebSocket Error Terdeteksi.\x1b[0m');
            console.error('WS Error:', err);
        };

        // Input dari keyboard ke socket
        term.onData(data => {
            if (socket.readyState === window.WebSocket.OPEN) socket.send(data);
        });

        window.addEventListener('resize', () => fitAddon.fit());
    }
</script>

<?= $this->endSection() ?>