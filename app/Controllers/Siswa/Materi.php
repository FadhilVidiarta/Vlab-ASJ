<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MateriModel;

class Materi extends BaseController
{
    public function index()
    {
        $materiModel = new MateriModel();

        $materiAktif = $materiModel->where('status', 'aktif')->orderBy('idMateri', 'ASC')->findAll();

        $materiGrouped = [];
        foreach ($materiAktif as $item) {
            $judulUtama = $item['judul_materi'];
            if (!isset($materiGrouped[$judulUtama])) {
                $materiGrouped[$judulUtama] = [];
            }
            $materiGrouped[$judulUtama][] = $item;
        }

        $data = [
            'title' => 'Materi Pembelajaran',
            'active_menu' => 'materi',
            'user' => (new UserModel())->find(session()->get('id')),
            'materiGrouped' => $materiGrouped
        ];

        return view('siswa/materi/materi', $data);
    }

    /**
     * Menampilkan isi materi pembelajaran
     * @param string $id
     */
    public function baca(string $id)
    {
        $materiModel = new MateriModel();

        $dataMateri = $materiModel->where('status', 'aktif')->find($id);

        if (!$dataMateri) {
            return redirect()->to('siswa/dashboard')->with('error', 'Materi tidak ditemukan atau belum dirilis oleh guru.');
        }

        $data = [
            'title' => 'Baca Materi',
            'active_menu' => 'materi',
            'materi' => $dataMateri,
            'user' => (new UserModel())->find(session()->get('id'))
        ];

        return view('siswa/materi/baca_materi', $data);
    }
}