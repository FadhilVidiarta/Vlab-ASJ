<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class VlabApi extends BaseController
{
    public function lapor_progres()
    {
        $db = \Config\Database::connect();

        $hostname = $this->request->getPost('hostname');
        $id_modul = $this->request->getPost('id_modul');
        $nilai_progres = (int) $this->request->getPost('progres');
        $vmid = $this->request->getPost('vmid');

        $parts = explode('-', (string) $hostname);
        $id_siswa = $parts[1] ?? 0;
        $nama_os = $parts[2] ?? 'unknown';

        if ($id_siswa > 0) {
            $builder = $db->table('vlab_ct');

            $logExist = $builder->where(['idUser' => $id_siswa, 'idMateri' => $id_modul])
                ->orderBy('idVlabCT', 'DESC')
                ->get()
                ->getRow();

            if ($logExist) {
                if ($nilai_progres <= (int) $logExist->progres) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'pesan' => 'Progres tetap (Nilai saat ini ' . $logExist->progres . '%)'
                    ]);
                }

                $dataUpdate = [
                    'progres' => $nilai_progres,
                    'status_praktikum' => ($nilai_progres >= 100) ? 'Selesai' : 'Proses'
                ];

                if ($nilai_progres >= 100 && empty($logExist->waktu_selesai)) {
                    $dataUpdate['waktu_selesai'] = date('Y-m-d H:i:s');
                }

                $builder->where('idVlabCT', $logExist->idVlabCT)->update($dataUpdate);

                return $this->response->setJSON([
                    'status' => 'success',
                    'pesan' => 'Progres diperbarui ke ' . $nilai_progres . '%'
                ]);

            } else {
                $builder->insert([
                    'idUser' => $id_siswa,
                    'idMateri' => $id_modul,
                    'vmid' => $vmid,
                    'nama_os' => $nama_os,
                    'progres' => $nilai_progres,
                    'waktu_mulai' => date('Y-m-d H:i:s'),
                    'waktu_selesai' => ($nilai_progres >= 100) ? date('Y-m-d H:i:s') : null,
                    'status_praktikum' => ($nilai_progres >= 100) ? 'Selesai' : 'Proses'
                ]);

                return $this->response->setJSON([
                    'status' => 'success',
                    'pesan' => 'Log baru dibuat: ' . $nilai_progres . '%'
                ]);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'pesan' => 'Data tidak valid']);
    }
}