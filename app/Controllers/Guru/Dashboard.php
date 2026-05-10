<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MateriModel;
use App\Models\UjianModel;
use App\Libraries\ProxmoxAPI;

class Dashboard extends BaseController
{
    /** 
     * Memberitahu Intelephense tipe data property ini
     * @var UserModel 
     */
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $idGuru = session()->get('id');
        $dataGuru = $this->userModel->find($idGuru);
        $materiModel = new MateriModel();
        $ujianModel = new UjianModel();
        $db = \Config\Database::connect();
        $api = new ProxmoxAPI();

        $vms_from_db = $db->table('vlab_ct')
            ->select('vlab_ct.vmid, MAX(vlab_ct.nama_os) as nama_os, MAX(users.nama_lengkap) as nama_siswa, MAX(users.kelas) as kelas, MAX(vlab_ct.idVlabCT) as latest_id')
            ->join('users', 'users.idUser = vlab_ct.idUser', 'left')
            ->where('vlab_ct.vmid !=', 0)
            ->groupBy('vlab_ct.vmid')
            ->orderBy('latest_id', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $node_name = 'vlab';
        $active_vms_realtime = [];

        foreach ($vms_from_db as $vm) {
            $vmid = $vm['vmid'];
            $real_status = 'stopped';
            $real_ip = 'DHCP';

            if (!empty($vmid)) {
                $res_status = $api->request("/nodes/{$node_name}/lxc/{$vmid}/status/current");
                if (!isset($res_status['error']) && isset($res_status['data']['status'])) {
                    $real_status = $res_status['data']['status'];
                }

                if ($real_status === 'running') {
                    $res_interfaces = $api->request("/nodes/{$node_name}/lxc/{$vmid}/interfaces");

                    if (!isset($res_interfaces['error']) && !empty($res_interfaces['data'])) {
                        foreach ($res_interfaces['data'] as $iface) {
                            if ($iface['name'] === 'eth0' || strpos($iface['name'], 'eth') !== false) {
                                if (isset($iface['inet'])) {
                                    $ip_parts = explode('/', $iface['inet']);
                                    $real_ip = $ip_parts[0];
                                    break;
                                }
                            }
                        }
                    }
                }

                if ($real_ip === 'DHCP' || $real_status === 'stopped') {
                    $res_config = $api->request("/nodes/{$node_name}/lxc/{$vmid}/config");
                    if (!isset($res_config['error']) && isset($res_config['data']['net0'])) {
                        $net0 = $res_config['data']['net0'];
                        if (preg_match('/ip=([0-9\.]+)/', $net0, $matches)) {
                            $real_ip = $matches[1];
                        }
                    }
                }
            }

            $vm['status'] = $real_status;
            $vm['ip_address'] = $real_ip;
            $active_vms_realtime[] = $vm;
        }

        $query_total_vms = $db->table('vlab_ct')
            ->select('vmid')
            ->where('vmid !=', 0)
            ->groupBy('vmid')
            ->get();

        $data = [
            'title' => 'Dashboard',
            'active_menu' => 'dashboard',
            'admin' => $dataGuru,
            'total_siswa' => $this->userModel->where('role', 'siswa')->countAllResults(),
            'total_materi' => $materiModel->countAllResults(),
            'total_tes' => $ujianModel->countAllResults(),
            'total_vms' => $query_total_vms->getNumRows(),
            'active_vms' => $active_vms_realtime
        ];

        return view('guru/dashboard', $data);
    }

    public function update_profile()
    {
        $userModel = new UserModel();
        $id_user = session()->get('id');

        $userSaatIni = $userModel->find($id_user);

        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama Lengkap wajib diisi.',
                    'min_length' => 'Nama Lengkap minimal 3 karakter.'
                ]
            ],
            'username' => [
                'rules' => "required|min_length[4]|is_unique[users.username,idUser,{$id_user}]",
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'min_length' => 'Username minimal 4 karakter.',
                    'is_unique' => 'Username tersebut sudah dipakai. Silakan pilih yang lain.'
                ]
            ],
            'email' => [
                'rules' => "required|valid_email|is_unique[users.email,idUser,{$id_user}]",
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email tersebut sudah terdaftar di akun lain.'
                ]
            ],
            'password_lama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password Lama wajib diisi untuk verifikasi keamanan.'
                ]
            ],
            'password_baru' => [
                'rules' => 'permit_empty|min_length[8]',
                'errors' => [
                    'min_length' => 'Password Baru harus memiliki minimal 8 karakter.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $passwordLamaInput = (string) $this->request->getPost('password_lama');
        $passwordBaruInput = (string) $this->request->getPost('password_baru');

        if (!password_verify($passwordLamaInput, (string) $userSaatIni['password'])) {
            return redirect()->back()->with('errors', ['password_lama' => 'Verifikasi Gagal, Password lama yang Anda masukkan SALAH.']);
        }

        $nama_lengkap = (string) $this->request->getPost('nama_lengkap');
        $username = (string) $this->request->getPost('username');
        $email = (string) $this->request->getPost('email');

        $dataUpdate = [
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'email' => $email,
        ];

        if (!empty($passwordBaruInput)) {
            $dataUpdate['password'] = password_hash($passwordBaruInput, PASSWORD_DEFAULT);
        }

        $userModel->update($id_user, $dataUpdate);

        session()->set([
            'nama' => $nama_lengkap,
            'username' => $username,
            'email' => $email
        ]);

        return redirect()->to('guru/dashboard')->with('success', 'Profil berhasil diperbarui!');
    }
}