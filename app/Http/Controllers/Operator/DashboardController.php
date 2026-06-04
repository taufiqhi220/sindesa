<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Services\LaporanService;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    protected $laporanService;

    // Tambahkan Constructor untuk Inject Service
    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    public function index()
    {
        $countMasuk = PengajuanSurat::where('status', 'menunggu_verifikasi')->count();
        $countMenungguTtd = PengajuanSurat::where('status', 'diproses_kades')->count();
        $countSelesai = PengajuanSurat::where('status', 'selesai')->count();
        $countDitolak = PengajuanSurat::where('status', 'ditolak')->count();
        $suratTerbaru = PengajuanSurat::with('user')->where('status', 'menunggu_verifikasi')->latest()->take(5)->get();

        return view('operator.dashboard', compact('countMasuk', 'countMenungguTtd', 'countSelesai', 'countDitolak', 'suratTerbaru'));
    }

    public function verifikasi()
    {
        $pengajuans = PengajuanSurat::with('user')->where('status', 'menunggu_verifikasi')->latest()->get();
        return view('operator.verifikasi', compact('pengajuans'));
    }

    public function menungguTtd(Request $request)
    {
        $query = PengajuanSurat::with('user')->where('status', 'diproses_kades')->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                })->orWhere('jenis_surat', 'like', "%{$search}%");
            });
        }

        $surats = $query->get();
        return view('operator.menunggu-ttd', compact('surats'));
    }

    // UPDATE METHOD RIWAYAT
    public function riwayat(Request $request)
    {
        // 1. Logika Filter Tanggal
        $start = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));

        // 2. Ambil Laporan (Data Terkelompok/Rekap) via Service
        $rekap = $this->laporanService->getRekapKategori($start, $end);

        // 3. Ambil Riwayat (Daftar Detail) dengan filter tanggal yang sama
        $surats = PengajuanSurat::with('user')
                    ->where('status', 'selesai')
                    ->whereBetween('updated_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('operator.riwayat', compact('surats', 'rekap', 'start', 'end'));
    }

    public function cetakLaporan(Request $request)
    {
        // 1. Ambil filter tanggal
        $start = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));

        // 2. Ambil data terkelompok dari Service
        $rekap = $this->laporanService->getRekapKategori($start, $end);

        // 3. Catat ke Log Aktivitas (Audit Trail COBIT 2019)
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => "Mencetak Laporan Rekapitulasi Surat periode {$start} s/d {$end}",
            'ip_address' => $request->ip()
        ]);

        // 4. Load view dan generate PDF
        $pdf = Pdf::loadView('operator.riwayat_pdf', [
            'rekap' => $rekap,
            'start' => $start,
            'end'   => $end,
            'tanggal_cetak' => now()->format('d F Y')
        ]);

        // 5. Tampilkan PDF (Preview)
        return $pdf->stream('Laporan_SINDESA_' . $start . '_to_' . $end . '.pdf');
    }

    public function ditolak()
    {
        $surats = PengajuanSurat::with('user')->where('status', 'ditolak')->orderBy('updated_at', 'desc')->get();
        return view('operator.ditolak', compact('surats'));
    }

    public function pengaturanSurat() { return view('operator.pengaturan-surat'); }
    public function pengaturanAkun() { return view('operator.pengaturan-akun'); }
}