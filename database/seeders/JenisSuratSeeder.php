<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surat')->insert([
            // Adm. Kependudukan
            ['id' => 1, 'kode_surat' => 'akta-lahir', 'nama_surat' => 'Surat Pengantar Akta Lahir', 'kategori' => 'Adm. Kependudukan'],
            ['id' => 2, 'kode_surat' => 'ktp', 'nama_surat' => 'Surat Pengantar KTP', 'kategori' => 'Adm. Kependudukan'],
            ['id' => 3, 'kode_surat' => 'kk', 'nama_surat' => 'Surat Pengantar Kartu Keluarga', 'kategori' => 'Adm. Kependudukan'],
            ['id' => 4, 'kode_surat' => 'kematian', 'nama_surat' => 'Surat Keterangan Kematian', 'kategori' => 'Adm. Kependudukan'],
            ['id' => 5, 'kode_surat' => 'pindah', 'nama_surat' => 'Surat Keterangan Pindah', 'kategori' => 'Adm. Kependudukan'],
            
            // Keterangan Umum
            ['id' => 6, 'kode_surat' => 'domisili', 'nama_surat' => 'Surat Keterangan Domisili', 'kategori' => 'Keterangan Umum'],
            ['id' => 7, 'kode_surat' => 'belum-menikah', 'nama_surat' => 'Surat Keterangan Belum Menikah', 'kategori' => 'Keterangan Umum'],
            ['id' => 8, 'kode_surat' => 'janda-duda', 'nama_surat' => 'Surat Keterangan Janda dan Duda', 'kategori' => 'Keterangan Umum'],
            ['id' => 9, 'kode_surat' => 'beda-nama', 'nama_surat' => 'Surat Keterangan Beda Nama', 'kategori' => 'Keterangan Umum'],
            ['id' => 10, 'kode_surat' => 'kehilangan', 'nama_surat' => 'Surat Keterangan Kehilangan', 'kategori' => 'Keterangan Umum'],
            ['id' => 11, 'kode_surat' => 'skck', 'nama_surat' => 'Surat Pengantar SKCK', 'kategori' => 'Keterangan Umum'],
            
            // Layanan Perizinan
            ['id' => 12, 'kode_surat' => 'usaha', 'nama_surat' => 'Surat Keterangan Usaha', 'kategori' => 'Layanan Perizinan'],
            ['id' => 13, 'kode_surat' => 'izin-keramaian', 'nama_surat' => 'Surat Pengantar Izin Keramaian', 'kategori' => 'Layanan Perizinan'],
            
            // Sosial & Ekonomi
            ['id' => 14, 'kode_surat' => 'tidak-mampu', 'nama_surat' => 'Surat Keterangan Tidak Mampu', 'kategori' => 'Sosial & Ekonomi'],
            ['id' => 15, 'kode_surat' => 'penghasilan', 'nama_surat' => 'Surat Keterangan Penghasilan', 'kategori' => 'Sosial & Ekonomi'],
        ]);
    }
}