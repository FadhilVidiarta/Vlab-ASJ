<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'idNilai';
    protected $allowedFields = ['idUjian', 'idUser', 'jml_benar', 'jml_salah', 'nilai_akhir'];

    public function getNilaiLengkap()
    {
        return $this->db->table($this->table)
            ->select($this->table . '.*, users.nama_lengkap AS nama_siswa, ujian.judul_ujian AS judul_materi')
            ->join('users', 'users.idUser = ' . $this->table . '.idUser')
            ->join('ujian', 'ujian.idUjian = ' . $this->table . '.idUjian')
            ->orderBy($this->table . '.idNilai', 'DESC')
            ->get()->getResultArray();
    }
}