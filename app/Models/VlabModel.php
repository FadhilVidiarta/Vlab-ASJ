<?php

namespace App\Models;

use CodeIgniter\Model;

class VlabModel extends Model
{
    protected $table = 'vlab_ct';
    protected $primaryKey = 'idVlabCT';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'idUser',
        'idMateri',
        'vmid',
        'nama_os',
        'progres',
        'status_praktikum',
        'waktu_mulai',
        'waktu_selesai'
    ];
}