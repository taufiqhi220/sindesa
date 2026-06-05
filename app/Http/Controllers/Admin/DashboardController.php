<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Admin
     */
    public function index()
    {
        $totalWarga = User::where('role', 'warga')->count();
        $totalOperator = User::where('role', 'operator')->count();
        $totalKades = User::where('role', 'kades')->count();
        $totalJenisSurat = \App\Models\JenisSurat::count();
        
        // TAMBAHAN: Ambil 4 warga terbaru yang mendaftar
        $wargaTerbaru = User::where('role', 'warga')->latest()->take(4)->get();

        return view('admin.dashboard', compact(
            'totalWarga', 
            'totalOperator', 
            'totalKades', 
            'totalJenisSurat',
            'wargaTerbaru' // Lempar ke view
        ));
    }

    /**
     * Manajemen Akun Warga
     */
    public function dataWarga(Request $request)
    {
        $query = User::where('role', 'warga');

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Fitur Pengurutan (Sort By)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'nama_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'status_asc':
                    $query->orderBy('status', 'asc');
                    break;
                case 'status_desc':
                    $query->orderBy('status', 'desc');
                    break;
                case 'terlama':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $perPage = min((int) $request->input('per_page', 5), 100);
        $wargas = $query->paginate($perPage)->withQueryString();

        $total = User::where('role', 'warga')->count();
        $aktif = User::where('role', 'warga')->where('status', 'active')->count();
        $nonaktif = User::where('role', 'warga')->where('status', 'inactive')->count();
        $ditangguhkan = User::where('role', 'warga')->where('status', 'suspended')->count();

        return view('admin.warga.data-warga', compact('wargas', 'total', 'aktif', 'nonaktif', 'ditangguhkan'));
    }

    //Menampilkan Halaman Edit Warga
    public function editWarga(string $id)
    {
        $warga = User::findOrFail($id);
        return view('admin.warga.edit', compact('warga'));
    }

    public function lihatKtp(string $id)
    {
        $warga = User::findOrFail($id);
        
        // Pastikan file KTP ada di storage (disk 'public' karena disimpan via store('foto_ktp_warga', 'public'))
        if (!$warga->foto_ktp || !Storage::disk('public')->exists($warga->foto_ktp)) {
            abort(404, 'Foto KTP tidak ditemukan.');
        }

        // Tampilkan file langsung ke browser tanpa bisa diakses publik
        return response()->file(storage_path('app/public/' . $warga->foto_ktp));
    }

    //Memproses Update Data Warga
    public function updateWarga(Request $request, string $id)
    {
        $warga = User::findOrFail($id);

        // Validasi eksplisit — HANYA field yang diizinkan
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'nik'               => 'nullable|string|max:20',
            'no_kk'             => 'nullable|string|max:20',
            'tempat_lahir'      => 'nullable|string|max:255',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => 'nullable|in:Laki-Laki,Perempuan',
            'agama'             => 'nullable|string|max:50',
            'alamat_lengkap'    => 'nullable|string',
            'rt_rw'             => 'nullable|string|max:20',
            'status_perkawinan' => 'nullable|string|max:50',
            'pekerjaan'         => 'nullable|string|max:100',
            'kewarganegaraan'   => 'nullable|in:WNI,WNA',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'required|email|unique:users,email,'.$id,
            'status'            => 'required|in:active,inactive,suspended',
            'password'          => 'nullable|min:8|confirmed',
        ]);

        // Hapus password dari data jika tidak diisi
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $warga->update($data);

        return redirect()->route('admin.data-warga')->with('success', 'Data warga berhasil diperbarui!');
    }

    //Memproses Hapus Data Warga
    public function destroyWarga(string $id)
    {
        $warga = User::findOrFail($id);
        $warga->delete();
        
        return back()->with('success', 'Data warga berhasil dihapus!');
    }

    /**
     * Fitur Export Data Warga ke CSV
     */
    public function exportWarga(Request $request)
    {
        // Ambil semua data warga dengan sorting
        $query = User::where('role', 'warga');

        $sort = $request->get('sort', 'terbaru');
        
        if ($sort == 'nama_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort == 'nama_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort == 'status') {
            // Urutkan status active -> suspended -> inactive
            $query->orderByRaw("FIELD(status, 'active', 'suspended', 'inactive')");
        } else {
            $query->orderBy('created_at', 'desc'); // default terbaru
        }

        $wargas = $query->get();

        // Load view PDF dan kirim data warga
        $pdf = Pdf::loadView('admin.warga.pdf', compact('wargas'));
        
        // Atur ukuran kertas ke A4 Landscape (Mendatar) agar tabel lebih leluasa
        $pdf->setPaper('A4', 'landscape');

        // Kembalikan sebagai stream (bisa dilihat di browser, lalu di-download)
        return $pdf->stream('Rekap_Data_Warga_SINDESA.pdf');
    }

    /**
     * Manajemen Akun Operator
     */
    public function dataOperator(Request $request)
    {
        $query = User::where('role', 'operator');

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Fitur Pengurutan (Sort By)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'nama_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'status_asc':
                    $query->orderBy('status', 'asc');
                    break;
                case 'status_desc':
                    $query->orderBy('status', 'desc');
                    break;
                case 'terlama':
                    $query->oldest();
                    break;
                default: // terbaru
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // --- TAMBAHKAN VARIABEL PER PAGE DI SINI ---
        $perPage = min((int) $request->input('per_page', 5), 100);
        $operators = $query->paginate($perPage)->withQueryString();
        
        // Data Rekap
        $total = User::where('role', 'operator')->count();
        $aktif = User::where('role', 'operator')->where('status', 'active')->count();
        $nonaktif = $total - $aktif;

        return view('admin.operator.data-operator', compact('operators', 'total', 'aktif', 'nonaktif'));
    }

    public function createOperator() {
        return view('admin.operator.create');
    }

    public function storeOperator(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:30|unique:users,nik',
            'nip' => 'nullable|string|max:30',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended'
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'operator',
            'status' => $request->status,
        ]);

        return redirect()->route('admin.data-operator')->with('success', 'Operator berhasil ditambahkan.');
    }

    public function editOperator(string $id) {
        $operator = User::findOrFail($id);
        return view('admin.operator.edit', compact('operator'));
    }

    public function updateOperator(Request $request, string $id) {
        $operator = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:30|unique:users,nik,' . $id,
            'nip' => 'nullable|string|max:30',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $data = [
            'name' => $request->name,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $operator->update($data);

        return redirect()->route('admin.data-operator')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroyOperator(string $id) {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.data-operator')->with('success', 'Operator dihapus.');
    }

    /**
     * Manajemen Akun Kades
     */
    public function dataKades(Request $request)
    {
        $query = User::where('role', 'kades');

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'nama_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'status_asc':
                    $query->orderBy('status', 'asc'); // Urutkan Status Akun
                    break;
                case 'status_desc':
                    $query->orderBy('status', 'desc');
                    break;
                case 'ttd_asc':
                    $query->orderBy('ttd_path', 'asc'); // Urutkan Status TTD (Ada/Tidak)
                    break;
                case 'ttd_desc':
                    $query->orderBy('ttd_path', 'desc');
                    break;
                case 'terlama':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // Definisikan $perPage di sini
        $perPage = min((int) $request->input('per_page', 5), 100);
        
        // Panggil $perPage di dalam paginate()
        $kades = $query->paginate($perPage)->withQueryString();
        
        $total = User::where('role', 'kades')->count();
        $aktif = User::where('role', 'kades')->where('status', 'active')->count();
        $nonaktif = $total - $aktif;

        return view('admin.kades.data-kades', compact('kades', 'total', 'aktif', 'nonaktif'));
    }
    
    // --- FITUR KEPALA DESA ---

    public function createKades()
    {
        return view('admin.kades.create');
    }

    public function storeKades(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik',
            'nip' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            'ttd_path' => 'required|image|mimes:png|max:2048', // Wajib PNG, maksimal 2MB
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ], [
            'ttd_path.required' => 'Spesimen tanda tangan wajib diunggah.',
            'ttd_path.mimes' => 'Spesimen tanda tangan harus berformat PNG.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.'
        ]);

        // 2. Logika Khusus: Hanya boleh ada 1 Kades Aktif
        // Jika kades baru ini diset 'active', maka kades lama yang 'active' harus diubah jadi 'inactive'
        if ($request->status === 'active') {
            User::where('role', 'kades')
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        // 3. Proses Upload Spesimen Tanda Tangan
        $ttdPath = null;
        if ($request->hasFile('ttd_path')) {
            // Menyimpan gambar ke folder storage/app/public/ttd_kades
            $ttdPath = $request->file('ttd_path')->store('ttd_kades', 'public');
        }

        // 4. Simpan Data ke Database
        User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'kades',
            'status' => $request->status,
            'ttd_path' => $ttdPath,
        ]);

        return redirect()->route('admin.data-kades')->with('success', 'Data Pejabat Kepala Desa berhasil ditambahkan.');
    }

    public function editKades(string $id)
    {
        $kades = User::findOrFail($id);
        return view('admin.kades.edit', compact('kades'));
    }

    public function updateKades(Request $request, string $id)
    {
        $kades = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30|unique:users,nik,' . $id,
            'nip' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            'ttd_path' => 'nullable|image|mimes:png|max:2048', // Opsional, wajib PNG jika diisi
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'ttd_path.mimes' => 'Spesimen tanda tangan harus berformat PNG.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.'
        ]);

        // Logika Khusus: Jika status Kades ini diubah jadi 'active', ubah Kades aktif lainnya jadi 'inactive'
        if ($request->status === 'active' && $kades->status !== 'active') {
            User::where('role', 'kades')
                ->where('status', 'active')
                ->where('id', '!=', $id) // Kecuali diri sendiri
                ->update(['status' => 'inactive']);
        }

        // Proses Upload Spesimen Baru (jika ada)
        $ttdPath = $kades->ttd_path; // Default ke path lama
        if ($request->hasFile('ttd_path')) {
            // Hapus spesimen lama dari storage jika ada
            if ($kades->ttd_path && Storage::disk('public')->exists($kades->ttd_path)) {
                Storage::disk('public')->delete($kades->ttd_path);
            }
            
            // Simpan spesimen baru
            $ttdPath = $request->file('ttd_path')->store('ttd_kades', 'public');
        }

        // Update Database
        $kades->update([
            'name' => $request->name,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'ttd_path' => $ttdPath,
            'password' => $request->filled('password') ? bcrypt($request->password) : $kades->password,
        ]);

        return redirect()->route('admin.data-kades')->with('success', 'Data Kepala Desa berhasil diperbarui.');
    }

    public function destroyKades(string $id)
    {
        $kades = User::findOrFail($id);
        
        // Hapus file fisik spesimen TTD dari storage
        if ($kades->ttd_path && Storage::disk('public')->exists($kades->ttd_path)) {
            Storage::disk('public')->delete($kades->ttd_path);
        }

        $kades->delete();

        return redirect()->route('admin.data-kades')->with('success', 'Riwayat Kepala Desa beserta spesimen TTD berhasil dihapus permanen.');
    }

    public function nonaktifKades(string $id)
    {
        // Fungsi khusus tombol "Akhiri Jabatan" (Purna Tugas)
        $kades = User::findOrFail($id);
        $kades->update(['status' => 'inactive']); // Mengubah status jadi tidak aktif

        return redirect()->route('admin.data-kades')->with('success', 'Kepala Desa berhasil dipurnatugaskan.');
    }

    /**
     * Konfigurasi Template & Syarat Surat
     */
    public function kelolaSurat()
    {
        // Menarik data Kop dari database (sesuai foto phpMyAdmin kamu)
        $pengaturan = \App\Models\PengaturanSurat::first(); 
        
        // Menarik 15 data jenis surat
        $jenisSurats = \App\Models\JenisSurat::all();

        return view('admin.kelola-surat', compact('pengaturan', 'jenisSurats'));
    }

    public function toggleSurat(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'is_active' => 'required|boolean',
            ]);

            $surat = \App\Models\JenisSurat::findOrFail($request->id);
            $surat->is_active = $request->is_active ? 1 : 0;
            $surat->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Keamanan: Jangan ekspos pesan error internal ke user
            Log::error('Toggle Surat Error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengubah status surat. Silakan coba lagi.'
            ], 500);
        }
    }

    public function editKop()
    {
        $pengaturan = \App\Models\PengaturanSurat::first();
        return view('admin.edit-kop', compact('pengaturan'));
    }

    public function updateKop(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'header_1' => 'nullable|string|max:255',
            'header_2' => 'nullable|string|max:255',
            'nama_desa' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
        ]);

        $pengaturan = \App\Models\PengaturanSurat::first() ?? new \App\Models\PengaturanSurat();
        
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($pengaturan->logo_path) {
                Storage::disk('public')->delete($pengaturan->logo_path);
            }
            $pengaturan->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $pengaturan->header_1 = $request->header_1;
        $pengaturan->header_2 = $request->header_2;
        $pengaturan->nama_desa = $request->nama_desa;
        $pengaturan->alamat = $request->alamat;
        $pengaturan->save();

        return redirect()->route('admin.kelola-surat')->with('success', 'Kop surat diperbarui!');
    }

    /**
     * Settings, Backup, & Logo
     */
    public function pengaturan()
    {
        $pengaturan = \App\Models\PengaturanSurat::first();
        $isMaintenance = app()->isDownForMaintenance();
        return view('admin.pengaturan', compact('pengaturan', 'isMaintenance'));
    }

    public function updateDesa(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $pengaturan = \App\Models\PengaturanSurat::first() ?? new \App\Models\PengaturanSurat();
        
        $pengaturan->nama_desa = $request->nama_desa;
        $pengaturan->header_2 = 'KECAMATAN ' . strtoupper($request->kecamatan);
        $pengaturan->header_1 = 'PEMERINTAH KABUPATEN ' . strtoupper($request->kabupaten);
        $pengaturan->alamat = $request->alamat;

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo_path) Storage::disk('public')->delete($pengaturan->logo_path);
            $pengaturan->logo_path = $request->file('logo')->store('logos', 'public');
        }
        
        $pengaturan->save();
        return back()->with('success', 'Identitas Desa berhasil diperbarui!');
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Password hanya diubah jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) Storage::disk('public')->delete($user->foto_profil);
            $user->foto_profil = $request->file('foto_profil')->store('profil', 'public');
        }

        $user->save();
        return back()->with('success', 'Profil Administrator diperbarui!');
    }

    public function toggleMaintenance(Request $request)
    {
        try {
            $request->validate(['is_maintenance' => 'required|boolean']);

            if ($request->is_maintenance) {
                // Mode perbaikan aktif
                Artisan::call('down', ['--secret' => 'sindesa-admin']); 
            } else {
                // Mode perbaikan dimatikan
                Artisan::call('up'); 
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Toggle Maintenance Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah mode perbaikan.']);
        }
    }

    public function backupDatabase()
    {
        try {
            $filename = "backup-sindesa-" . date('Y-m-d') . ".sql";
            $filePath = storage_path("app/" . $filename);

            $mysqldumpPath = 'mysqldump';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('where mysqldump 2>nul', $out, $res);
                if ($res !== 0) {
                    $laragonMysqlPath = dirname(base_path(), 2) . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'mysql';
                    if (is_dir($laragonMysqlPath)) {
                        $folders = scandir($laragonMysqlPath);
                        foreach ($folders as $folder) {
                            if ($folder !== '.' && $folder !== '..') {
                                $path = $laragonMysqlPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'mysqldump.exe';
                                if (file_exists($path)) {
                                    $mysqldumpPath = $path;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $command = [
                $mysqldumpPath,
                '--user=' . config('database.connections.mysql.username'),
                '--host=' . config('database.connections.mysql.host'),
                '--port=' . config('database.connections.mysql.port'),
            ];

            $password = config('database.connections.mysql.password');
            if (!empty($password)) {
                $command[] = '--password=' . $password;
            }

            $command[] = config('database.connections.mysql.database');

            // Menyediakan environment variables Windows (khususnya SystemRoot) agar mysqldump tidak gagal socket 10106
            $env = $_SERVER;
            if (!isset($env['SystemRoot'])) {
                $env['SystemRoot'] = 'C:\Windows';
            }

            // Gunakan Symfony Process untuk menghindari command injection
            $process = new \Symfony\Component\Process\Process($command, null, $env);

            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Backup Database Error: ' . ($process->getErrorOutput() ?: 'Unknown mysqldump error'));
                return back()->with('error', 'Gagal membackup database. Silakan coba lagi atau hubungi administrator.');
            }

            // Tulis output ke file
            file_put_contents($filePath, $process->getOutput());

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Backup Database Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function logAktivitas(Request $request)
    {
        // Ambil request per_page, defaultnya 10
        $perPage = min((int) $request->input('per_page', 10), 100);
        
        // Pastikan pakai ->appends() agar saat pindah halaman, jumlah datanya tidak keriset
        $logs = Activity::latest()
                ->paginate($perPage)
                ->appends(request()->query());

        return view('admin.log-aktivitas', compact('logs'));
    }

    // Pusat Bantuan Admin
    public function pusatBantuan()
    {
        return view('admin.pusat-bantuan');
    }
}