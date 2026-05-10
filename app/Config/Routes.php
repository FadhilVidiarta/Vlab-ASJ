<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Auth;
use App\Controllers\Guru\Dashboard as GuruDashboard;
use App\Controllers\Guru\DataSiswa as GuruDataSiswa;
use App\Controllers\Guru\Materi as GuruMateri;
use App\Controllers\Guru\Ujian as GuruUjian;
use App\Controllers\Guru\Nilai as GuruNilai;
use App\Controllers\Siswa\Dashboard as SiswaDashboard;
use App\Controllers\Siswa\Materi as SiswaMateri;
use App\Controllers\Siswa\Ujian as SiswaUjian;
use App\Controllers\Siswa\Praktikum as SiswaPraktikum;


/**
 * @var RouteCollection $routes
 */

$routes->get('/', [Home::class, 'index']);

$routes->get('login', [Auth::class, 'index']);
$routes->get('register', [Auth::class, 'register']);
$routes->post('auth/process_login', [Auth::class, 'process_login']);
$routes->post('auth/process_register', [Auth::class, 'process_register']);
$routes->get('auth/logout', [Auth::class, 'logout']);


$routes->get('auth/google_callback', [Auth::class, 'google_callback']);

$routes->get('auth/lupa_password', [Auth::class, 'forgot_password']);
$routes->post('auth/process_forgot', [Auth::class, 'process_forgot']);
$routes->get('auth/reset_password/(:segment)', [Auth::class, 'reset_password']);
$routes->post('auth/process_reset', [Auth::class, 'process_reset']);

// GROUP ROUTE SISWA
$routes->group('siswa', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', [SiswaDashboard::class, 'index']);
    $routes->get('materi/baca/(:num)', [SiswaMateri::class, 'baca']);
    $routes->get('materi', [SiswaMateri::class, 'index']);

    $routes->post('dashboard/update_profile', [SiswaDashboard::class, 'update_profile']);

    $routes->get('ujian', [SiswaUjian::class, 'index']);
    $routes->get('ujian/kerjakan/(:num)', [SiswaUjian::class, 'kerjakan']);
    $routes->post('ujian/submit/(:num)', [SiswaUjian::class, 'submit']);

    $routes->get('praktikum', [SiswaPraktikum::class, 'index']);
    $routes->get('praktikum/mulai/(:num)/(:segment)', [SiswaPraktikum::class, 'mulai']);
    $routes->get('praktikum/akhiri_sesi/(:num)', [SiswaPraktikum::class, 'akhiri_sesi']);

    $routes->get('praktikum/hapus_mesin', [SiswaPraktikum::class, 'hapus_mesin']);
    $routes->get('praktikum/lanjutkan_log/(:num)', [SiswaPraktikum::class, 'lanjutkan_log']);
    $routes->get('praktikum/hapus_riwayat/(:num)', [SiswaPraktikum::class, 'hapus_riwayat']);

    $routes->get('praktikum/keep_alive', [SiswaPraktikum::class, 'keep_alive']);
});

// GROUP ROUTE GURU
$routes->group('guru', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', [GuruDashboard::class, 'index']);

    $routes->get('data_siswa', [GuruDataSiswa::class, 'index']);
    $routes->get('data_siswa/hapus_siswa/(:num)', [GuruDataSiswa::class, 'hapus_siswa']);

    $routes->get('materi', [GuruMateri::class, 'index']);
    $routes->get('materi/tambah', [GuruMateri::class, 'tambah']);
    $routes->post('materi/simpan', [GuruMateri::class, 'simpan']);
    $routes->get('materi/detail/(:num)', [GuruMateri::class, 'detail']);
    $routes->get('materi/edit/(:num)', [GuruMateri::class, 'edit']);
    $routes->post('materi/update/(:num)', [GuruMateri::class, 'update']);
    $routes->get('materi/hapus/(:num)', [GuruMateri::class, 'hapus']);

    $routes->post('dashboard/update_profile', [GuruDashboard::class, 'update_profile']);

    $routes->get('ujian', [GuruUjian::class, 'index']);
    $routes->post('ujian/simpan', [GuruUjian::class, 'simpan']);

    $routes->get('ujian/soal/(:num)', [GuruUjian::class, 'soal']);
    $routes->get('ujian/tambah_soal/(:num)', [GuruUjian::class, 'tambah_soal']);
    $routes->post('ujian/simpan_soal/(:num)', [GuruUjian::class, 'simpan_soal']);
    $routes->get('ujian/hapus_soal/(:num)/(:num)', [GuruUjian::class, 'hapus_soal']);
    $routes->post('ujian/update/(:num)', [GuruUjian::class, 'update']);
    $routes->get('ujian/hapus/(:num)', [GuruUjian::class, 'hapus']);
    $routes->get('ujian/edit_soal/(:num)/(:num)', [GuruUjian::class, 'edit_soal']);
    $routes->post('ujian/update_soal/(:num)/(:num)', [GuruUjian::class, 'update_soal']);

    $routes->get('nilai', [GuruNilai::class, 'index']);

    $routes->get('progres_praktikum', 'Guru\ProgresPraktikum::index');
    $routes->get('hapus_log/(:num)', 'Guru\ProgresPraktikum::hapus_log/$1');

});

// ROUTE API UNTUK MENERIMA LAPORAN DARI DALAM TERMINAL V-LAB
$routes->post('api/vlab/lapor_progres', '\App\Controllers\Api\VlabApi::lapor_progres');