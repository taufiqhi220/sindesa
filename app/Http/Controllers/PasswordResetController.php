<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // reCAPTCHA validation with null-safety
        try {
            $google_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
            ])->json();

            if (!($google_response['success'] ?? false) || ($google_response['score'] ?? 0) < 0.5) {
                return back()->withErrors(['email' => 'Sistem mendeteksi aktivitas bot.']);
            }
        } catch (\Exception $e) {
            Log::warning('reCAPTCHA verification failed: ' . $e->getMessage());
            if (!app()->isLocal()) {
                return back()->withErrors(['email' => 'Verifikasi keamanan gagal. Silakan coba lagi.']);
            }
        }

        try {
            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                        ? back()->with(['success' => 'Tautan reset password telah dikirim ke email Anda.'])
                        : back()->withErrors(['email' => 'Gagal mengirim email reset password.']);
        } catch (\Exception $e) {
            Log::error('Password reset email failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email reset password. Pastikan konfigurasi email server sudah benar.']);
        }
    }

    public function resetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // reCAPTCHA validation with null-safety
        try {
            $google_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
            ])->json();

            if (!($google_response['success'] ?? false) || ($google_response['score'] ?? 0) < 0.5) {
                return back()->withErrors(['email' => 'Sistem mendeteksi aktivitas bot.']);
            }
        } catch (\Exception $e) {
            Log::warning('reCAPTCHA verification failed: ' . $e->getMessage());
            if (!app()->isLocal()) {
                return back()->withErrors(['email' => 'Verifikasi keamanan gagal. Silakan coba lagi.']);
            }
        }

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                        ? redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.')
                        : back()->withErrors(['email' => 'Token kadaluarsa atau tidak valid.']);
        } catch (\Exception $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.']);
        }
    }
}