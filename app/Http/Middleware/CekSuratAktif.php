<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\JenisSurat;

class CekSuratAktif
{
    public function handle(Request $request, Closure $next, $kode)
    {
        $surat = JenisSurat::where('kode_surat', $kode)->first();
        
        if (!$surat || !$surat->is_active) {
            // Lempar kembali ke dashboard jika lewat URL langsung
            return redirect()->route('warga.dashboard')->with('error', 'Layanan surat ini sedang dinonaktifkan oleh Admin.');
        }

        return $next($request);
    }
}