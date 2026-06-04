<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class PrepareHosting extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:prepare-hosting 
                            {--force : Jalankan tanpa konfirmasi}';

    /**
     * The console command description.
     */
    protected $description = 'Bersihkan database & file upload agar siap untuk hosting (production). Hanya akun Admin yang tersisa.';

    /**
     * Folder-folder upload yang akan dikosongkan isinya.
     * Path relatif terhadap storage/app/public
     */
    protected array $uploadFolders = [
        'pengajuan',
        'foto_ktp_warga',
        'profil',
        'ttd',
        'ttd_kades',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->newLine();
        $this->components->warn('⚠️  PERINGATAN: Perintah ini akan menghapus SEMUA data!');
        $this->newLine();

        $this->line('  Yang akan dilakukan:');
        $this->line('  1. Bersihkan data transaksi (Pengajuan, Log Aktivitas) — master data (User, Jenis Surat, KOP) tetap aman');
        $this->line('  2. Hapus file upload khusus transaksi (pengajuan, foto KTP, profil, ttd, dll)');
        $this->line('  3. Bersihkan cache, session, views, & log');
        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('Apakah Anda yakin ingin melanjutkan?', false)) {
            $this->components->info('Dibatalkan. Tidak ada perubahan yang dilakukan.');
            return self::SUCCESS;
        }

        // ============================================================
        // STEP 1: Bersihkan Data Transaksi (Tanpa Hapus Master Data)
        // ============================================================
        $this->components->task('Membersihkan data pengajuan & log aktivitas', function () {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            
            // Kosongkan tabel transaksi
            \Illuminate\Support\Facades\DB::table('pengajuan_surats')->truncate();
            \Illuminate\Support\Facades\DB::table('log_aktivitas')->truncate();
            \Illuminate\Support\Facades\DB::table('activity_log')->truncate();
            
            // Sesuai permintaan: Hapus semua user KECUALI Admin
            \Illuminate\Support\Facades\DB::table('users')->where('role', '!=', 'admin')->delete();
            
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        });

        // ============================================================
        // STEP 3: Hapus file upload
        // ============================================================
        $totalDeleted = 0;
        $totalSize    = 0;
        $basePath     = storage_path('app/public');

        foreach ($this->uploadFolders as $folder) {
            $folderPath = $basePath . DIRECTORY_SEPARATOR . $folder;

            if (! File::isDirectory($folderPath)) {
                continue;
            }

            $files = File::allFiles($folderPath);
            $count = count($files);
            $size  = collect($files)->sum(fn ($f) => $f->getSize());

            // Hapus semua file di dalam folder, tapi jaga folder-nya tetap ada
            foreach ($files as $file) {
                File::delete($file->getPathname());
            }

            // Hapus juga subfolder kosong jika ada
            foreach (File::directories($folderPath) as $subDir) {
                File::deleteDirectory($subDir);
            }

            $totalDeleted += $count;
            $totalSize    += $size;

            $sizeMB = round($size / 1024 / 1024, 2);
            $this->components->twoColumnDetail(
                "  Hapus file di <comment>{$folder}</comment>",
                "<info>{$count} file ({$sizeMB} MB)</info>"
            );
        }

        // Tidak menghapus file root (kecuali ada folder spesifik lain nantinya)

        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
        $this->newLine();
        $this->components->info("✅ Total file dihapus: {$totalDeleted} file ({$totalSizeMB} MB dibebaskan)");

        // ============================================================
        // STEP 4: Bersihkan cache, session, views, log
        // ============================================================
        $this->newLine();
        
        $this->components->task('Bersihkan cache aplikasi', function () {
            Artisan::call('cache:clear');
        });

        $this->components->task('Bersihkan cache config', function () {
            Artisan::call('config:clear');
        });

        $this->components->task('Bersihkan cache route', function () {
            Artisan::call('route:clear');
        });

        $this->components->task('Bersihkan cache view', function () {
            Artisan::call('view:clear');
        });

        // Hapus file log
        $this->components->task('Bersihkan file log', function () {
            $logPath = storage_path('logs');
            $logFiles = File::glob($logPath . DIRECTORY_SEPARATOR . '*.log');
            foreach ($logFiles as $logFile) {
                File::delete($logFile);
            }
        });

        // Bersihkan session files (jika menggunakan file driver)
        $this->components->task('Bersihkan session files', function () {
            $sessionPath = storage_path('framework/sessions');
            if (File::isDirectory($sessionPath)) {
                $sessionFiles = File::files($sessionPath);
                foreach ($sessionFiles as $file) {
                    if ($file->getFilename() !== '.gitignore') {
                        File::delete($file->getPathname());
                    }
                }
            }
        });

        // ============================================================
        // SUMMARY
        // ============================================================
        $this->newLine(2);
        $this->components->info('🎉 Project sudah bersih dan siap untuk hosting!');
        $this->newLine();
        $this->line('  📋 Informasi login Admin:');
        $this->line('     Email    : <comment>admin@desabuttusawe.id</comment>');
        $this->line('     Password : <comment>Admin@Sindesa2026</comment>');
        $this->newLine();
        $this->components->warn('⚠️  SEGERA ganti password setelah login pertama!');
        $this->newLine();
        $this->line('  📌 Langkah selanjutnya di server hosting:');
        $this->line('     1. Upload project ke server');
        $this->line('     2. Sesuaikan file <comment>.env</comment> (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL)');
        $this->line('     3. Jalankan <comment>php artisan storage:link</comment>');
        $this->line('     4. Jalankan <comment>php artisan config:cache</comment>');
        $this->line('     5. Jalankan <comment>php artisan route:cache</comment>');
        $this->newLine();

        return self::SUCCESS;
    }
}
