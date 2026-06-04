<?php

namespace App\Http\Controllers;

use App\Services\LaporanService; // Import Service
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    protected $laporanService;

    // Dependency Injection dilakukan di Constructor
    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    public function index(Request $request)
    {
        // Default rentang waktu (misal: 30 hari terakhir)
        $start = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $end = $request->get('end_date', now()->format('Y-m-d'));

        // Memanggil fungsi dari Service
        $rekap = $this->laporanService->getRekapKategori($start, $end);

        return view('operator.riwayat.index', [
            'data' => $rekap,
            'start' => $start,
            'end' => $end
        ]);
    }
}