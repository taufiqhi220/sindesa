<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder khusus untuk Production / Hosting.
 * 
 * Hanya membuat 1 akun Admin default + data wilayah Indonesia (Laravolt).
 * Akun Warga, Operator, Kades TIDAK dibuat (akan ditambahkan manual oleh Admin melalui panel).
 * 
 * Cara pakai di server:
 *   php artisan db:seed --class=ProductionSeeder
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. AKUN ADMIN (Satu-satunya akun bawaan sistem)
        //    Password dan email bisa diganti nanti dari panel Admin.
        // ============================================================
        User::updateOrCreate(
            ['email' => 'admin@desabuttusawe.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin@Sindesa2026'),
                'role' => 'admin',
                'status' => 'active',
                'jenis_kelamin' => 'Laki-laki',
                'nik' => '0000000000000000',
                'tempat_lahir' => '-',
                'tanggal_lahir' => '2000-01-01',
                'alamat_lengkap' => 'Kantor Desa Buttu Sawe',
            ]
        );

        $this->command->info('✅ Akun Admin berhasil dibuat.');
        $this->command->info('   Email    : admin@desabuttusawe.id');
        $this->command->info('   Password : Admin@Sindesa2026');
        $this->command->warn('⚠️  SEGERA ganti password setelah login pertama!');

        // ============================================================
        // 2. DATA WILAYAH INDONESIA (Laravolt)
        //    Berisi Provinsi, Kabupaten/Kota, Kecamatan, Desa/Kelurahan
        //    Data ini dibutuhkan untuk dropdown di form pengajuan surat.
        // ============================================================
        $this->call(ManualIndonesiaSeeder::class);

        // ============================================================
        // 3. DATA JENIS SURAT (Master data 15 jenis surat)
        //    Dibutuhkan untuk toggle aktif/nonaktif di Pengaturan Surat.
        // ============================================================
        $this->call(JenisSuratSeeder::class);

        // ============================================================
        // 4. KOP SURAT DEFAULT
        // ============================================================
        \Illuminate\Support\Facades\DB::table('pengaturan_surats')->updateOrInsert(
            ['id' => 1],
            [
                'header_1' => 'PEMERINTAH KABUPATEN PINRANG',
                'header_2' => 'KECAMATAN DUAMPANUA',
                'nama_desa' => 'DESA BUTTU SAWE',
                'alamat' => 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253',
                'logo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('');
        $this->command->info('🎉 Production seeding selesai!');
        $this->command->info('   Langkah selanjutnya:');
        $this->command->info('   1. Login sebagai Admin');
        $this->command->info('   2. Buat akun Operator dari menu Kelola Operator');
        $this->command->info('   3. Buat akun Kepala Desa dari menu Kelola Kades');
        $this->command->info('   4. Atur KOP Surat dari menu Pengaturan Surat');
    }
}
