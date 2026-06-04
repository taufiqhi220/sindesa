<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Atur Ulang Kata Sandi - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tambahan Script reCAPTCHA di bagian Head --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] min-h-screen flex flex-col bg-[#f4f6f9] items-center justify-center p-4">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-[#1a5e35]">Buat Sandi Baru</h2>
            <p class="text-sm text-gray-500 mt-2">Silakan buat kata sandi baru yang kuat untuk akun Anda.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form id="resetForm" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Input reCAPTCHA --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-reset">

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" value="{{ request()->email }}" readonly
                        class="w-full py-3.5 pl-11 pr-4 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 outline-none cursor-not-allowed">
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Kata Sandi Baru</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full py-3.5 pl-11 pr-4 border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all">
                </div>
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                        class="w-full py-3.5 pl-11 pr-4 border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-[#cfa03f] hover:bg-[#b88e32] text-white rounded-full font-bold transition-all shadow-md">
                SIMPAN SANDI BARU
            </button>
        </form>
    </div>

    {{-- Script Validasi reCAPTCHA --}}
    <script>
        document.getElementById('resetForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const form = this;
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'reset_password' }).then(function (token) {
                    document.getElementById('g-recaptcha-response-reset').value = token;
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>