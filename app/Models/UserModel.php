<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'idUser';

    protected $allowedFields = [
        'nama_lengkap',
        'email',
        'kelas',
        'no_absen',
        'username',
        'password',
        'role',
        'google_id',
        'reset_token',
        'reset_expires',
        'last_active'
    ];

    protected $useTimestamps = false;
}