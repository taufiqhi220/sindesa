<?php

namespace App\Http\Controllers\Kades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengaturanSurat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Helper untuk merapikan nama surat
    private function formatJenisSurat($jenis)
    {
        $map = [
            'pengantar_akta_lahir' => 'Pengantar Akta Kelahiran',
            'pengantar_ktp' => 'Pengantar KTP',
            'pengantar_kk' => 'Pengantar Kartu Keluarga',
            'keterangan_kematian' => 'Keterangan Kematian',
            'keterangan_pindah' => 'Keterangan Pindah',
            'keterangan_usaha' => 'Keterangan Usaha',
            'izin_keramaian' => 'Pengantar Izin Keramaian',
            'keterangan_tidak_mampu' => 'Keterangan Tidak Mampu (SKTM)',
            'keterangan_penghasilan' => 'Keterangan Penghasilan',
            'keterangan_domisili' => 'Keterangan Domisili',
            'keterangan_belum_menikah' => 'Keterangan Belum Menikah',
            'keterangan_janda_duda' => 'Keterangan Janda / Duda',
            'keterangan_beda_nama' => 'Keterangan Beda Nama',
            'keterangan_kehilangan' => 'Keterangan Kehilangan',
            'pengantar_skck' => 'Pengantar SKCK',
        ];

        return $map[$jenis] ?? ucwords(str_replace('_', ' ', $jenis));
    }

    public function index()
    {
        $countPerluTtd = PengajuanSurat::where('status', 'diproses_kades')->count();
        $countTtdHariIni = PengajuanSurat::where('status', 'selesai')->whereDate('updated_at', Carbon::today())->count();
        $countSuratBulanIni = PengajuanSurat::where('status', 'selesai')->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year)->count();

        $suratTerbaru = PengajuanSurat::with('user')
            ->where('status', 'diproses_kades')
            ->orderBy('updated_at', 'desc')
            ->take(5)->get();

        // Tambahkan nama surat lengkap
        foreach ($suratTerbaru as $surat) {
            $surat->nama_surat_lengkap = $this->formatJenisSurat($surat->jenis_surat);
        }

        return view('kades.dashboard', compact('countPerluTtd', 'countTtdHariIni', 'countSuratBulanIni', 'suratTerbaru'));
    }

    public function perluTtd()
    {
        $surats = PengajuanSurat::with('user')
            ->where('status', 'diproses_kades')
            ->orderBy('updated_at', 'asc') // Paling lama menunggu di atas
            ->get();

        foreach ($surats as $surat) {
            $surat->nama_surat_lengkap = $this->formatJenisSurat($surat->jenis_surat);
        }

        $countPerluTtd = $surats->count();

        return view('kades.perlu-ttd', compact('surats', 'countPerluTtd'));
    }

    public function riwayat(Request $request)
    {
        $countPerluTtd = PengajuanSurat::where('status', 'diproses_kades')->count();
        $query = PengajuanSurat::with('user')->where('status', 'selesai');

        if ($request->has('search') && $request->search != '') {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhere('jenis_surat', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('bulan') && $request->bulan != '') {
            $date = explode('-', $request->bulan);
            $query->whereYear('updated_at', $date[0])->whereMonth('updated_at', $date[1]);
        }

        // PERBAIKAN: Gunakan get() agar data tidak dilimit 10, karena pagination di-handle oleh Javascript
        $riwayatSurat = $query->orderBy('updated_at', 'desc')->get();

        foreach ($riwayatSurat as $surat) {
            $surat->nama_surat_lengkap = $this->formatJenisSurat($surat->jenis_surat);
        }

        return view('kades.riwayat', compact('riwayatSurat', 'countPerluTtd'));
    }

    public function pengaturanAkun()
    {
        $countPerluTtd = PengajuanSurat::where('status', 'diproses_kades')->count();
        return view('kades.pengaturan-akun', compact('countPerluTtd'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . Auth::id(),
            'nip' => 'nullable|string|max:30',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update($request->only('name', 'nik', 'nip', 'email', 'phone'));
        
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateFotoProfil(Request $request)
    {
        $request->validate(['foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => $request->file('foto_profil')->store('profil', 'public')]);
        }
        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updateTtd(Request $request)
    {
        $request->validate(['ttd_image' => 'required|image|mimes:png|max:2048']);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('ttd_image')) {
            if ($user->ttd_path) Storage::disk('public')->delete($user->ttd_path);
            $user->update(['ttd_path' => $request->file('ttd_image')->store('ttd', 'public')]);
        }
        return back()->with('success', 'Spesimen tanda tangan berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak cocok.');
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function showDetailSurat($type, $id)
    {
        if ($type === 'cetak') return $this->cetakPdf($id);

        $surat = PengajuanSurat::with('user')->findOrFail($id);
        
        if (!$surat->is_seen_by_kades) {
            $surat->update(['is_seen_by_kades' => true]);
        }
        
        $viewName = str_replace(['pengantar_', 'keterangan_', '_'], ['', '', '-'], $type);
        $countPerluTtd = PengajuanSurat::where('status', 'diproses_kades')->count();

        if (!view()->exists("kades.details.{$viewName}")) {
            return back()->with('error', "Halaman detail untuk {$type} belum dibuat.");
        }

        return view("kades.details.{$viewName}", compact('surat', 'countPerluTtd'));
    }

    public function prosesSurat(Request $request, $id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        // PROTEKSI: Hanya surat dengan status diproses_kades yang bisa ditandatangani
        if ($surat->status !== 'diproses_kades') {
            return redirect()->route('kades.perlu-ttd')
                ->with('error', 'Surat ini tidak dalam status menunggu tanda tangan.');
        }
        if ($request->status == 'selesai') {
            $dataTambahan = $surat->data_tambahan ?? [];
            $kadesActive = Auth::user();
            $dataTambahan['kades_snapshot'] = [
                'name' => $kadesActive->name,
                'nip' => $kadesActive->nip,
                'ttd_path' => $kadesActive->ttd_path,
            ];

            $surat->update([
                'status' => 'selesai',
                'metode_ttd' => $request->ttd_method,
                'token_verifikasi' => Str::random(40),
                'updated_at' => now(),
                'data_tambahan' => $dataTambahan,
            ]);
            $pesan = "Surat berhasil ditandatangani!";
        } else {
            $surat->update([
                'status' => 'ditolak',
                'pesan_penolakan' => $request->keterangan,
            ]);
            $pesan = "Surat telah ditolak dan dikembalikan.";
        }

        return redirect()->route('kades.perlu-ttd')->with('success', $pesan);
    }

    public function cetakPdf($id)
    {
        $surat = PengajuanSurat::with('user')->findOrFail($id);
        $pengaturan = PengaturanSurat::first(); 
        
        $dataTambahan = $surat->data_tambahan ?? [];
        if (isset($dataTambahan['kades_snapshot'])) {
            $kades = (object) $dataTambahan['kades_snapshot'];
        } else {
            $kades = User::where('role', 'kades')->where('status', 'active')->first();
        }

        $viewName = str_replace(['pengantar_', 'keterangan_', '_'], ['', '', '-'], $surat->jenis_surat);
        $viewPath = 'pdf.' . $viewName;
        
        if (!view()->exists($viewPath)) {
            return back()->with('error', 'Template PDF untuk jenis surat ini belum tersedia.');
        }

        $pdf = Pdf::loadView($viewPath, compact('surat', 'pengaturan', 'kades'));
        $namaFileAman = str_replace(['/', '\\'], '-', $surat->nomor_surat);
        
        return $pdf->stream('Surat_' . $namaFileAman . '.pdf');
    }

    public function cetakLaporan(Request $request)
    {
        // Jika tidak ada bulan yang dipilih, gunakan bulan ini
        $bulan = $request->query('bulan', now()->format('Y-m')); 
        $tahun = date('Y', strtotime($bulan));
        $bulanAngka = date('m', strtotime($bulan));

        // Ambil data surat yang sudah selesai di bulan tersebut
        $suratSelesai = PengajuanSurat::with('user')
            ->where('status', 'selesai')
            ->whereYear('updated_at', $tahun)
            ->whereMonth('updated_at', $bulanAngka)
            ->orderBy('updated_at', 'asc')
            ->get();

        $pengaturan = PengaturanSurat::first(); 
        $kades = User::where('role', 'kades')->where('status', 'active')->first();

        $orientasi = $request->query('orientasi', 'landscape');
        $pdf = Pdf::loadView('kades.laporan-bulanan', compact('suratSelesai', 'bulan', 'pengaturan', 'kades'))
                     ->setPaper('a4', in_array($orientasi, ['portrait', 'landscape']) ? $orientasi : 'landscape');

        return $pdf->stream('Laporan_Surat_Bulan_' . $bulan . '.pdf');
    }

    // Pusat Bantuan Kades
    public function bantuan()
    {
        $countPerluTtd = PengajuanSurat::where('status', 'diproses_kades')->count();
        return view('kades.bantuan', compact('countPerluTtd'));
    }
}