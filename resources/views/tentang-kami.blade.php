<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Tentang Kami - SINDESA Desa Buttu Sawe</title>
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
            <a href="/" class="hover:text-emerald-600 transition-colors">Beranda</a>
            <a href="{{ route('tentang-kami') }}"
                class="{{ request()->routeIs('tentang-kami') ? 'text-emerald-600 font-bold border-b-2 border-emerald-600' : 'hover:text-emerald-600' }}">Tentang
                Kami</a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('login') }}"
                class="px-6 py-2.5 rounded-full font-semibold border-2 border-[#2e7d32] text-[#2e7d32] hover:bg-[#2e7d32] hover:text-white transition-all">Login</a>
            <a href="{{ route('register') }}"
                class="px-6 py-2.5 rounded-full font-semibold border-2 border-[#cfa03f] bg-[#cfa03f] text-white hover:bg-[#b88e32] hover:border-[#b88e32] transition-all">Register</a>
        </div>
    </nav>

    <section class="bg-gradient-to-br from-[#11442b] to-[#2e7d32] text-white px-[5%] py-16 md:py-24 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">Mengenal SINDESA</h1>
        <p class="text-lg max-w-3xl mx-auto opacity-90">Solusi digital untuk pelayanan administrasi Desa Buttu Sawe yang
            lebih transparan dan efisien.</p>
    </section>

    <section class="py-20 px-[5%]">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16">
            <div class="flex-1 relative">
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#cfa03f]/20 rounded-full -z-10"></div>
                <img src="{{ asset('image/hero-image-placeholder.png') }}" alt="Kantor Desa Buttu Sawe"
                    class="rounded-2xl shadow-2xl border-8 border-white w-full h-[450px] object-cover transition-transform hover:scale-[1.02] duration-500">
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#1a5e35]/10 rounded-2xl -z-10"></div>
            </div>

            <div class="flex-1">
                <h2 class="text-3xl font-bold text-[#1a5e35] mb-6 leading-snug">Membangun Masa Depan Digital Desa Buttu
                    Sawe</h2>

                <div class="space-y-5 text-gray-600 leading-relaxed">
                    <p>{{ $aboutContents['paragraph_1'] }}</p>
                    <p>{{ $aboutContents['paragraph_2'] }}</p>
                    <p class="p-4 bg-emerald-50 border-l-4 border-[#cfa03f] italic rounded-r-lg text-[#1a5e35]">
                        "{{ $aboutContents['paragraph_3'] }}"
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-10">
                    <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white hover:shadow-md transition-all">
                        <div class="bg-[#cfa03f]/10 p-3 rounded-lg text-[#cfa03f]">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1a5e35]">Transparan</h4>
                            <p class="text-xs text-gray-500 mt-1">Keterbukaan informasi publik desa.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white hover:shadow-md transition-all">
                        <div class="bg-[#2e7d32]/10 p-3 rounded-lg text-[#2e7d32]">
                            <i class="fas fa-bolt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1a5e35]">Cepat</h4>
                            <p class="text-xs text-gray-500 mt-1">Efisiensi pengurusan administrasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 px-[5%] relative overflow-hidden">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[#cfa03f] font-bold tracking-widest uppercase text-sm">Tujuan Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-[#1a5e35] mt-2">Visi & Misi</h2>
                <div class="w-20 h-1.5 bg-[#cfa03f] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div
                    class="group bg-[#f9f9f9] p-10 rounded-3xl border-b-8 border-[#2e7d32] shadow-sm hover:shadow-xl transition-all duration-300">
                    <div
                        class="mb-6 inline-block bg-[#2e7d32] text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        Visi</div>
                    <p class="text-gray-700 italic leading-relaxed text-xl">
                        "Terwujudnya Desa Buttu Sawe yang mandiri, sejahtera, dan terdepan dalam pelayanan publik
                        berbasis teknologi di Kabupaten Pinrang."
                    </p>
                </div>

                <div
                    class="group bg-[#f9f9f9] p-10 rounded-3xl border-b-8 border-[#cfa03f] shadow-sm hover:shadow-xl transition-all duration-300">
                    <div
                        class="mb-6 inline-block bg-[#cfa03f] text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        Misi</div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-arrow-right text-[#cfa03f] mt-1.5 text-sm"></i>
                            <span class="text-gray-600">Mengoptimalkan penggunaan teknologi untuk pelayanan
                                administratif desa.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-arrow-right text-[#cfa03f] mt-1.5 text-sm"></i>
                            <span class="text-gray-600">Meningkatkan transparansi tata kelola keuangan dan program kerja
                                desa.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-arrow-right text-[#cfa03f] mt-1.5 text-sm"></i>
                            <span class="text-gray-600">Mendekatkan layanan desa ke genggaman masyarakat secara
                                real-time.</span>
                        </li>
                    </ul>
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
            <div class="flex items-start mb-4">
                <i class="fas fa-map-marker-alt text-[#cfa03f] w-8 text-xl text-center mr-4 mt-1"></i>
                <p>Jl. Poros Bungi-Rajang, Buttu Sawe, Kec. Duampanua, Kabupaten Pinrang, Sulawesi Selatan</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fas fa-envelope text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>email: sindesa.buttusawe@gmail.com</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fab fa-whatsapp text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>WA: -</p>
            </div>
        </div>
        <div class="flex-1 min-w-[300px]">
            <h3 class="text-xl mb-6 text-[#cfa03f] border-b-2 border-[#cfa03f] inline-block pb-1 font-semibold">Lokasi
                Kami</h3>
            <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/10 bg-white/5">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.0911448253682!2d119.54731177570577!3d-3.566494642371504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d945d6e318aa5a1%3A0xeb2eeba9b59e69c9!2sKANTOR%20DESA%20BUTTUSAWE!5e0!3m2!1sid!2sid!4v1780627282660!5m2!1sid!2sid"
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </footer>
    <div class="text-center py-6 bg-[#0d3320] text-white/70 text-sm">
        © 2026 SINDESA Desa Buttu Sawe. All rights reserved.
    </div>

    @include('components.chatbot-widget')
</body>

</html>