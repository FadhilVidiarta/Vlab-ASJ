<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MateriModel;
use App\Models\UjianModel;
use App\Models\NilaiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $materiModel = new MateriModel();
        $ujianModel = new UjianModel();
        $nilaiModel = new NilaiModel();

        $userId = session()->get('id');
        $userData = $userModel->find($userId);

        if (!$userData) {
            return redirect()->to('auth/logout');
        }

        $namaLengkap = (string) ($userData['nama_lengkap'] ?? 'Siswa');
        $namaDepan = explode(' ', $namaLengkap)[0];

        $isProfileIncomplete = empty($userData['kelas']) || empty($userData['no_absen']) || $userData['kelas'] == 'Umum' || empty($userData['password']);

        $materiTerbaru = $materiModel->where('status', 'aktif')->orderBy('idMateri', 'DESC')->limit(3)->findAll();
        $ujianAktif = $ujianModel->where('status', 'aktif')->orderBy('created_at', 'DESC')->limit(3)->findAll();

        $nilaiSiswa = $nilaiModel->where('idUser', $userId)->findAll();
        $nilaiMap = [];

        foreach ($nilaiSiswa as $n) {
            $nilaiMap[$n['idUjian']] = $n['nilai_akhir'];
        }

        $ujianAktifFinal = [];
        foreach ($ujianAktif as $u) {
            if (array_key_exists($u['idUjian'], $nilaiMap)) {
                $u['sudah_dikerjakan'] = true;
                $u['nilai'] = $nilaiMap[$u['idUjian']];
            } else {
                $u['sudah_dikerjakan'] = false;
            }
            $ujianAktifFinal[] = $u;
        }

        $data = [
            'title' => 'Beranda Siswa',
            'page_title' => 'Selamat Datang, ' . $namaDepan . '!',
            'user' => $userData,
            'nama_depan' => $namaDepan,
            'active_menu' => 'beranda',
            'show_popup' => $isProfileIncomplete,
            'materiTerbaru' => $materiTerbaru,
            'ujianAktif' => $ujianAktifFinal,
            'nilaiMap' => $nilaiMap
        ];

        return view('siswa/dashboard', $data);
    }

    public function update_profile()
    {
        $userModel = new UserModel();
        $idUser = session()->get('id');
        $userSaatIni = $userModel->find($idUser);
        $isPopup = $this->request->getPost('from_popup');

        // LENGKAPI PROFIL (Google Login Pertama Kali)
        if ($isPopup == '1') {
            $rulesPopup = [
                'nama_lengkap' => 'required|min_length[3]',
                'kelas' => 'required',
                'no_absen' => 'required|numeric|less_than_equal_to[50]|greater_than[0]',
                'password' => 'required|min_length[8]'
            ];

            if (!$this->validate($rulesPopup)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $dataLengkapi = [
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'kelas' => $this->request->getPost('kelas'),
                'no_absen' => $this->request->getPost('no_absen'),
                'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT)
            ];

            $userModel->update($idUser, $dataLengkapi);
            session()->set('nama', $dataLengkapi['nama_lengkap']);

            return redirect()->to('siswa/dashboard')->with('success', 'Profil berhasil dilengkapi!');
        }

        // EDIT PROFIL BIASA
        else {
            $rules = [
                'username' => [
                    'rules' => "required|min_length[4]|is_unique[users.username,idUser,{$idUser}]",
                    'errors' => [
                        'required' => 'Username wajib diisi.',
                        'min_length' => 'Username minimal 4 karakter.',
                        'is_unique' => 'Username tersebut sudah dipakai siswa lain.'
                    ]
                ],
                'password_lama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password lama wajib diisi untuk verifikasi keamanan.'
                    ]
                ],
                'password_baru' => [
                    'rules' => 'permit_empty|min_length[8]',
                    'errors' => [
                        'min_length' => 'Password baru minimal 8 karakter.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Verifikasi Password Lama
            $passwordLamaInput = (string) $this->request->getPost('password_lama');
            if (!password_verify($passwordLamaInput, (string) $userSaatIni['password'])) {
                return redirect()->back()->withInput()->with('errors', ['password_lama' => 'Verifikasi Gagal, Password lama SALAH']);
            }

            $dataUpdate = [
                'username' => (string) $this->request->getPost('username'),
            ];

            // Jika ganti password baru
            $passwordBaruInput = (string) $this->request->getPost('password_baru');
            if (!empty($passwordBaruInput)) {
                $dataUpdate['password'] = password_hash($passwordBaruInput, PASSWORD_DEFAULT);
            }

            $userModel->update($idUser, $dataUpdate);
            session()->set('username', $dataUpdate['username']);

            return redirect()->to('siswa/dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
        }
    }
}