<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriModel extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'idMateri';
    protected $allowedFields = ['idUser', 'judul_materi', 'sub_materi', 'file_pdf', 'deskripsi', 'status'];
    protected $useTimestamps = true;
}