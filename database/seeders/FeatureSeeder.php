<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feature::create([
            'title' => 'Administrasi Kependudukan',
            'image' => 'images/model2.jpg',
            'description' => "Pengelolaan Surat Pengantar KTP\nPengelolaan Surat Pengantar KK\nPengelolaan Surat Pengantar Akta Lahir\nPengelolaan Surat Keterangan Kematian\nPengelolaan Surat Pindah dan Datang Antar Wilayah\nPengelolaan Perubahan Data Kependudukan\nPengelolaan Surat Keterangan Belum Menikah\nPengelolaan Surat Keterangan Ahli Waris"
        ]);

        Feature::create([
            'title' => 'Layanan Perizinan',
            'image' => 'images/imgPerijinan.jpg',
            'description' => "Pengelolaan Izin Usaha Mikro Kecil\nPengelolaan Surat Izin Tempat Usaha\nPengelolaan Surat Rekomendasi Usaha"
        ]);

        Feature::create([
            'title' => 'Surat Keterangan Umum',
            'image' => 'images/imgKeteranganUmum.jpg',
            'description' => "Pengelolaan Surat Keterangan Domisili\nPengelolaan Surat Keterangan Tidak Mampu\nPengelolaan Surat Pengantar SKCK"
        ]);

        Feature::create([
            'title' => 'Layanan Sosial',
            'image' => 'images/imgLayananSosial.png',
            'description' => "Pengelolaan Surat Keterangan Tidak Mampu (SKTM)\nPengelolaan Surat Izin Keramaian / Kegiatan Warga"
        ]);

        Feature::create([
            'title' => 'Pemantauan Aset',
            'image' => 'images/imgPemantauanAset.jpg',
            'description' => "Pengelolaan Pendataan Aset\nPengelolaan Pemantauan Aset"
        ]);
    }
}
