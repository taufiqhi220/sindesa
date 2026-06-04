<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Warga
        User::updateOrCreate(
            ['email' => 'warga@desabuttusawe.id'], 
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'warga',
                'status' => 'active', // <-- Diaktifkan
                'jenis_kelamin' => 'Laki-laki',
                'nik' => '7371123456780001',
                'tempat_lahir' => 'Pinrang',
                'tanggal_lahir' => '1990-01-01',
                'alamat_lengkap' => 'Dusun 1, Desa Buttu Sawe'
            ]
        );

        // 2. Akun Kades
        User::updateOrCreate(
            ['email' => 'kades@desabuttusawe.id'],
            [
                'name' => 'Bapak Kepala Desa',
                'password' => Hash::make('password123'),
                'role' => 'kades',
                'status' => 'active', // <-- Diaktifkan
                'jenis_kelamin' => 'Laki-laki',
                'nik' => '7371123456780002',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '1980-05-15',
                'alamat_lengkap' => 'Kantor Desa Buttu Sawe'
            ]
        );

        // 3. Akun Operator
        User::updateOrCreate(
            ['email' => 'operator@desabuttusawe.id'],
            [
                'name' => 'Staf Pelayanan',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'status' => 'active', // <-- Diaktifkan
                'jenis_kelamin' => 'Perempuan',
                'nik' => '7371123456780003',
                'tempat_lahir' => 'Pinrang',
                'tanggal_lahir' => '1995-08-20',
                'alamat_lengkap' => 'Kantor Desa Buttu Sawe'
            ]
        );

        // 4. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@desabuttusawe.id'],
            [
                'name' => 'Taufiq Hidayat',
                'password' => Hash::make('Admin@Sindesa2026'),
                'role' => 'admin',
                'status' => 'active', // <-- Diaktifkan
                'jenis_kelamin' => 'Laki-laki',
                'nik' => '7371123456780004',
                'tempat_lahir' => 'Pinrang',
                'tanggal_lahir' => '1998-12-10',
                'alamat_lengkap' => 'Kantor Desa Buttu Sawe'
            ]
        );
        // 5. Data Pengaturan Kop Surat Default
        \Illuminate\Support\Facades\DB::table('pengaturan_surats')->updateOrInsert(
            ['id' => 1],
            [
                'header_1' => 'PEMERINTAH KABUPATEN PINRANG',
                'header_2' => 'KECAMATAN DUAMPANUA',
                'nama_desa' => 'DESA BUTTU SAWE',
                'alamat' => 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253',
                'logo_path' => null, // Biarkan null, nanti akan fallback ke logo default di view
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Panggil seeder wilayah dan jenis surat
        $this->call([
            ManualIndonesiaSeeder::class,
            JenisSuratSeeder::class,
        ]);
    }
}