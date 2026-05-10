<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MateriModel;

class Materi extends BaseController
{
    /** @var UserModel */
    protected $userModel;

    /** @var MateriModel */
    protected $materiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->materiModel = new MateriModel();
    }

    public function index()
    {
        $idGuru = session()->get('id');
        $dataGuru = $this->userModel->find($idGuru);

        $keyword = $this->request->getVar('keyword');
        $query = $this->materiModel;

        if ($keyword) {
            $query = $query->groupStart()
                ->like('judul_materi', $keyword)
                ->orLike('sub_materi', $keyword)
                ->groupEnd();
        }

        $dataMateri = $query->orderBy('idMateri', 'DESC')->paginate(5, 'materi_group');

        $data = [
            'title' => 'Kelola Materi',
            'active_menu' => 'data_materi',
            'admin' => $dataGuru,
            'materi' => $dataMateri,
            'pager' => $this->materiModel->pager,
            'keyword' => $keyword,
            'nomor' => ($this->request->getVar('page_materi_group') ? $this->request->getVar('page_materi_group') - 1 : 0) * 5 + 1
        ];

        return view('guru/materi/data_materi', $data);
    }

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Materi',
            'active_menu' => 'data_materi',
            'admin' => $this->userModel->find(session()->get('id')),
            'validation' => \Config\Services::validation()
        ];

        return view('guru/materi/tambah_materi', $data);
    }

    public function simpan()
    {
        $rules = [
            'judul_materi' => 'required',
            'sub_materi' => 'required',
            'file_pdf' => 'uploaded[file_pdf]|max_size[file_pdf,10240]|ext_in[file_pdf,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file PDF
        $filePdf = $this->request->getFile('file_pdf');

        $namaFilePdf = $filePdf->getRandomName();

        $filePdf->move('uploads/materi', $namaFilePdf);

        $this->materiModel->save([
            'idUser' => session()->get('id'),
            'judul_materi' => $this->request->getPost('judul_materi'),
            'sub_materi' => $this->request->getPost('sub_materi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'file_pdf' => $namaFilePdf,
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('guru/materi')->with('success', 'Materi dan file PDF berhasil ditambahkan!');
    }

    /**
     * --- FUNGSI DETAIL MATERI ---
     * @param string $id
     */
    public function detail(string $id)
    {
        $dataMateri = $this->materiModel->find($id);
        if (!$dataMateri)
            return redirect()->to('guru/materi')->with('error', 'Data tidak ditemukan.');

        $data = [
            'title' => 'Detail Materi',
            'active_menu' => 'data_materi',
            'admin' => $this->userModel->find(session()->get('id')),
            'materi' => $dataMateri
        ];

        return view('guru/materi/detail_materi', $data);
    }

    /**
     * --- FUNGSI TAMPILKAN FORM EDIT ---
     * @param string $id
     */
    public function edit(string $id)
    {
        $dataMateri = $this->materiModel->find($id);
        if (!$dataMateri)
            return redirect()->to('guru/materi')->with('error', 'Data tidak ditemukan.');

        $data = [
            'title' => 'Edit Materi',
            'active_menu' => 'data_materi',
            'admin' => $this->userModel->find(session()->get('id')),
            'materi' => $dataMateri,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/materi/edit_materi', $data);
    }

    /**
     * --- FUNGSI UPDATE DATA & REPLACE PDF ---
     * @param string $id
     */
    public function update(string $id)
    {
        $materiLama = $this->materiModel->find($id);
        if (!$materiLama)
            return redirect()->to('guru/materi');

        // Aturan validasi (PDF tidak wajib diisi saat edit)
        $rules = [
            'judul_materi' => 'required',
            'sub_materi' => 'required',
            'file_pdf' => 'max_size[file_pdf,10240]|ext_in[file_pdf,pdf]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaFilePdf = $materiLama['file_pdf'];
        $filePdf = $this->request->getFile('file_pdf');

        if ($filePdf->getError() != 4) {
            if ($materiLama['file_pdf'] && file_exists('uploads/materi/' . $materiLama['file_pdf'])) {
                unlink('uploads/materi/' . $materiLama['file_pdf']);
            }

            $namaFilePdf = $filePdf->getRandomName();
            $filePdf->move('uploads/materi', $namaFilePdf);
        }

        $this->materiModel->update($id, [
            'judul_materi' => $this->request->getPost('judul_materi'),
            'sub_materi' => $this->request->getPost('sub_materi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'file_pdf' => $namaFilePdf,
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('guru/materi')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * --- FUNGSI HAPUS MATERI ---
     * @param string $id
     */
    public function hapus(string $id)
    {
        $materi = $this->materiModel->find($id);
        if ($materi) {
            if ($materi['file_pdf'] && file_exists('uploads/materi/' . $materi['file_pdf'])) {
                unlink('uploads/materi/' . $materi['file_pdf']);
            }
            $this->materiModel->delete($id);
            return redirect()->to('guru/materi')->with('success', 'Data materi dan file PDF berhasil dihapus permanen.');
        }
        return redirect()->to('guru/materi')->with('error', 'Data gagal dihapus.');
    }
}