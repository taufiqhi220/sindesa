<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Lupa Kata Sandi - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] min-h-screen flex flex-col bg-[#f4f6f9] items-center justify-center p-4">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-[#1a5e35]">Lupa Kata Sandi?</h2>
            <p class="text-sm text-gray-500 mt-2">Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk
                mengatur ulang kata sandi.</p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 text-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first('email') }}
            </div>
        @endif

        <form id="forgotForm" action="{{ route('password.email') }}" method="POST">
            @csrf

            {{-- Input reCAPTCHA --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-forgot">

            <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@gmail.com"
                        class="w-full py-3.5 pl-11 pr-4 border border-gray-200 bg-gray-50 rounded-xl focus:bg-white focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-[#1a5e35] hover:bg-[#11442b] text-white rounded-full font-bold transition-all shadow-md">
                KIRIM TAUTAN RESET
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-[#cfa03f] font-bold hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
            </a>
        </div>
    </div>

    {{-- Script Validasi reCAPTCHA dengan Error Handling --}}
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const form = this;
            
            try {
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'forgot_password' })
                    .then(function (token) {
                        document.getElementById('g-recaptcha-response-forgot').value = token;
                        form.submit();
                    })
                    .catch(function(error) {
                        alert('Koneksi ke sistem keamanan (reCAPTCHA) gagal. Silakan periksa koneksi internet Anda atau matikan ad-blocker dan coba lagi.');
                    });
                });
                
                // Fallback timeout 3 detik jika reCAPTCHA hang/diblokir oleh browser
                setTimeout(function() {
                    if (!document.getElementById('g-recaptcha-response-forgot').value) {
                        alert('Validasi keamanan (reCAPTCHA) tidak merespon. Pastikan koneksi internet Anda stabil atau coba gunakan browser lain.');
                    }
                }, 3000);
            } catch (err) {
                alert('Sistem keamanan gagal dimuat. Silakan muat ulang halaman ini.');
            }
        });
    </script>
</body>

</html>