<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function verifikasiSurat(Request $request, $token)
    {
        // Cari berdasarkan token yang unik, bukan nomor surat
        $surat = PengajuanSurat::with('user')
            ->where('token_verifikasi', $token)
            ->where('status', 'selesai')
            ->first();

        if (!$surat) {
            // Jika tidak ketemu, kirim nilai 'Kosong' agar view tidak error
            return view('public.verifikasi', ['surat' => null, 'token_input' => $token]);
        }
        $dataTambahan = $surat->data_tambahan ?? [];
        if (isset($dataTambahan['kades_snapshot'])) {
            $kades = (object) $dataTambahan['kades_snapshot'];
        } else {
            $kades = User::where('role', 'kades')->where('status', 'active')->first();
        }

        return view('public.verifikasi', compact('surat', 'kades'));
    }
}