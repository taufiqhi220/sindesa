<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrateStorageUuid extends Command
{
    /**
     * Nama dan signature perintah konsol.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-uuid';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Migrasi file lama di storage dan database agar menggunakan nama acak berbasis UUIDv4.';

    /**
     * Eksekusi perintah konsol.
     */
    public function handle(): int
    {
        $this->newLine();
        $this->components->info('🚀 Memulai migrasi nama file lama ke format UUIDv4...');
        $this->newLine();

        $baseStorage = storage_path('app/public');
        $renamedCount = 0;

        // ============================================================
        // 1. MIGRASI TABEL USERS (foto_profil, foto_ktp, ttd_path)
        // ============================================================
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $updates = [];

            // a) foto_profil
            if (!empty($user->foto_profil)) {
                $newPath = $this->renameFileToUuid($baseStorage, $user->foto_profil);
                if ($newPath !== $user->foto_profil) {
                    $updates['foto_profil'] = $newPath;
                    $renamedCount++;
                }
            }

            // b) foto_ktp
            if (!empty($user->foto_ktp)) {
                $newPath = $this->renameFileToUuid($baseStorage, $user->foto_ktp);
                if ($newPath !== $user->foto_ktp) {
                    $updates['foto_ktp'] = $newPath;
                    $renamedCount++;
                }
            }

            // c) ttd_path
            if (!empty($user->ttd_path)) {
                $newPath = $this->renameFileToUuid($baseStorage, $user->ttd_path);
                if ($newPath !== $user->ttd_path) {
                    $updates['ttd_path'] = $newPath;
                    $renamedCount++;
                }
            }

            if (!empty($updates)) {
                DB::table('users')->where('id', $user->id)->update($updates);
            }
        }

        // ============================================================
        // 2. MIGRASI TABEL PENGATURAN_SURATS (logo_path)
        // ============================================================
        $pengaturanList = DB::table('pengaturan_surats')->get();
        foreach ($pengaturanList as $pengaturan) {
            if (!empty($pengaturan->logo_path)) {
                $newPath = $this->renameFileToUuid($baseStorage, $pengaturan->logo_path);
                if ($newPath !== $pengaturan->logo_path) {
                    DB::table('pengaturan_surats')->where('id', $pengaturan->id)->update(['logo_path' => $newPath]);
                    $renamedCount++;
                }
            }
        }

        // ============================================================
        // 3. MIGRASI TABEL PENGAJUAN_SURATS (data_tambahan JSON)
        // ============================================================
        $pengajuans = DB::table('pengajuan_surats')->get();
        foreach ($pengajuans as $pengajuan) {
            if (empty($pengajuan->data_tambahan)) {
                continue;
            }

            $data = json_decode($pengajuan->data_tambahan, true);
            if (!is_array($data)) {
                continue;
            }

            $hasChanged = false;
            foreach ($data as $key => $val) {
                if (is_string($val) && (str_starts_with($val, 'pengajuan/') || str_starts_with($val, 'profil/') || str_starts_with($val, 'foto_ktp_warga/'))) {
                    $newPath = $this->renameFileToUuid($baseStorage, $val);
                    if ($newPath !== $val) {
                        $data[$key] = $newPath;
                        $hasChanged = true;
                        $renamedCount++;
                    }
                }
            }

            if ($hasChanged) {
                DB::table('pengajuan_surats')->where('id', $pengajuan->id)->update([
                    'data_tambahan' => json_encode($data),
                ]);
            }
        }

        $this->components->info("🎉 Migrasi selesai! Total berkas diperbarui ke UUIDv4: {$renamedCount} file.");
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Helper merenaming file fisik ke UUIDv4 jika belum berformat UUID
     */
    private function renameFileToUuid(string $baseStorage, string $relativePath): string
    {
        $fullPath = $baseStorage . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (!File::exists($fullPath)) {
            return $relativePath;
        }

        $info = pathinfo($fullPath);
        $filenameWithoutExt = $info['filename'] ?? '';
        $extension = isset($info['extension']) ? '.' . $info['extension'] : '';

        // Cek jika nama file sudah merupakan UUIDv4 valid (36 karakter dengan 4 tanda strip)
        if (Str::isUuid($filenameWithoutExt)) {
            return $relativePath;
        }

        $dir = $info['dirname'];
        $newFilename = Str::uuid() . $extension;
        $newFullPath = $dir . DIRECTORY_SEPARATOR . $newFilename;

        File::move($fullPath, $newFullPath);

        $dirnameRelative = pathinfo($relativePath, PATHINFO_DIRNAME);
        return ($dirnameRelative === '.' ? '' : $dirnameRelative . '/') . $newFilename;
    }
}
