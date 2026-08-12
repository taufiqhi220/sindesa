<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Warga\DashboardController as WargaDashboard;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Operator\OperatorSuratController;
use App\Http\Controllers\Operator\PengaturanSuratController;
use App\Http\Controllers\Operator\PengaturanAkunController;
use App\Http\Controllers\Kades\DashboardController as KadesDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Middleware\CekSuratAktif;
use App\Http\Controllers\PasswordResetController;


// ==========================================
// HALAMAN PUBLIK & TAMU
// ==========================================
Route::get('/', function () { return view('welcome'); });
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('tentang-kami');
Route::get('/verifikasi/surat/{token}', [PublicController::class, 'verifikasiSurat'])->middleware(['signed', 'throttle:15,1'])->name('verifikasi.surat');

// Route Helper Otomatis untuk Membuat Storage Link di Server Hosting (cPanel / Live Server)
// KEAMANAN: Dilindungi auth + role admin agar tidak bisa diakses publik
Route::get('/buat-symlink', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'BERHASIL: Storage link berhasil dibuat di server!';
    } catch (\Exception $e) {
        return 'INFO / ERROR: ' . $e->getMessage();
    }
})->middleware(['auth', 'role:admin']);

// Bypass untuk mengatasi 403 Forbidden pada Windows NTFS Symlink (php artisan serve)
Route::get('/storage/{path}', function ($path) {
    // --- KEAMANAN: Cegah Path Traversal (../ attack) ---
    $basePath = realpath(storage_path('app/public'));
    $fullPath = realpath(storage_path('app/public/' . $path));

    // Jika realpath gagal (file tidak ada) atau path keluar dari folder storage
    if (!$fullPath || !str_starts_with($fullPath, $basePath)) {
        abort(404);
    }

    if (file_exists($fullPath)) {
        $user = Auth::user();
        
        // --- Sistem Keamanan Ketat: Kontrol akses berdasarkan role ---
        // Whitelist: Hanya folder-folder ini yang boleh diakses
        $allowedDirs = ['pengajuan', 'foto_ktp_warga', 'profil', 'ttd_kades', 'ttd', 'logos'];
        $pathSegments = explode('/', str_replace('\\', '/', $path));
        $requestedDir = $pathSegments[0] ?? '';

        if (!in_array($requestedDir, $allowedDirs)) {
            abort(403, 'AKSES DITOLAK: Direktori tidak diizinkan.');
        }

        // ROLE WARGA: Hanya boleh akses file milik sendiri
        if ($user->role === 'warga') {
            // Folder foto_ktp_warga & profil: cek berdasarkan record user
            if (in_array($requestedDir, ['foto_ktp_warga', 'profil'])) {
                $userFiles = array_filter([$user->foto_ktp, $user->foto_profil]);
                if (!in_array($path, $userFiles)) {
                    abort(403, 'AKSES DITOLAK: Anda tidak berhak melihat dokumen milik warga lain.');
                }
            }
            // Folder pengajuan: cek berdasarkan surat milik warga
            elseif ($requestedDir === 'pengajuan') {
                $milikUser = \App\Models\PengajuanSurat::where('user_id', $user->id)
                    ->get()
                    ->pluck('data_tambahan')
                    ->filter()
                    ->flatMap(fn($dt) => collect($dt)->filter(fn($v) => is_string($v) && str_starts_with($v, 'pengajuan/')))
                    ->contains($path);
                if (!$milikUser) {
                    abort(403, 'AKSES DITOLAK: Anda tidak berhak melihat dokumen milik warga lain.');
                }
            }
            // Folder ttd_kades, ttd, logos: warga tidak boleh akses langsung
            elseif (in_array($requestedDir, ['ttd_kades', 'ttd'])) {
                abort(403, 'AKSES DITOLAK.');
            }
        }
        // -------------------------------------------------------------------------------

        try {
            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            return response(file_get_contents($fullPath), 200)
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'private, no-store');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('File read error: ' . $e->getMessage());
            abort(500, 'Gagal membaca file.');
        }
    }
    abort(404);
})->where('path', '.*')->middleware('auth');

// Data Wilayah (AJAX) — dengan validasi input & rate limiting ketat
    Route::middleware(['throttle:30,1'])->group(function () {
        Route::get('/data/cities', function (Request $request) { 
            $request->validate(['province_code' => 'required|string|max:10']);
            return DB::table('indonesia_cities')->where('province_code', $request->province_code)->orderBy('name', 'asc')->get(); 
        });
        Route::get('/data/districts', function (Request $request) { 
            $request->validate(['city_code' => 'required|string|max:10']);
            return DB::table('indonesia_districts')->where('city_code', $request->city_code)->orderBy('name', 'asc')->get(); 
        });
        Route::get('/data/villages', function (Request $request) { 
            $request->validate(['district_code' => 'required|string|max:10']);
            return DB::table('indonesia_villages')->where('district_code', $request->district_code)->orderBy('name', 'asc')->get(); 
        });
    });

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->middleware('throttle:15,1'); // Max 15 percobaan login per menit
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register')->middleware('throttle:15,1'); // Max 15 pendaftaran per menit
        Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:10,1')->name('password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->middleware('throttle:10,1')->name('password.update');
    });
});

// ==========================================
// AREA TERLINDUNGI (WAJIB LOGIN)
// ==========================================
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // ROLE: WARGA
    // ------------------------------------------
    Route::middleware(['role:warga'])->prefix('warga')->name('warga.')->group(function () {
        
        // PENGELOMPOKKAN WARGA DASHBOARD CONTROLLER
        Route::controller(WargaDashboard::class)->group(function () {
            // Beranda & Profil
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/riwayat', 'riwayat')->name('riwayat');
            Route::get('/terverifikasi', 'terverifikasi')->name('terverifikasi');
            Route::get('/selesai', 'selesai')->name('selesai');
            Route::get('/verifikasi', 'verifikasi')->name('verifikasi');
            Route::get('/profil', 'profil')->name('profil');
            Route::put('/profil', 'update')->name('profil.update');
            Route::delete('/riwayat/{id}', 'destroy')->name('riwayat.destroy');

            // --- FORM PENGAJUAN SURAT ---
            $chk = CekSuratAktif::class;

            Route::middleware(["$chk:akta-lahir"])->group(function () {
                Route::get('/form/akta-lahir', 'formAktaLahir')->name('form.akta-lahir');
                Route::post('/form/akta-lahir', 'storeAktaLahir')->name('form.akta-lahir.store');
                Route::get('/form-edit/akta-lahir/{id}/edit', 'editAktaLahir')->name('form.akta-lahir.edit');
                Route::put('/form-edit/akta-lahir/{id}', 'updateAktaLahir')->name('form.akta-lahir.update');
            });

            Route::middleware(["$chk:ktp"])->group(function () {
                Route::get('/form/pengantar-ktp', 'formKtp')->name('form.ktp');
                Route::post('/form/pengantar-ktp', 'storeKtp')->name('form.ktp.store');
                Route::get('/form-edit/ktp/{id}/edit', 'editKtp')->name('form.ktp.edit');
                Route::put('/form-edit/ktp/{id}', 'updateKtp')->name('form.ktp.update');
            });

            Route::middleware(["$chk:kk"])->group(function () {
                Route::get('/form/pengantar-kk', 'formKk')->name('form.kk');
                Route::post('/form/pengantar-kk', 'storeKk')->name('form.kk.store');
                Route::get('/form-edit/kk/{id}/edit', 'editKk')->name('form.kk.edit');
                Route::put('/form-edit/kk/{id}', 'updateKk')->name('form.kk.update');
            });

            Route::middleware(["$chk:kematian"])->group(function () {
                Route::get('/form/kematian', 'formKematian')->name('form.kematian');
                Route::post('/form/kematian', 'storeKematian')->name('form.kematian.store');
                Route::get('/form-edit/kematian/{id}/edit', 'editKematian')->name('form.kematian.edit');
                Route::put('/form-edit/kematian/{id}', 'updateKematian')->name('form.kematian.update');
            });

            Route::middleware(["$chk:pindah"])->group(function () {
                Route::get('/form/pindah', 'formPindah')->name('form.pindah');
                Route::post('/form/pindah', 'storePindah')->name('form.pindah.store');
                Route::get('/form-edit/pindah/{id}/edit', 'editPindah')->name('form.pindah.edit');
                Route::put('/form-edit/pindah/{id}', 'updatePindah')->name('form.pindah.update');
            });

            Route::middleware(["$chk:domisili"])->group(function () {
                Route::get('/form/domisili', 'formDomisili')->name('form.domisili');
                Route::post('/form/domisili', 'storeDomisili')->name('form.domisili.store');
                Route::get('/form-edit/domisili/{id}/edit', 'editDomisili')->name('form.domisili.edit');
                Route::put('/form-edit/domisili/{id}', 'updateDomisili')->name('form.domisili.update');
            });

            Route::middleware(["$chk:belum-menikah"])->group(function () {
                Route::get('/form/belum-menikah', 'formBelumMenikah')->name('form.belum-menikah');
                Route::post('/form/belum-menikah', 'storeBelumMenikah')->name('form.belum-menikah.store');
                Route::get('/form-edit/belum-menikah/{id}/edit', 'editBelumMenikah')->name('form.belum-menikah.edit');
                Route::put('/form-edit/belum-menikah/{id}', 'updateBelumMenikah')->name('form.belum-menikah.update');
            });

            Route::middleware(["$chk:janda-duda"])->group(function () {
                Route::get('/form/janda-duda', 'formJandaDuda')->name('form.janda-duda');
                Route::post('/form/janda-duda', 'storeJandaDuda')->name('form.janda-duda.store');
                Route::get('/form-edit/janda-duda/{id}/edit', 'editJandaDuda')->name('form.janda-duda.edit');
                Route::put('/form-edit/janda-duda/{id}', 'updateJandaDuda')->name('form.janda-duda.update');
            });

            Route::middleware(["$chk:beda-nama"])->group(function () {
                Route::get('/form/beda-nama', 'formBedaNama')->name('form.beda-nama');
                Route::post('/form/beda-nama', 'storeBedaNama')->name('form.beda-nama.store');
                Route::get('/form-edit/beda-nama/{id}/edit', 'editBedaNama')->name('form.beda-nama.edit');
                Route::put('/form-edit/beda-nama/{id}', 'updateBedaNama')->name('form.beda-nama.update');
            });

            Route::middleware(["$chk:kehilangan"])->group(function () {
                Route::get('/form/kehilangan', 'formKehilangan')->name('form.kehilangan');
                Route::post('/form/kehilangan', 'storeKehilangan')->name('form.kehilangan.store');
                Route::get('/form-edit/kehilangan/{id}/edit', 'editKehilangan')->name('form.kehilangan.edit');
                Route::put('/form-edit/kehilangan/{id}', 'updateKehilangan')->name('form.kehilangan.update');
            });

            Route::middleware(["$chk:skck"])->group(function () {
                Route::get('/form/skck', 'formSkck')->name('form.skck');
                Route::post('/form/skck', 'storeSkck')->name('form.skck.store');
                Route::get('/form-edit/skck/{id}/edit', 'editSkck')->name('form.skck.edit');
                Route::put('/form-edit/skck/{id}', 'updateSkck')->name('form.skck.update');
            });

            Route::middleware(["$chk:usaha"])->group(function () {
                Route::get('/form/usaha', 'formUsaha')->name('form.usaha');
                Route::post('/form/usaha', 'storeUsaha')->name('form.usaha.store');
                Route::get('/form-edit/usaha/{id}/edit', 'editUsaha')->name('form.usaha.edit');
                Route::put('/form-edit/usaha/{id}', 'updateUsaha')->name('form.usaha.update');
            });

            Route::middleware(["$chk:izin-keramaian"])->group(function () {
                Route::get('/form/izin-keramaian', 'formIzinKeramaian')->name('form.izin-keramaian');
                Route::post('/form/izin-keramaian', 'storeIzinKeramaian')->name('form.izin-keramaian.store');
                Route::get('/form-edit/izin-keramaian/{id}/edit', 'editIzinKeramaian')->name('form.izin-keramaian.edit');
                Route::put('/form-edit/izin-keramaian/{id}', 'updateIzinKeramaian')->name('form.izin-keramaian.update');
            });

            Route::middleware(["$chk:tidak-mampu"])->group(function () {
                Route::get('/form/tidak-mampu', 'formTidakMampu')->name('form.tidak-mampu');
                Route::post('/form/tidak-mampu', 'storeTidakMampu')->name('form.tidak-mampu.store');
                Route::get('/form-edit/tidak-mampu/{id}/edit', 'editTidakMampu')->name('form.tidak-mampu.edit');
                Route::put('/form-edit/tidak-mampu/{id}', 'updateTidakMampu')->name('form.tidak-mampu.update');
            });

            Route::middleware(["$chk:penghasilan"])->group(function () {
                Route::get('/form/penghasilan', 'formPenghasilan')->name('form.penghasilan');
                Route::post('/form/penghasilan', 'storePenghasilan')->name('form.penghasilan.store');
                Route::get('/form-edit/penghasilan/{id}/edit', 'editPenghasilan')->name('form.penghasilan.edit');
                Route::put('/form-edit/penghasilan/{id}', 'updatePenghasilan')->name('form.penghasilan.update');
            });
        });

        // Pengelompokkan khusus SuratController (Warga)
        Route::controller(WargaSuratController::class)->group(function () {
            Route::get('/surat/{id}/cetak', 'cetakPdf')->name('surat.cetak');
        });

        // Pusat Bantuan Warga
        Route::get('/bantuan', [WargaDashboard::class, 'bantuan'])->name('bantuan');
    });

    // ------------------------------------------
    // ROLE: OPERATOR
    // ------------------------------------------
    Route::middleware(['role:operator'])->prefix('operator')->name('operator.')->group(function () {
        
        Route::controller(OperatorDashboard::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/verifikasi', 'verifikasi')->name('verifikasi');
            Route::get('/menunggu-ttd', 'menungguTtd')->name('menunggu-ttd');
            Route::get('/riwayat', 'riwayat')->name('riwayat');
            Route::get('/riwayat/cetak', 'cetakLaporan')->name('riwayat.cetak');
            Route::get('/ditolak', 'ditolak')->name('ditolak');
        });

        Route::controller(PengaturanSuratController::class)->group(function () {
            Route::get('/pengaturan-surat', 'index')->name('pengaturan-surat');
            Route::post('/pengaturan-surat/update', 'update')->name('pengaturan-surat.update');
        });

        Route::controller(PengaturanAkunController::class)->group(function () {
            Route::get('/pengaturan-akun', 'index')->name('pengaturan-akun');
            Route::patch('/pengaturan-akun/update', 'updateProfile')->name('pengaturan-akun.update');
            Route::patch('/pengaturan-akun/password', 'updatePassword')->name('pengaturan-akun.password');
        });

        Route::controller(OperatorSuratController::class)->group(function () {
            Route::get('/verifikasi/detail/{id}', 'show')->name('verifikasi.show');
            Route::patch('/verifikasi/update/{id}', 'update')->name('verifikasi.update');
            Route::patch('/verifikasi/tarik/{id}', 'tarik')->name('verifikasi.tarik');
        });

        // Pusat Bantuan Operator
        Route::get('/bantuan', [OperatorDashboard::class, 'bantuan'])->name('bantuan');
    });

    // ------------------------------------------
    // ROLE: KADES
    // ------------------------------------------
    Route::middleware(['role:kades'])->prefix('kades')->name('kades.')->controller(KadesDashboard::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/perlu-ttd', 'perluTtd')->name('perlu-ttd');
        Route::get('/riwayat', 'riwayat')->name('riwayat');
        Route::get('/pengaturan', 'pengaturanAkun')->name('pengaturan-akun');
        Route::get('/surat/cetak/{id}', 'cetakPdf')->name('surat.cetak');
        Route::get('/riwayat/laporan', 'cetakLaporan')->name('riwayat.laporan');
        
        Route::get('/surat/{type}/{id}', 'showDetailSurat')->where('type', '^(?!cetak$).*')->name('surat.detail');
        Route::patch('/surat/proses/{id}', 'prosesSurat')->name('surat.proses');

        Route::patch('/pengaturan/update-foto', 'updateFotoProfil')->name('pengaturan.update-foto');
        Route::patch('/pengaturan/update-profil', 'updateProfil')->name('pengaturan.update-profil');
        Route::patch('/pengaturan/update-ttd', 'updateTtd')->name('pengaturan.update-ttd');
        Route::patch('/pengaturan/update-password', 'updatePassword')->name('pengaturan.update-password');

        // Pusat Bantuan Kades
        Route::get('/bantuan', 'bantuan')->name('bantuan');
    });

    // ------------------------------------------
    // ROLE: ADMIN
    // ------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->controller(AdminDashboard::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/data-warga', 'dataWarga')->name('data-warga');
        Route::get('/data-operator', 'dataOperator')->name('data-operator');
        Route::get('/data-kades', 'dataKades')->name('data-kades');
        
        Route::get('/kelola-surat', 'kelolaSurat')->name('kelola-surat');
        Route::post('/kelola-surat/toggle', 'toggleSurat')->name('kelola-surat.toggle');
        Route::get('/kelola-surat/kop/edit', 'editKop')->name('kelola-surat.edit-kop');
        Route::put('/kelola-surat/kop/update', 'updateKop')->name('kelola-surat.update-kop');
        
        Route::get('/pengaturan', 'pengaturan')->name('pengaturan');
        Route::put('/pengaturan/desa', 'updateDesa')->name('pengaturan.desa');
        Route::put('/pengaturan/profil', 'updateProfil')->name('pengaturan.profil');
        Route::post('/pengaturan/maintenance', 'toggleMaintenance')->name('pengaturan.maintenance');
        Route::get('/pengaturan/backup', 'backupDatabase')->name('pengaturan.backup');
        
        Route::get('/pusat-bantuan', 'pusatBantuan')->name('pusat-bantuan');
        Route::get('/log-aktivitas', 'logAktivitas')->name('log-aktivitas');

        Route::get('/data-warga/export', 'exportWarga')->name('warga.export');
        Route::get('/data-warga/{id}/ktp', 'lihatKtp')->name('warga.ktp');

        Route::get('/data-warga/{id}/edit', 'editWarga')->name('warga.edit');
        Route::put('/data-warga/{id}', 'updateWarga')->name('warga.update');
        Route::delete('/data-warga/{id}', 'destroyWarga')->name('warga.destroy');

        Route::get('/operator/create', 'createOperator')->name('operator.create');
        Route::post('/operator', 'storeOperator')->name('operator.store');
        Route::get('/operator/{id}/edit', 'editOperator')->name('operator.edit');
        Route::put('/operator/{id}', 'updateOperator')->name('operator.update');
        Route::delete('/operator/{id}', 'destroyOperator')->name('operator.destroy');

        Route::get('/kades/create', 'createKades')->name('kades.create');
        Route::post('/kades', 'storeKades')->name('kades.store');
        Route::get('/kades/{id}/edit', 'editKades')->name('kades.edit');
        Route::put('/kades/{id}', 'updateKades')->name('kades.update');
        Route::put('/kades/{id}/nonaktif', 'nonaktifKades')->name('kades.nonaktif');
        Route::delete('/kades/{id}', 'destroyKades')->name('kades.destroy');
    });
});