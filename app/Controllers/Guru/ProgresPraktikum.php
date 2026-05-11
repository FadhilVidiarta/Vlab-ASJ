<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VlabModel;

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
            ->paginate(5, 'progres_group');

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

        if ($vlabModel->find($id_vlab)) {
            $vlabModel->delete($id_vlab);
            return redirect()->back()->with('success', 'Riwayat praktikum siswa berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Data riwayat praktikum tidak ditemukan.');
    }
}