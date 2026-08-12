<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class OperatorSuratController extends Controller
{
    // Fungsi untuk menampilkan halaman detail/review
    public function show($id)
    {
        // KEAMANAN: Operator hanya boleh melihat surat yang statusnya relevan dengan tugasnya
        $surat = PengajuanSurat::with('user')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses_kades', 'ditolak'])
            ->findOrFail($id);
        if (!$surat->is_seen_by_operator) {
            $surat->update(['is_seen_by_operator' => true]);
        }

        $routeMap = [
            'pengantar_akta_lahir'     => 'akta-lahir',
            'pengantar_ktp'            => 'ktp',
            'pengantar_kk'             => 'kk',
            'keterangan_kematian'      => 'kematian',
            'keterangan_pindah'        => 'pindah',
            'keterangan_domisili'      => 'domisili',
            'keterangan_belum_menikah' => 'belum-menikah',
            'keterangan_janda_duda'    => 'janda-duda',
            'keterangan_beda_nama'     => 'beda-nama',
            'keterangan_kehilangan'    => 'kehilangan',
            'pengantar_skck'           => 'skck',
            'keterangan_usaha'         => 'usaha',
            'izin_keramaian'           => 'izin-keramaian',
            'keterangan_tidak_mampu'   => 'sktm',
            'keterangan_penghasilan'   => 'penghasilan',
        ];
        

        $slug = $routeMap[$surat->jenis_surat] ?? str_replace('_', '-', $surat->jenis_surat);
        $viewPath = "operator.verifikasi.details." . $slug;

        if (view()->exists($viewPath)) {
            return view($viewPath, compact('surat'));
        }

        abort(404, 'File tampilan untuk detail surat ini belum dibuat ('.$viewPath.').');
    }

    // Pindahkan fungsi update dari DashboardController ke sini
    public function update(Request $request, $id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        // PROTEKSI: Hanya surat menunggu_verifikasi yang bisa diproses
        if ($surat->status !== 'menunggu_verifikasi') {
            return redirect()->route('operator.verifikasi')
                ->with('error', 'Surat ini tidak dalam status menunggu verifikasi.');
        }

        if ($request->action == 'setujui') {
            $request->validate([
                'nomor_surat' => 'required|string|max:255',
            ]);

            $surat->update([
                'status' => 'diproses_kades',
                'nomor_surat' => $request->nomor_surat,
                'pesan_penolakan' => null, // Bersihkan pesan jika diterima
                'is_seen_by_operator' => true
            ]);

            return redirect()->route('operator.menunggu-ttd')->with('success', 'Surat diteruskan ke Kepala Desa.');
        } 
        
        if ($request->action == 'tolak') {
            $request->validate([
                'pesan_penolakan' => 'required|string|min:5',
            ]);

            $surat->update([
                'status' => 'ditolak',
                'nomor_surat' => null, // Hapus nomor jika ditolak
                'pesan_penolakan' => $request->pesan_penolakan,
                'is_seen_by_operator' => true
            ]);

            return redirect()->route('operator.ditolak')->with('success', 'Permohonan surat telah ditolak.');
        }
    }

    public function tarik($id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        // PROTEKSI: Hanya surat diproses_kades yang bisa ditarik kembali
        if ($surat->status !== 'diproses_kades') {
            return redirect()->route('operator.menunggu-ttd')
                ->with('error', 'Surat ini tidak bisa ditarik kembali.');
        }

        // Kembalikan status ke menunggu_verifikasi agar bisa diedit ulang oleh operator
        $surat->update([
            'status' => 'menunggu_verifikasi',
            'nomor_surat' => null // Opsional: hapus nomor surat jika ingin diinput ulang
        ]);

        return redirect()->route('operator.verifikasi')
            ->with('success', 'Surat berhasil ditarik kembali ke antrean verifikasi.');
    }
}