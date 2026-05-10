<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\MateriModel;
use App\Models\UserModel;
use App\Libraries\ProxmoxAPI;

class Praktikum extends BaseController
{
    private string $node_name;

    public function __construct()
    {
        $this->node_name = 'vlab';
    }

    public function index()
    {
        $materiModel = new MateriModel();
        $userModel = new UserModel();
        $db = \Config\Database::connect();
        $idUser = session()->get('id');

        $history_praktikum = $db->table('vlab_ct')
            ->select('vlab_ct.idVlabCT as id_log, vlab_ct.vmid, vlab_ct.nama_os, materi.judul_materi, vlab_ct.waktu_mulai, vlab_ct.waktu_selesai, vlab_ct.progres, vlab_ct.status_praktikum')
            ->join('materi', 'materi.idMateri = vlab_ct.idMateri', 'left')
            ->where('vlab_ct.idUser', $idUser)
            ->orderBy('vlab_ct.idVlabCT', 'DESC')
            ->get()->getResultArray();

        $mesin_aktif = null;
        foreach ($history_praktikum as $h) {
            if (!empty($h['vmid']) && $h['vmid'] != 0) {
                $mesin_aktif = [
                    'vmid' => $h['vmid'],
                    'nama_os' => $h['nama_os']
                ];
                break;
            }
        }

        $data = [
            'title' => 'Praktikum V-Lab',
            'active_menu' => 'praktikum',
            'user' => $userModel->find($idUser),
            'materi' => $materiModel->where('status', 'aktif')->findAll(),
            'history_praktikum' => $history_praktikum,
            'mesin_aktif' => $mesin_aktif
        ];

        return view('siswa/praktikum/pilih_praktikum', $data);
    }

    public function mulai(string $materi_id, string $os_name)
    {
        $materiModel = new MateriModel();
        $userModel = new UserModel();
        $materi = $materiModel->find($materi_id);
        $idUser = session()->get('id');

        if (!$materi) {
            return redirect()->to('siswa/praktikum')->with('errors', ['Materi tidak ditemukan.']);
        }

        $api = new ProxmoxAPI();
        $db = \Config\Database::connect();
        $builder = $db->table('vlab_ct');

        $sesi_aktif = $builder->where('idUser', $idUser)->where('vmid !=', 0)->get()->getRowArray();

        if ($sesi_aktif) {
            $vmid_final = $sesi_aktif['vmid'];
            $os_final = $sesi_aktif['nama_os'];
            $api->request("/nodes/{$this->node_name}/lxc/{$vmid_final}/status/start", "POST");
        } else {
            $template_map = ['debian' => 102, 'ubuntu' => 101, 'centos' => 103];
            $template_vmid = $template_map[strtolower($os_name)] ?? 102;

            $new_vmid_resp = $api->request('/cluster/nextid');
            if (empty($new_vmid_resp['data'])) {
                return redirect()->to('siswa/praktikum')->with('error', 'Gagal mendapatkan ID baru dari server V-Lab.');
            }
            $new_vmid = $new_vmid_resp['data'];

            // Eksekusi Perintah CLONE (DENGAN FULL CLONE)
            $clone_resp = $api->request("/nodes/{$this->node_name}/lxc/{$template_vmid}/clone", "POST", [
                'newid' => $new_vmid,
                'hostname' => 'siswa-' . $idUser . '-' . $os_name,
                'full' => 1
            ]);

            if (empty($clone_resp['data'])) {
                return redirect()->to('siswa/praktikum')->with('error', 'Proxmox menolak Clone. Cek penyimpanan VPS Anda.');
            }
            sleep(15);

            $api->request("/nodes/{$this->node_name}/lxc/{$new_vmid}/status/start", "POST");

            $vmid_final = $new_vmid;
            $os_final = $os_name;

            $builder->where('idUser', $idUser)->update([
                'vmid' => $vmid_final,
                'nama_os' => $os_final
            ]);
        }

        sleep(4);

        $termData = $api->request("/nodes/{$this->node_name}/lxc/{$vmid_final}/termproxy", "POST");
        $ticket = $termData['data']['ticket'] ?? '';
        $port = $termData['data']['port'] ?? '';

        $log_modul = $builder->where(['idUser' => $idUser, 'idMateri' => $materi_id])->get()->getRowArray();

        if (!$log_modul) {
            $builder->insert([
                'idUser' => $idUser,
                'idMateri' => $materi_id,
                'vmid' => $vmid_final,
                'nama_os' => $os_final,
                'progres' => 0,
                'status_praktikum' => 'Proses',
                'waktu_mulai' => date('Y-m-d H:i:s')
            ]);
        }

        $data = [
            'title' => 'V-Lab: ' . ucfirst($os_final),
            'active_menu' => 'praktikum',
            'user' => $userModel->find($idUser),
            'materi' => $materi,
            'os_name' => $os_final,
            'vmid' => $vmid_final,
            'node_name' => $this->node_name,
            'ticket' => $ticket,
            'port' => $port
        ];

        return view('siswa/praktikum/mulai', $data);
    }

    public function hapus_mesin()
    {
        $db = \Config\Database::connect();
        $idUser = session()->get('id');

        $sesi = $db->table('vlab_ct')->where('idUser', $idUser)->where('vmid !=', 0)->get()->getRowArray();

        if ($sesi) {
            $vmid = $sesi['vmid'];

            try {
                $api = new ProxmoxAPI();
                $api->request("/nodes/{$this->node_name}/lxc/{$vmid}/status/stop", "POST");
                sleep(4);
                $api->request("/nodes/{$this->node_name}/lxc/{$vmid}", "DELETE");
            } catch (\Exception $e) {

            }

            $db->table('vlab_ct')->where('idUser', $idUser)->where('vmid', $vmid)->update([
                'vmid' => 0
            ]);

            return redirect()->to('siswa/praktikum')->with('success', 'OS Proxmox diproses untuk dihancurkan! Silakan klik tombol Tong Sampah di tabel bawah jika ingin menghapus riwayatnya.');
        }

        return redirect()->to('siswa/praktikum');
    }

    public function akhiri_sesi(string $vmid)
    {
        $api = new ProxmoxAPI();
        $api->request("/nodes/{$this->node_name}/lxc/{$vmid}/status/stop", "POST");
        return redirect()->to('siswa/praktikum')->with('success', 'Terminal ditutup. OS telah dimatikan sementara.');
    }

    public function lanjutkan_log(string $id_log)
    {
        $db = \Config\Database::connect();
        $idUser = session()->get('id');
        $sesi = $db->table('vlab_ct')->where(['idVlabCT' => $id_log, 'idUser' => $idUser])->get()->getRowArray();

        if (!$sesi || empty($sesi['vmid']) || $sesi['vmid'] == 0) {
            return redirect()->to('siswa/praktikum')->with('error', 'Mesin OS sudah dihancurkan. Silakan pilih modul ini di form "Mulai Praktikum Baru" untuk membuat OS kembali.');
        }

        $api = new ProxmoxAPI();
        $api->request("/nodes/{$this->node_name}/lxc/{$sesi['vmid']}/status/start", "POST");

        sleep(4);

        $termData = $api->request("/nodes/{$this->node_name}/lxc/{$sesi['vmid']}/termproxy", "POST");
        $ticket = $termData['data']['ticket'] ?? '';
        $port = $termData['data']['port'] ?? '';

        $data = [
            'title' => 'V-Lab: ' . ucfirst($sesi['nama_os']),
            'active_menu' => 'praktikum',
            'user' => (new UserModel())->find($idUser),
            'materi' => (new MateriModel())->find($sesi['idMateri']),
            'os_name' => $sesi['nama_os'],
            'vmid' => $sesi['vmid'],
            'node_name' => $this->node_name,
            'ticket' => $ticket,
            'port' => $port
        ];

        return view('siswa/praktikum/mulai', $data);
    }

    public function hapus_riwayat(string $id_log)
    {
        $db = \Config\Database::connect();
        $idUser = session()->get('id');

        $log = $db->table('vlab_ct')->where(['idVlabCT' => $id_log, 'idUser' => $idUser])->get()->getRow();

        if ($log) {
            $total_log = $db->table('vlab_ct')->where('idUser', $idUser)->countAllResults();
            if ($total_log == 1 && $log->vmid != 0) {
                return redirect()->to('siswa/praktikum')->with('error', 'PERINGATAN: OS Anda masih ada! Anda wajib mengklik tombol "Hapus OS" terlebih dahulu sebelum bisa menghapus riwayat terakhir ini.');
            }

            $db->table('vlab_ct')->where('idVlabCT', $id_log)->delete();
            return redirect()->to('siswa/praktikum')->with('success', 'Riwayat pengerjaan berhasil dihapus.');
        }
        return redirect()->to('siswa/praktikum')->with('error', 'Data riwayat tidak ditemukan.');
    }

    public function keep_alive()
    {
        session()->set('last_active', time());
        return $this->response->setJSON(['status' => 'alive']);
    }
}