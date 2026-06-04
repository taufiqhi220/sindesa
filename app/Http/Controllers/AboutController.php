<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Data ini yang akan tampil di halaman Tentang Kami SINDESA
        $aboutContents = [
            'paragraph_1' => 'SINDESA adalah solusi digital terpadu yang dirancang untuk mempercepat transformasi pelayanan publik di tingkat desa. Dengan sistem ini, pengelolaan data kependudukan dan administrasi surat-menyurat menjadi lebih transparan dan akuntabel.',
            'paragraph_2' => 'Kami berfokus pada integrasi teknologi informasi untuk mempermudah perangkat desa dalam melayani warga, sekaligus memberikan akses yang lebih luas bagi masyarakat desa untuk mendapatkan layanan birokrasi secara mandiri.',
            'paragraph_3' => 'Dikembangkan dengan standar tata kelola COBIT 2019, SINDESA memastikan setiap proses bisnis di desa berjalan sesuai dengan tujuan strategis pembangunan nasional menuju Desa Digital.'
        ];

        return view('tentang-kami', compact('aboutContents'));
    }
}