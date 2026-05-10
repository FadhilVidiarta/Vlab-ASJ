<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\NilaiModel;
use App\Models\UserModel;

class Nilai extends BaseController
{
    public function index()
    {
        $nilaiModel = new NilaiModel();
        $userModel = new UserModel();

        $daftar_nilai = $nilaiModel->select('nilai.*, users.nama_lengkap as nama_siswa, ujian.judul_ujian as judul_materi')
            ->join('users', 'users.idUser = nilai.idUser', 'left')
            ->join('ujian', 'ujian.idUjian = nilai.idUjian', 'left')
            ->orderBy('nilai.idNilai', 'DESC')
            ->paginate(5, 'nilai_group');

        $data = [
            'title' => 'Kelola Nilai Tes Sumatif',
            'active_menu' => 'nilai',
            'admin' => $userModel->find(session()->get('id')),
            'daftar_nilai' => $daftar_nilai,
            'pager' => $nilaiModel->pager,
            'nomor' => ($this->request->getVar('page_nilai_group') ? $this->request->getVar('page_nilai_group') - 1 : 0) * 5 + 1
        ];

        return view('guru/ujian/nilai', $data);
    }
}