<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Login - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script reCAPTCHA v3 Resmi dari Google --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
</head>

<body class="font-['Poppins'] min-h-screen flex flex-col bg-white m-0 p-0">

    {{-- BANNER MODE PERBAIKAN --}}
    @if(app()->isDownForMaintenance())
        <div
            class="w-full bg-red-600 text-white text-center py-3 font-semibold shadow-md z-[9999] flex items-center justify-center gap-2 text-sm shrink-0">
            <i class="fas fa-tools"></i>
            <span>Sistem SINDESA sedang dalam pemeliharaan. Saat ini hanya Administrator yang diizinkan masuk.</span>
        </div>
    @endif

    {{-- BUNGKUS KONTEN SPLIT SCREEN KE DALAM MAIN --}}
    <main class="flex-1 flex flex-col-reverse lg:flex-row w-full">

        {{-- BAGIAN KIRI: FORM LOGIN --}}
        <div class="flex-1 flex flex-col justify-center px-[5%] py-12 relative">
            <a href="{{ url('/') }}"
                class="absolute top-8 left-[8%] text-[#1a5e35] font-medium flex items-center hover:-translate-x-1 transition-transform">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

            <div class="max-w-[400px] w-full mx-auto mt-8 lg:mt-0">
                <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-11 mb-6">

                <div class="mb-10">
                    <h2 class="text-[#1a5e35] text-3xl font-bold mb-2">Selamat Datang Kembali</h2>
                    <p class="text-gray-500">Masuk untuk melanjutkan layanan desa digital Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm mb-6 text-sm"
                        role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm mb-6 text-sm"
                        role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                {{-- Tambahkan ID pada form untuk ditangkap oleh JavaScript reCAPTCHA --}}
                <form id="loginForm" action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Input Hidden untuk menyimpan token reCAPTCHA --}}
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm font-semibold text-[#333333]">Email atau
                            NIK</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="email" name="email" value="{{ old('email') }}"
                                placeholder="Contoh: xxxxxxxxxxxx@gmail.com"
                                class="w-full py-3.5 pl-11 pr-4 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#2e7d32] focus:bg-white focus:ring-4 focus:ring-[#1a5e35]/10 transition-all"
                                required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block mb-2 text-sm font-semibold text-[#333333]">Kata Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" id="password" name="password" value="{{ old('password') }}"
                                placeholder="Masukkan kata sandi Anda"
                                class="w-full py-3.5 pl-11 pr-11 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#2e7d32] focus:bg-white focus:ring-4 focus:ring-[#1a5e35]/10 transition-all"
                                required>
                            <i class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600"
                                id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-8 text-sm text-gray-500">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="mr-2 accent-[#1a5e35] w-4 h-4 rounded"> Ingat
                            Saya
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-[#1a5e35] font-semibold hover:underline">Lupa Kata Sandi?</a>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-[#cfa03f] hover:bg-[#b88e32] text-white rounded-full font-semibold text-lg transition-all shadow-[0_4px_15px_rgba(207,160,63,0.2)] hover:-translate-y-0.5 hover:shadow-[0_8px_20px_rgba(207,160,63,0.3)]">
                        MASUK SEKARANG
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-gray-500">
                    Belum memiliki akun SINDESA? <br>
                    <a href="{{ route('register') }}" class="text-[#1a5e35] font-bold hover:underline">Daftar sebagai
                        Warga Baru</a>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: GAMBAR HERO --}}
        <div class="flex-1 bg-cover bg-center relative flex items-center justify-center p-8 lg:p-16 h-[250px] lg:h-auto"
            style="background-image: linear-gradient(135deg, rgba(17, 68, 43, 0.85) 0%, rgba(46, 125, 50, 0.75) 100%), url('{{ asset('image/hero-image-placeholder.png') }}');">
            <div class="max-w-[500px] text-white z-10 text-center lg:text-left">
                <h1 class="text-2xl lg:text-5xl font-bold mb-4 leading-tight">Digitalisasi Pelayanan Desa Buttu Sawe
                </h1>
                <p class="text-base lg:text-lg opacity-90 hidden lg:block">Mewujudkan administrasi desa yang transparan,
                    cepat, dan dapat diakses oleh seluruh warga dari mana saja.</p>
            </div>
        </div>

    </main>

    <script>
        // Fitur Tampil/Sembunyi Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Logika Eksekusi reCAPTCHA v3 saat form dikirim
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Tahan pengiriman form sementara
            const form = this;

            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'login' }).then(function (token) {
                    // Masukkan token yang didapat ke dalam input hidden
                    document.getElementById('g-recaptcha-response').value = token;
                    // Lanjutkan pengiriman form
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>