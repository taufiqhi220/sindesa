<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class AuthController extends Controller
{
    // ==========================================
    // HELPER: Verifikasi reCAPTCHA v3 (ANTI-BOT)
    // ==========================================

    /**
     * Verifikasi token reCAPTCHA v3 ke Google.
     * Mengembalikan true jika LOLOS, false jika GAGAL/BOT.
     *
     * @param string|null $token Token dari hidden input g-recaptcha-response
     * @param string $expectedAction Nama action yang diharapkan (login, register, dll)
     * @param string|null $ip IP address user untuk verifikasi tambahan
     * @return bool
     */
    private function verifyRecaptcha(?string $token, string $expectedAction, ?string $ip = null): bool
    {
        // LANGKAH 1: Tolak langsung jika token kosong
        if (empty($token)) {
            Log::warning('reCAPTCHA: Token kosong terdeteksi', ['action' => $expectedAction, 'ip' => $ip]);
            return false;
        }

        try {
            // LANGKAH 2: Kirim token ke Google untuk verifikasi
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]);

            // LANGKAH 3: Cek apakah HTTP request berhasil
            if (!$response->successful()) {
                Log::error('reCAPTCHA: HTTP request gagal', ['status' => $response->status()]);
                return false;
            }

            $data = $response->json();

            // LANGKAH 4: Debug logging
            Log::info('reCAPTCHA: Hasil verifikasi', [
                'success' => $data['success'] ?? false,
                'score'   => $data['score'] ?? 'N/A',
                'action'  => $data['action'] ?? 'N/A',
                'ip'      => $ip,
            ]);

            // LANGKAH 5: Validasi response dari Google
            // a) Harus success
            if (empty($data['success']) || $data['success'] !== true) {
                Log::warning('reCAPTCHA: Verifikasi gagal', ['errors' => $data['error-codes'] ?? []]);
                return false;
            }

            // b) Score harus >= 0.7
            $score = $data['score'] ?? 0;
            if ($score < 0.7) {
                Log::warning('reCAPTCHA: Score terlalu rendah (bot terdeteksi)', ['score' => $score]);
                return false;
            }

            // c) Action harus cocok
            $action = $data['action'] ?? '';
            if ($action !== $expectedAction) {
                Log::warning('reCAPTCHA: Action tidak cocok', [
                    'expected' => $expectedAction,
                    'actual'   => $action,
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('reCAPTCHA: Exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    // ==========================================
    // 1. BAGIAN LOGIN
    // ==========================================

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // VERIFIKASI RECAPTCHA v3 — ANTI BOT
        if (!$this->verifyRecaptcha(
            $request->input('g-recaptcha-response'),
            'login',
            $request->ip()
        )) {
            return back()
                ->withErrors(['email' => 'Sistem mendeteksi aktivitas mencurigakan (Bot). Silakan coba lagi.'])
                ->onlyInput('email');
        }

        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Deteksi apakah input berupa email atau NIK
        $loginField  = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';
        $credentials = [
            $loginField => $request->email,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // KEAMANAN: Cek status akun sebelum mengizinkan akses
            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $pesan = match ($user->status) {
                    'inactive'  => 'Akun Anda belum diverifikasi oleh Admin Desa. Silakan hubungi petugas.',
                    'suspended' => 'Akun Anda telah ditangguhkan. Hubungi Admin untuk informasi lebih lanjut.',
                    default     => 'Akun Anda tidak aktif.',
                };

                return back()->withErrors(['email' => $pesan])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Arahkan sesuai role
            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard'),
                'kades'    => redirect()->route('kades.dashboard'),
                'operator' => redirect()->route('operator.dashboard'),
                default    => redirect()->route('warga.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ==========================================
    // 2. BAGIAN REGISTER (PENDAFTARAN WARGA)
    // ==========================================

    public function showRegistrationForm()
    {
        $provinces = Province::all();

        return view('auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        // VERIFIKASI RECAPTCHA v3 — ANTI BOT
        if (!$this->verifyRecaptcha(
            $request->input('g-recaptcha-response'),
            'register',
            $request->ip()
        )) {
            return back()
                ->withErrors(['error' => 'Sistem mendeteksi aktivitas mencurigakan (Bot). Pendaftaran dibatalkan.'])
                ->withInput();
        }

        // 1. Validasi semua input dari form
        $request->validate([
            'name'              => 'required|string|max:255',
            'nik'               => 'required|string|size:16|unique:users,nik',
            'no_kk'             => 'required|string|size:16',
            'tempat_lahir'      => 'required|string|max:255',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:Laki-Laki,Perempuan',
            'agama'             => 'required|string',
            'alamat_lengkap'    => 'required|string',
            'rt_rw'             => 'required|string',
            'status_perkawinan' => 'required|string',
            'provinsi'          => 'required',
            'kota'              => 'required',
            'kecamatan'         => 'required',
            'kelurahan_desa'    => 'required',
            'pekerjaan'         => 'required|string',
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'phone'             => 'required|string|max:20',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8|confirmed',
            'foto_ktp'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses upload foto KTP
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('foto_ktp_warga', 'public');
        }

        // 2. Simpan ke database
        User::create([
            'name'              => $request->name,
            'nik'               => $request->nik,
            'no_kk'             => $request->no_kk,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'agama'             => $request->agama,
            'alamat_lengkap'    => $request->alamat_lengkap,
            'rt_rw'             => $request->rt_rw,
            'status_perkawinan' => $request->status_perkawinan,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kelurahan_desa'    => $request->kelurahan_desa,
            'pekerjaan'         => $request->pekerjaan,
            'kewarganegaraan'   => $request->kewarganegaraan,
            'phone'             => $request->phone,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'foto_ktp'          => $fotoKtpPath,
            'role'              => 'warga',
            'status'            => 'inactive',
        ]);

        // 3. Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi dari Admin Desa.');
    }
}