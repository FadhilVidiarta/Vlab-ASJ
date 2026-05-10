<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table = 'ujian';
    protected $primaryKey = 'idUjian';
    protected $allowedFields = ['judul_ujian', 'deskripsi', 'durasi_menit', 'status'];
    protected $useTimestamps = true;
}