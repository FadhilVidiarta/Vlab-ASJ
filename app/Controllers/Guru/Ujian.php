<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UjianModel;
use App\Models\SoalModel;

class Ujian extends BaseController
{
    /** @var UserModel */
    protected $userModel;
    /** @var UjianModel */
    protected $ujianModel;
    /** @var SoalModel */
    protected $soalModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ujianModel = new UjianModel();
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Tes Sumatif',
            'active_menu' => 'ujian',
            'admin' => $this->userModel->find(session()->get('id')),
            'ujian' => $this->ujianModel->orderBy('idUjian', 'DESC')->paginate(5, 'ujian_group'),
            'pager' => $this->ujianModel->pager,

            // RUMUS NOMOR UNTUK UJIAN
            'nomor' => ($this->request->getVar('page_ujian_group') ? $this->request->getVar('page_ujian_group') - 1 : 0) * 5 + 1
        ];

        return view('guru/ujian/data_ujian', $data);
    }

    public function simpan()
    {
        $rules = [
            'judul_ujian' => 'required',
            'durasi_menit' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Gagal menyimpan. Pastikan judul dan durasi diisi dengan benar.');
        }

        $this->ujianModel->save([
            'judul_ujian' => $this->request->getPost('judul_ujian'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'durasi_menit' => $this->request->getPost('durasi_menit'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('guru/ujian')->with('success', 'Tes Sumatif baru berhasil dibuat! Silakan tambahkan soal.');
    }

    public function soal(string $id_ujian)
    {
        $ujian = $this->ujianModel->find($id_ujian);
        if (!$ujian)
            return redirect()->to('guru/ujian')->with('error', 'Data ujian tidak ditemukan.');

        $data = [
            'title' => 'Kelola Soal: ' . $ujian['judul_ujian'],
            'active_menu' => 'ujian',
            'admin' => $this->userModel->find(session()->get('id')),
            'ujian' => $ujian,
            'soal' => $this->soalModel->where('idUjian', $id_ujian)->findAll()
        ];

        return view('guru/ujian/soal', $data);
    }

    public function tambah_soal(string $id_ujian)
    {
        $ujian = $this->ujianModel->find($id_ujian);
        if (!$ujian)
            return redirect()->to('guru/ujian');

        $data = [
            'title' => 'Tambah Soal',
            'active_menu' => 'ujian',
            'admin' => $this->userModel->find(session()->get('id')),
            'ujian' => $ujian,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/ujian/tambah_soal', $data);
    }

    public function simpan_soal(string $id_ujian)
    {
        $rules = [
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'kunci_jawaban' => 'required',
            'file_gambar' => 'max_size[file_gambar,2048]|is_image[file_gambar]|mime_in[file_gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileGambar = $this->request->getFile('file_gambar');
        $namaGambar = null;

        if ($fileGambar && $fileGambar->getError() != 4) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/soal', $namaGambar);
        }

        $this->soalModel->save([
            // REVISI: Ubah ujian_id menjadi idUjian
            'idUjian' => $id_ujian,
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'file_gambar' => $namaGambar,
            'opsi_a' => $this->request->getPost('opsi_a'),
            'opsi_b' => $this->request->getPost('opsi_b'),
            'opsi_c' => $this->request->getPost('opsi_c'),
            'opsi_d' => $this->request->getPost('opsi_d'),
            'opsi_e' => $this->request->getPost('opsi_e'),
            'kunci_jawaban' => $this->request->getPost('kunci_jawaban')
        ]);

        return redirect()->to('guru/ujian/soal/' . $id_ujian)->with('success', 'Soal berhasil ditambahkan!');
    }

    public function update(string $id_ujian)
    {
        $rules = [
            'judul_ujian' => 'required',
            'durasi_menit' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Gagal memperbarui. Pastikan form diisi.');
        }

        $this->ujianModel->update($id_ujian, [
            'judul_ujian' => $this->request->getPost('judul_ujian'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'durasi_menit' => $this->request->getPost('durasi_menit'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('guru/ujian')->with('success', 'Pengaturan Tes berhasil diperbarui!');
    }

    public function hapus_soal(string $id_soal, string $id_ujian)
    {
        $soal = $this->soalModel->find($id_soal);
        if ($soal) {
            if ($soal['file_gambar'] && file_exists('uploads/soal/' . $soal['file_gambar'])) {
                unlink('uploads/soal/' . $soal['file_gambar']);
            }
            $this->soalModel->delete($id_soal);
            return redirect()->to('guru/ujian/soal/' . $id_ujian)->with('success', 'Soal berhasil dihapus.');
        }
        return redirect()->to('guru/ujian/soal/' . $id_ujian);
    }

    public function hapus(string $id_ujian)
    {
        $this->ujianModel->delete($id_ujian);
        return redirect()->to('guru/ujian')->with('success', 'Tes Sumatif berhasil dihapus.');
    }

    public function edit_soal(string $id_soal, string $id_ujian)
    {
        $ujian = $this->ujianModel->find($id_ujian);
        $soal = $this->soalModel->find($id_soal);

        if (!$ujian || !$soal)
            return redirect()->to('guru/ujian/soal/' . $id_ujian);

        $data = [
            'title' => 'Edit Soal',
            'active_menu' => 'ujian',
            'admin' => $this->userModel->find(session()->get('id')),
            'ujian' => $ujian,
            'soal' => $soal,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/ujian/edit_soal', $data);
    }

    public function update_soal(string $id_soal, string $id_ujian)
    {
        $soalLama = $this->soalModel->find($id_soal);

        $rules = [
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'kunci_jawaban' => 'required',
        ];

        $fileGambar = $this->request->getFile('file_gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $rules['file_gambar'] = 'max_size[file_gambar,2048]|is_image[file_gambar]|mime_in[file_gambar,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaGambar = $soalLama['file_gambar'];

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            if (!empty($soalLama['file_gambar']) && file_exists('uploads/soal/' . $soalLama['file_gambar'])) {
                unlink('uploads/soal/' . $soalLama['file_gambar']);
            }
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/soal', $namaGambar);
        }

        if ($this->request->getPost('hapus_gambar') == '1') {
            if (!empty($soalLama['file_gambar']) && file_exists('uploads/soal/' . $soalLama['file_gambar'])) {
                unlink('uploads/soal/' . $soalLama['file_gambar']);
            }
            $namaGambar = null;
        }

        $this->soalModel->update($id_soal, [
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'file_gambar' => $namaGambar,
            'opsi_a' => $this->request->getPost('opsi_a'),
            'opsi_b' => $this->request->getPost('opsi_b'),
            'opsi_c' => $this->request->getPost('opsi_c'),
            'opsi_d' => $this->request->getPost('opsi_d'),
            'opsi_e' => $this->request->getPost('opsi_e'),
            'kunci_jawaban' => $this->request->getPost('kunci_jawaban')
        ]);

        return redirect()->to('guru/ujian/soal/' . $id_ujian)->with('success', 'Soal berhasil diperbarui!');
    }
}