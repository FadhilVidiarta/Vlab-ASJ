<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. GERBANG DEPAN: Jika user tidak punya sesi login, 
        // langsung tendang ke halaman login SEBELUM masuk ke sistem!
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 2. ANTI-CACHE GLOBAL: 
        // Memaksa seluruh browser untuk membuang ingatan halaman ini
        // agar tombol "Back" tidak akan pernah berfungsi setelah logout.
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');
    }
}