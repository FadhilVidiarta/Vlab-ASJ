<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table = 'soal';
    protected $primaryKey = 'idSoal';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'idUjian',
        'pertanyaan',
        'file_gambar',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'kunci_jawaban'
    ];

    protected $useTimestamps = true;
}