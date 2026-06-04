<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Menu::truncate();
        DB::table('kantor_menu')->truncate();
        Schema::enableForeignKeyConstraints();

        $menus = [
            // Kategori 1
            ['nama' => 'Surat Pengantar KTP', 'route_name' => 'warga.pengajuan.ktp', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Surat Pengantar KK', 'route_name' => 'warga.pengajuan.kk', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Surat Pengantar Akta Lahir', 'route_name' => 'warga.pengajuan.akta-lahir', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Surat Keterangan Kematian', 'route_name' => 'warga.pengajuan.kematian', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Surat Pindah dan Datang', 'route_name' => 'warga.pengajuan.pindah', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Perubahan Data Kependudukan', 'route_name' => 'warga.pengajuan.perubahan-data', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Keterangan Belum Menikah', 'route_name' => 'warga.pengajuan.belum-menikah', 'kategori' => 'Administrasi Kependudukan'],
            ['nama' => 'Keterangan Ahli Waris', 'route_name' => 'warga.pengajuan.ahli-waris', 'kategori' => 'Administrasi Kependudukan'],

            // Kategori 2
            ['nama' => 'Izin Usaha Mikro Kecil', 'route_name' => 'warga.pengajuan.izin-usaha', 'kategori' => 'Layanan Perizinan'],
            ['nama' => 'Surat Izin Tempat Usaha', 'route_name' => 'warga.pengajuan.izin-tempat-usaha', 'kategori' => 'Layanan Perizinan'],

            // Kategori 3
            ['nama' => 'Keterangan Domisili', 'route_name' => 'warga.pengajuan.domisili', 'kategori' => 'Keterangan Umum'],
            ['nama' => 'Pengantar SKCK', 'route_name' => 'warga.pengajuan.skck', 'kategori' => 'Keterangan Umum'],

            // Kategori 4
            ['nama' => 'Keterangan Tidak Mampu', 'route_name' => 'warga.pengajuan.tidak-mampu', 'kategori' => 'Layanan Sosial'],
            ['nama' => 'Izin Keramaian/Kegiatan', 'route_name' => 'warga.pengajuan.izin-keramaian', 'kategori' => 'Layanan Sosial'],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}