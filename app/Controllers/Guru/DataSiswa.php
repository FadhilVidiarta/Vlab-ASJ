<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DataSiswa extends BaseController
{
    /** @var UserModel */
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $idGuru = session()->get('id');
        $dataGuru = $this->userModel->find($idGuru);
        $keyword = $this->request->getVar('keyword');

        $query = $this->userModel->where('role', 'siswa');

        if ($keyword) {
            $query = $query->groupStart()
                ->like('nama_lengkap', $keyword)
                ->orLike('email', $keyword)
                ->orLike('kelas', $keyword)
                ->groupEnd();
        }

        $dataSiswa = $query->orderBy('idUser', 'DESC')->paginate(5, 'siswa_group');

        $data = [
            'title' => 'Data Siswa',
            'active_menu' => 'data_siswa',
            'admin' => $dataGuru,
            'users' => $dataSiswa,
            'pager' => $this->userModel->pager,
            'keyword' => $keyword,
            'nomor' => ($this->request->getVar('page_siswa_group') ? $this->request->getVar('page_siswa_group') - 1 : 0) * 5 + 1
        ];

        return view('guru/data_siswa', $data);
    }

    public function hapus_siswa(string $id)
    {
        $this->userModel->delete($id);

        return redirect()->to('guru/data_siswa')->with('success', 'Data siswa berhasil dihapus.');
    }
}