<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UjianModel;
use App\Models\SoalModel;
use App\Models\NilaiModel;

class Ujian extends BaseController
{
    /** @var UserModel */
    protected $userModel;
    /** @var UjianModel */
    protected $ujianModel;
    /** @var SoalModel */
    protected $soalModel;
    /** @var NilaiModel */
    protected $nilaiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ujianModel = new UjianModel();
        $this->soalModel = new SoalModel();
        $this->nilaiModel = new NilaiModel();
    }

    public function index()
    {
        $userId = session()->get('id');

        // REVISI: siswa_id menjadi idUser
        $nilaiSiswa = $this->nilaiModel->where('idUser', $userId)->findAll();
        $nilaiMap = [];
        foreach ($nilaiSiswa as $n) {
            $nilaiMap[$n['idUjian']] = $n['nilai_akhir'];
        }

        $data = [
            'title' => 'Daftar Tes Sumatif',
            'active_menu' => 'ujian',
            'user' => $this->userModel->find($userId),
            'ujianAktif' => $this->ujianModel->where('status', 'aktif')->orderBy('idUjian', 'DESC')->findAll(),
            'nilaiMap' => $nilaiMap
        ];

        return view('siswa/ujian/daftar_ujian', $data);
    }

    public function kerjakan(string $id_ujian)
    {
        $ujian = $this->ujianModel->find($id_ujian);
        if (!$ujian || $ujian['status'] == 'draft') {
            return redirect()->to('siswa/ujian')->with('errors', ['Tes Sumatif tidak tersedia.']);
        }

        $sudah_mengerjakan = $this->nilaiModel->where('idUjian', $id_ujian)
            ->where('idUser', session()->get('id'))
            ->first();

        if ($sudah_mengerjakan) {
            return redirect()->to('siswa/ujian')->with('success', 'Anda sudah mengerjakan tes sumatif ini. Nilai Anda: ' . $sudah_mengerjakan['nilai_akhir']);
        }

        $sessionKey = 'ujian_start_' . $id_ujian;

        if (!session()->has($sessionKey)) {
            session()->set($sessionKey, time());
        }

        $waktu_mulai = session()->get($sessionKey);
        $waktu_habis = $waktu_mulai + ($ujian['durasi_menit'] * 60);
        $sisa_waktu = $waktu_habis - time();

        if ($sisa_waktu < 0) {
            $sisa_waktu = 0;
        }

        $data = [
            'title' => 'Kerjakan: ' . $ujian['judul_ujian'],
            'active_menu' => 'ujian',
            'user' => $this->userModel->find(session()->get('id')),
            'ujian' => $ujian,
            'soal' => $this->soalModel->where('idUjian', $id_ujian)->findAll(),
            'sisa_waktu' => $sisa_waktu
        ];

        return view('siswa/ujian/kerjakan', $data);
    }

    public function submit(string $id_ujian)
    {
        session()->remove('ujian_start_' . $id_ujian);
        $ujian = $this->ujianModel->find($id_ujian);
        $soal_ujian = $this->soalModel->where('idUjian', $id_ujian)->findAll();
        $jawaban_siswa = $this->request->getPost('jawaban');
        $jml_benar = 0;
        $jml_salah = 0;
        $total_soal = count($soal_ujian);

        if ($total_soal == 0) {
            return redirect()->to('siswa/dashboard')->with('errors', ['Tes sumatif ini belum memiliki soal.']);
        }

        foreach ($soal_ujian as $soal) {
            $id_soal = $soal['idSoal'];

            if (isset($jawaban_siswa[$id_soal]) && $jawaban_siswa[$id_soal] == $soal['kunci_jawaban']) {
                $jml_benar++;
            } else {
                $jml_salah++;
            }
        }

        $nilai_akhir = ($jml_benar / $total_soal) * 100;

        $this->nilaiModel->insert([
            'idUjian' => $id_ujian,
            'idUser' => session()->get('id'),
            'jml_benar' => $jml_benar,
            'jml_salah' => $jml_salah,
            'nilai_akhir' => $nilai_akhir
        ]);

        return redirect()->to('siswa/ujian')
            ->with('popup_nilai', $nilai_akhir)
            ->with('popup_judul', $ujian['judul_ujian']);
    }
}