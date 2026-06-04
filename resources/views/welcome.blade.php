<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>SINDESA - Digitalisasi Desa Buttu Sawe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-[#f9f9f9] text-[#333333] m-0 p-0 scroll-smooth">

    @if(app()->isDownForMaintenance())
        <div
            class="w-full bg-red-600 text-white text-center py-3 font-semibold shadow-md z-[9999] relative flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-tools"></i>
            Sistem SINDESA sedang dalam pemeliharaan (Maintenance Mode). Beberapa fitur mungkin dibatasi.
        </div>
    @endif

    <nav class="flex justify-between items-center px-[5%] py-6 bg-white shadow-sm sticky top-0 z-[100]">
        <a href="/" class="flex items-center font-bold text-2xl text-[#1a5e35] hover:opacity-80 transition-opacity">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-9 mr-2.5">
        </a>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="/"
                class="{{ request()->is('/') ? 'text-emerald-600 font-bold border-b-2 border-emerald-600' : 'hover:text-emerald-600' }} transition-colors">
                Beranda
            </a>
            <a href="{{ route('tentang-kami') }}"
                class="{{ request()->routeIs('tentang-kami') ? 'text-emerald-600 font-bold border-b-2 border-emerald-600' : 'hover:text-emerald-600' }} transition-colors">
                Tentang Kami
            </a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('login') }}"
                class="px-6 py-2.5 rounded-full font-semibold border-2 border-[#2e7d32] text-[#2e7d32] hover:bg-[#2e7d32] hover:text-white transition-all">Login</a>
            <a href="{{ route('register') }}"
                class="px-6 py-2.5 rounded-full font-semibold border-2 border-[#cfa03f] bg-[#cfa03f] text-white hover:bg-[#b88e32] hover:border-[#b88e32] transition-all">Register</a>
        </div>
    </nav>

    <section
        class="bg-gradient-to-br from-[#11442b] to-[#2e7d32] text-white px-[5%] py-16 md:py-24 flex flex-col md:flex-row items-center justify-between text-center md:text-left gap-12">
        <div class="flex-1 max-w-2xl">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight drop-shadow-sm">DIGITALISASI PELAYANAN DESA
                BUTTU SAWE UNTUK KEMUDAHAN WARGA</h1>
            <p class="text-lg mb-8 opacity-95 leading-relaxed">Mewujudkan Desa Buttu Sawe yang maju dengan layanan
                administrasi digital yang cepat, transparan, dan mudah diakses oleh seluruh masyarakat desa.</p>
        </div>
        <div class="flex-1 flex justify-center md:justify-end">
            <img src="{{ asset('image/hero-image-placeholder.png') }}" alt="Perangkat Desa Buttu Sawe"
                class="w-72 h-72 md:w-[450px] md:h-[450px] rounded-full object-cover border-8 border-white/20 shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
        </div>
    </section>

    <section class="py-20 px-[5%] text-center" id="fitur">
        <h2 class="text-3xl md:text-4xl text-[#1a5e35] font-bold mb-4">Layanan Desa Kami</h2>
        <p class="max-w-2xl mx-auto mb-16 text-gray-600 leading-relaxed">Layanan digital yang mencakup berbagai
            kebutuhan administrasi dan perizinan di Desa Buttu Sawe untuk mempermudah urusan warga.</p>

        <div class="flex flex-wrap justify-center gap-10 max-w-6xl mx-auto">
            <div
                class="bg-white rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.15)] transition-all duration-300 border border-black/5 flex flex-col flex-1 min-w-[280px] max-w-[350px]">
                <img src="{{ asset('image/layanan-kependudukan-placeholder.jpg') }}" alt="Administrasi Kependudukan"
                    class="w-full h-56 object-cover">
                <div class="p-6 text-left grow">
                    <h3 class="text-xl text-[#1a5e35] font-semibold">Administrasi Kependudukan</h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.15)] transition-all duration-300 border border-black/5 flex flex-col flex-1 min-w-[280px] max-w-[350px]">
                <img src="{{ asset('image/layanan-perizinan-placeholder.png') }}" alt="Layanan Perizinan Desa"
                    class="w-full h-56 object-cover">
                <div class="p-6 text-left grow">
                    <h3 class="text-xl text-[#1a5e35] font-semibold">Layanan Perizinan Desa</h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.15)] transition-all duration-300 border border-black/5 flex flex-col flex-1 min-w-[280px] max-w-[350px]">
                <img src="{{ asset('image/layanan-surat-placeholder.png') }}" alt="Surat Keterangan Umum"
                    class="w-full h-56 object-cover">
                <div class="p-6 text-left grow">
                    <h3 class="text-xl text-[#1a5e35] font-semibold">Surat Keterangan Umum</h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)] hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(0,0,0,0.15)] transition-all duration-300 border border-black/5 flex flex-col flex-1 min-w-[280px] max-w-[350px]">
                <img src="{{ asset('image/layanan-sosial-placeholder.png') }}" alt="Layanan Sosial Desa"
                    class="w-full h-56 object-cover">
                <div class="p-6 text-left grow">
                    <h3 class="text-xl text-[#1a5e35] font-semibold">Layanan Sosial Desa</h3>
                </div>
            </div>
        </div>
    </section>

    <footer
        class="bg-gradient-to-br from-[#11442b] to-[#2e7d32] text-white px-[5%] py-16 flex flex-col md:flex-row justify-between gap-8"
        id="kontak">
        <div class="flex-1 min-w-[300px]">
            <div class="text-white mb-4">
                <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo White" class="h-9">
            </div>
            <p class="max-w-md leading-relaxed opacity-90 mt-4">SINDESA adalah komitmen Desa Buttu Sawe untuk mewujudkan
                pemerintahan desa yang maju, transparan, dan melayani melalui transformasi digital.</p>
        </div>
        <div class="flex-1 min-w-[300px]">
            <h3 class="text-xl mb-6 text-[#cfa03f] border-b-2 border-[#cfa03f] inline-block pb-1 font-semibold">Hubungi
                Kami</h3>
            <div class="flex items-center mb-4">
                <i class="fas fa-map-marker-alt text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>Kantor Desa Buttu Sawe, Kec. Duampanua, Kab. Pinrang</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fas fa-envelope text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>email: layanan@desabuttusawe.id</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fab fa-whatsapp text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>WA: 0812-3456-7890</p>
            </div>
        </div>
    </footer>
    <div class="text-center py-6 bg-[#0d3320] text-white/70 text-sm">
        © 2026 SINDESA Desa Buttu Sawe. All rights reserved.
    </div>

</body>

</html>