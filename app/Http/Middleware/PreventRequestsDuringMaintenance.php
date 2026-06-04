<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    protected $except = [
        '/',              // Home page biarkan lolos agar bisa nampilin pesan perbaikan
        'login',          // Halaman login (GET & POST) tetap buka
        'logout',         // Bisa logout
        'tentang-kami',   // Halaman publik
        'admin/*',        // Akses admin 100% aman
    ];
}