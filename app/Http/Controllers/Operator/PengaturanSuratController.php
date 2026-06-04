<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanSuratController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSurat::first();

        // Jika belum ada data sama sekali, buat data kosong agar tidak error di view
        if (!$pengaturan) {
            $pengaturan = new PengaturanSurat();
        }

        return view('operator.pengaturan-surat', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'header_1' => 'required|string|max:255',
            'header_2' => 'required|string|max:255',
            'nama_desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $pengaturan = PengaturanSurat::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanSurat();
        }

        $pengaturan->header_1 = $request->header_1;
        $pengaturan->header_2 = $request->header_2;
        $pengaturan->nama_desa = $request->nama_desa;
        $pengaturan->alamat = $request->alamat;

        // Jika ada file logo yang diupload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($pengaturan->logo_path && Storage::disk('public')->exists($pengaturan->logo_path)) {
                Storage::disk('public')->delete($pengaturan->logo_path);
            }
            
            // Simpan logo baru
            $path = $request->file('logo')->store('kop_surat', 'public');
            $pengaturan->logo_path = $path;
        }

        $pengaturan->save();

        return redirect()->route('operator.pengaturan-surat')->with('success', 'Kop Surat berhasil diperbarui.');
    }
}