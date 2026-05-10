<?php

namespace App\Controllers;

use App\Models\UserModel;
use Google_Client;
use GuzzleHttp\Client;

class Auth extends BaseController
{
    // Menampilkan halaman login atau mengarahkan pengguna yang sudah login ke dashboard masing-masing.
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') == 'guru' ? 'guru/dashboard' : 'siswa/dashboard');
        }

        $googleClient = new Google_Client();
        $httpClient = new Client(['verify' => false]);

        $googleClient->setHttpClient($httpClient);
        $googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $googleClient->setRedirectUri(getenv('GOOGLE_REDIRECT_URL'));
        $googleClient->addScope('email');
        $googleClient->addScope('profile');

        $data['googleButton'] = $googleClient->createAuthUrl();

        return view('auth/login', $data);
    }

    // Menampilkan halaman formulir registrasi untuk siswa baru.
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->back();
        }
        return view('auth/register');
    }

    // Memvalidasi dan memproses penyimpanan data registrasi siswa baru ke database.
    public function process_register()
    {
        $userModel = new UserModel();

        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]',
                'errors' => ['required' => 'Nama Lengkap wajib diisi.', 'min_length' => 'Minimal 3 karakter.']
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => ['required' => 'Email wajib diisi.', 'valid_email' => 'Format email salah.', 'is_unique' => 'Email sudah terdaftar.']
            ],
            'kelas' => ['rules' => 'required', 'errors' => ['required' => 'Kelas wajib diisi.']],
            'no_absen' => ['rules' => 'required', 'errors' => ['required' => 'No Absen wajib diisi.']],
            'username' => [
                'rules' => 'required|min_length[4]|is_unique[users.username]',
                'errors' => ['required' => 'Username wajib diisi.', 'min_length' => 'Minimal 4 karakter.', 'is_unique' => 'Username sudah dipakai.']
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => ['required' => 'Password wajib diisi.', 'min_length' => 'Minimal 8 karakter.']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel->save([
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'email' => $this->request->getVar('email'),
            'kelas' => $this->request->getVar('kelas'),
            'no_absen' => $this->request->getVar('no_absen'),
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role' => 'siswa'
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Memverifikasi kredensial login (username/email dan password) untuk masuk ke sistem.
    public function process_login()
    {
        $userModel = new UserModel();
        $loginId = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');

        $user = $userModel->where('username', $loginId)->orWhere('email', $loginId)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return redirect()->to($user['role'] == 'guru' ? 'guru/dashboard' : 'siswa/dashboard');
            } else {
                return redirect()->to('login')->withInput()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->to('login')->withInput()->with('error', 'Akun tidak ditemukan.');
        }
    }

    // Menangani respons (callback) dari Google OAuth untuk proses login atau registrasi otomatis.
    public function google_callback()
    {
        $googleClient = new Google_Client();
        $httpClient = new Client(['verify' => false]);

        $googleClient->setHttpClient($httpClient);
        $googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $googleClient->setRedirectUri(getenv('GOOGLE_REDIRECT_URL'));

        $code = $this->request->getVar('code');

        if ($code) {
            $token = $googleClient->fetchAccessTokenWithAuthCode($code);

            if (!isset($token['error'])) {
                $googleClient->setAccessToken($token['access_token']);
                $googleService = new \Google_Service_Oauth2($googleClient);
                $data = $googleService->userinfo->get();

                $email = $data['email'];
                $google_id = $data['id'];
                $name = $data['name'];

                $userModel = new UserModel();
                $user = $userModel->where('email', $email)->first();

                if ($user) {
                    if (empty($user['google_id'])) {
                        $userModel->update($user['idUser'], ['google_id' => $google_id]);
                    }
                    $this->setUserSession($user);
                    return redirect()->to($user['role'] == 'guru' ? 'guru/dashboard' : 'siswa/dashboard');
                } else {
                    $newUser = [
                        'nama_lengkap' => $name,
                        'email' => $email,
                        'username' => explode('@', $email)[0] . rand(100, 999),
                        'password' => null,
                        'role' => 'siswa',
                        'kelas' => 'Umum',
                        'no_absen' => 0,
                        'google_id' => $google_id
                    ];
                    $userModel->insert($newUser);

                    $userInfo = $userModel->where('email', $email)->first();
                    $this->setUserSession($userInfo);

                    return redirect()->to('siswa/dashboard')->with('success', 'Login Google Berhasil!');
                }
            }
        }
        return redirect()->to('/login')->with('error', 'Gagal login dengan Google.');
    }

    // Menyimpan data identitas pengguna ke dalam sesi (session) setelah proses otentikasi berhasil.
    private function setUserSession(array $user)
    {
        $sessionData = [
            'id' => $user['idUser'],
            'nama' => $user['nama_lengkap'],
            'username' => $user['username'],
            'role' => $user['role'],
            'isLoggedIn' => true
        ];
        session()->set($sessionData);
    }

    // Menampilkan halaman formulir untuk meminta tautan pemulihan (reset) password.
    public function forgot_password()
    {
        return view('auth/lupa_password');
    }

    // Memproses permintaan pemulihan password dan mengirimkan tautan token unik ke email pengguna.
    public function process_forgot()
    {
        $email = $this->request->getVar('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            $userModel->update($user['idUser'], [
                'reset_token' => $token,
                'reset_expires' => $expires
            ]);

            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Reset Password - V-Lab ASJ');

            $link = base_url("auth/reset_password/$token");
            $message = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; }
                    .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                    .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 20px; margin-bottom: 20px; }
                    .brand { font-size: 24px; font-weight: bold; color: #0d6efd; text-decoration: none; }
                    .btn { display: inline-block; background-color: #0d6efd; color: #ffffff !important; padding: 12px 25px; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
                    .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <a href="#" class="brand">IT Support V-Lab ASJ</a>
                    </div>
                    <p>Halo, <strong>' . $user['nama_lengkap'] . '</strong></p>
                    <p>Kami menerima permintaan untuk mereset password akun Anda di <strong> V-Lab ASJ</strong>.</p>
                    <p>Jika ini benar Anda, silakan klik tombol di bawah ini untuk membuat password baru:</p>
                    <div style="text-align: center;">
                        <a href="' . $link . '" class="btn">Reset Password Saya</a>
                    </div>
                    <p style="margin-top: 30px;">Atau salin link berikut ke browser Anda:</p>
                    <p><a href="' . $link . '">' . $link . '</a></p>
                    <p><em>Link ini hanya berlaku selama 5 menit.</em></p>
                    <div class="footer">
                        <p>Jika Anda tidak merasa meminta reset password, silakan abaikan email ini.<br>
                        &copy; ' . date('Y') . ' IT Support V-Lab ASJ</p>
                    </div>
                </div>
            </body>
            </html>
            ';

            $emailService->setMessage($message);

            if ($emailService->send()) {
                return redirect()->back()->with('success', 'Silahkan Cek Email Anda.');
            } else {
                return redirect()->back()->with('error', 'Gagal kirim email. Cek koneksi/setting.');
            }
        } else {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }
    }

    // Menampilkan formulir pembuatan password baru jika token pemulihan masih valid.
    public function reset_password(string $token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_expires >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Link reset password sudah tidak berlaku.');
        }

        return view('auth/lupa_password', ['token' => $token]);
    }

    // Memproses dan menyimpan pembaruan password pengguna ke database.
    public function process_reset()
    {
        $token = $this->request->getVar('token');
        $password = $this->request->getVar('password');
        $confPassword = $this->request->getVar('conf_password');

        if (strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password baru minimal harus 8 karakter!');
        }

        if ($password != $confPassword) {
            return redirect()->back()->with('error', 'Password tidak cocok.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        if ($user) {
            $userModel->update($user['idUser'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expires' => null
            ]);
            return redirect()->to('/login')->with('success', 'Password berhasil direset!');
        }

        return redirect()->to('/login')->with('error', 'Token invalid.');
    }

    // Menghapus sesi login aktif dan mencatat waktu aktivitas terakhir pengguna.
    public function logout()
    {
        $session = session();
        $userId = $session->get('id');

        if ($userId) {
            $userModel = new UserModel();
            $userModel->update($userId, [
                'last_active' => null
            ]);
        }

        $session->remove(['id', 'nama', 'username', 'role', 'isLoggedIn']);
        $session->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}