<?php
namespace App\Services;

use App\Models\PengajuanSurat;
use Carbon\Carbon;

class LaporanService
{
    public function getRekapKategori($startDate, $endDate)
    {
        $query = PengajuanSurat::where('status', 'selesai')
            ->whereBetween('updated_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        return $query->get()->groupBy(fn($item) => $this->mapKategori($item->jenis_surat));
    }

    private function mapKategori($jenis)
    {
        $mapping = [
            'Adminduk'  => [
                'pengantar_ktp', 'pengantar_kk', 'pengantar_akta_lahir',
                'keterangan_pindah', 'keterangan_domisili',
                'keterangan_beda_nama', 'keterangan_kematian',
            ],
            'Sosial'    => [
                'keterangan_tidak_mampu', 'keterangan_penghasilan',
                'keterangan_belum_menikah', 'keterangan_janda_duda',
            ],
            'Perizinan' => [
                'keterangan_usaha', 'izin_keramaian',
                'keterangan_kehilangan', 'pengantar_skck',
            ],
        ];

        foreach ($mapping as $kategori => $daftar) {
            if (in_array($jenis, $daftar)) return $kategori;
        }

        return 'Umum';
    }
}
