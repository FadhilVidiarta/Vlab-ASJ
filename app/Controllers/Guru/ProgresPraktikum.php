<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VlabModel;
use App\Libraries\ProxmoxAPI;

class ProgresPraktikum extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $vlabModel = new VlabModel();

        $data_vlab = $vlabModel->select('vlab_ct.*, users.nama_lengkap as nama, users.kelas, materi.judul_materi')
            ->join('users', 'users.idUser = vlab_ct.idUser', 'left')
            ->join('materi', 'materi.idMateri = vlab_ct.idMateri', 'left')
            ->orderBy('vlab_ct.idVlabCT', 'DESC')
            ->paginate(20, 'progres_group');

        $data = [
            'title' => 'Progres Praktikum V-Lab',
            'active_menu' => 'progres_praktikum',
            'admin' => $userModel->find(session()->get('id')),
            'progres_praktikum' => $data_vlab,
            'pager' => $vlabModel->pager,
            'nomor' => ($this->request->getVar('page_progres_group') ? $this->request->getVar('page_progres_group') - 1 : 0) * 5 + 1
        ];

        return view('guru/progres_praktikum', $data);
    }

    public function hapus_log(string $id_vlab)
    {
        $vlabModel = new VlabModel();
        $log = $vlabModel->find($id_vlab);

        if ($log) {
            $idUser = $log['idUser'];

            $total_log = $vlabModel->where('idUser', $idUser)->countAllResults();

            if ($total_log == 1 && $log['vmid'] != 0) {
                return redirect()->back()->with('error', 'GAGAL MENGHAPUS LOG: Mesin OS (VMID: ' . $log['vmid'] . ') milik siswa ini masih AKTIF di Proxmox! Anda wajib mengklik tombol "Hapus OS Siswa" terlebih dahulu untuk menghancurkan mesinnya sebelum menghapus riwayat terakhir ini.');
            }

            $vlabModel->delete($id_vlab);
            return redirect()->back()->with('success', 'Riwayat praktikum siswa berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Data riwayat praktikum tidak ditemukan.');
    }

    public function hapus_mesin_siswa(string $id_vlab)
    {
        $vlabModel = new VlabModel();
        $log = $vlabModel->find($id_vlab);

        if ($log && $log['vmid'] != 0) {
            $vmid = $log['vmid'];
            $node_name = 'vlab';

            try {
                $api = new ProxmoxAPI();
                $api->request("/nodes/{$node_name}/lxc/{$vmid}/status/stop", "POST");
                sleep(4);
                $api->request("/nodes/{$node_name}/lxc/{$vmid}", "DELETE");
            } catch (\Exception $e) {
            }
            $vlabModel->update($id_vlab, ['vmid' => 0]);

            return redirect()->back()->with('success', 'OS Server (VMID: ' . $vmid . ') milik siswa berhasil dihancurkan. Resource VPS berhasil dihemat!');
        }

        return redirect()->back()->with('error', 'Mesin tidak ditemukan atau sudah dihapus sebelumnya.');
    }
}