<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_lengkap' => 'Fadhil Vidiarta',
                'email' => 'fdhlvidiarta@gmail.com',
                'kelas' => null,
                'no_absen' => null,
                'username' => 'admin_fadhil',
                'password' => password_hash('rahasia123', PASSWORD_DEFAULT),
                'role' => 'guru'
            ],

            [
                'nama_lengkap' => 'Eko Trismianto',
                'email' => 'eko trismianto@smkn1jiwan.sch.id',
                'kelas' => null,
                'no_absen' => null,
                'username' => 'admin_Eko',
                'password' => password_hash('guru12345', PASSWORD_DEFAULT),
                'role' => 'guru'
            ]
        ];

        $this->db->table('users')->insertBatch($data);

        echo "Seeder berhasil! Akun Guru/Admin telah ditambahkan.\n";
    }
}