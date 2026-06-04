<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Sistem Dalam Perbaikan - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f4f6f9] min-h-screen flex items-center justify-center p-6">
    <div
        class="max-w-lg w-full bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] p-10 text-center border border-gray-100">

        <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-tools text-5xl text-red-500"></i>
        </div>

        <h1 class="text-3xl font-bold text-[#1a5e35] mb-3">Sistem Dalam Perbaikan</h1>
        <p class="text-gray-500 mb-8 leading-relaxed text-sm">
            Mohon maaf, layanan SINDESA saat ini sedang dalam proses pemeliharaan rutin untuk meningkatkan performa.
            Akses <b>Warga, Operator, dan Kepala Desa</b> ditutup sementara.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-[#cfa03f] hover:bg-[#b88e32] text-white rounded-xl font-bold transition-all shadow-md hover:-translate-y-0.5 text-sm">
                <i class="fas fa-home mr-2"></i> Beranda
            </a>

            <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-red-100 hover:bg-red-200 text-red-600 border border-red-200 rounded-xl font-bold transition-all text-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar Akun
                </button>
            </form>
        </div>

    </div>
</body>

</html>