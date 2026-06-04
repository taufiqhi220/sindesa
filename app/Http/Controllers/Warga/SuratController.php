<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\PengaturanSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan library dompdf sudah terinstall

class SuratController extends Controller
{
    /**
     * Menangani proses Cetak (Stream) dan Unduh (Download) PDF
     */
    public function cetakPdf(Request $request, $id)
    {
        // 1. Ambil data surat & pastikan milik user yang login
        $surat = PengajuanSurat::with('user')
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->findOrFail($id);

        // 2. Ambil data pendukung (KOP Surat & Data Kades)
        $pengaturan = PengaturanSurat::first(); 
        
        $dataTambahan = $surat->data_tambahan ?? [];
        if (isset($dataTambahan['kades_snapshot'])) {
            $kades = (object) $dataTambahan['kades_snapshot'];
        } else {
            $kades = User::where('role', 'kades')->where('status', 'active')->first();
        }

        // 3. Tentukan path view secara dinamis berdasarkan jenis_surat
        // Contoh: 'pengantar_akta_lahir' -> 'pdf.akta-lahir'
        $viewPath = 'pdf.' . str_replace(['pengantar_', 'keterangan_', '_'], ['', '', '-'], $surat->jenis_surat);

        // 4. Validasi apakah file template .blade ada
        if (!view()->exists($viewPath)) {
            return back()->with('error', 'Format cetak untuk jenis surat ini belum tersedia.');
        }

        // 5. Generate PDF
        $pdf = Pdf::loadView($viewPath, compact('surat', 'pengaturan', 'kades'));
        
        // Buat nama file yang aman (ganti garis miring nomor surat jadi strip)
        $namaFileAman = 'Surat_' . str_replace(['/', '\\'], '-', $surat->nomor_surat) . '.pdf';

        // 6. Logika: Jika ada parameter ?download=1 maka unduh, jika tidak maka tampilkan saja (print)
        if ($request->has('download')) {
            return $pdf->download($namaFileAman);
        }

        return $pdf->stream($namaFileAman);
    }
}