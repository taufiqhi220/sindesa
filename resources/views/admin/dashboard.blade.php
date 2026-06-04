<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Dashboard Admin - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    {{-- Panggil File Sidebar --}}
    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">

        {{-- Header Mobile --}}
        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="max-w-7xl mx-auto">

            {{-- Ucapan Selamat Datang --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Selamat Datang,
                        {{ explode(' ', Auth::user()->name ?? 'Administrator')[0] }}!
                    </h2>
                    <p class="text-gray-500 text-sm">Ini adalah pusat kendali sistem SINDESA. Pantau dan kelola data
                        master desa di sini.</p>
                </div>
                <div
                    class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center gap-3 text-sm font-medium text-gray-600">
                    <i class="fas fa-calendar-alt text-[#cfa03f]"></i>
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </div>

            {{-- Grid Counter --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('admin.data-warga') }}"
                    class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out">
                    </div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Warga</p>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ number_format($totalWarga ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div
                        class="mt-4 flex items-center text-xs text-blue-600 font-semibold group-hover:translate-x-1 transition-transform">
                        Kelola Warga <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                </a>

                <a href="{{ route('admin.data-operator') }}"
                    class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out">
                    </div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Operator</p>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ number_format($totalOperator ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div
                        class="mt-4 flex items-center text-xs text-purple-600 font-semibold group-hover:translate-x-1 transition-transform">
                        Kelola Operator <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                </a>

                <a href="{{ route('admin.data-kades') }}"
                    class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out">
                    </div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Kepala Desa</p>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ number_format($totalKades ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-100 text-[#cfa03f] flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div
                        class="mt-4 flex items-center text-xs text-[#cfa03f] font-semibold group-hover:translate-x-1 transition-transform">
                        Kelola Kades <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                </a>

                <a href="{{ route('admin.kelola-surat') }}"
                    class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all relative overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out">
                    </div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Jenis Surat</p>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ number_format($totalJenisSurat ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-100 text-[#1a5e35] flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-file-contract"></i>
                        </div>
                    </div>
                    <div
                        class="mt-4 flex items-center text-xs text-[#1a5e35] font-semibold group-hover:translate-x-1 transition-transform">
                        Konfigurasi Surat <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 flex flex-col gap-6">

                    {{-- Akses Cepat --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Akses Cepat</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="{{ route('admin.pusat-bantuan') }}"
                                class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-[#cfa03f] hover:bg-[#cfa03f]/5 transition-all group">
                                <div
                                    class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 group-hover:bg-[#cfa03f] group-hover:text-white transition-colors mr-3">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Pusat Bantuan</h4>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Kelola FAQ & Tutorial</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.pengaturan') }}"
                                class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-[#1a5e35] hover:bg-[#1a5e35]/5 transition-all group">
                                <div
                                    class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-500 group-hover:bg-[#1a5e35] group-hover:text-white transition-colors mr-3">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm">Pengaturan Sistem</h4>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Identitas Desa & Database</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- List Warga Terbaru --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-1">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                            <h3 class="text-lg font-bold text-gray-800">Warga Baru Mendaftar</h3>
                            <a href="{{ route('admin.data-warga') }}"
                                class="text-sm text-[#1a5e35] hover:underline font-medium">Lihat Semua</a>
                        </div>

                        <div class="space-y-4">
                            @forelse($wargaTerbaru as $warga)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm overflow-hidden shadow-sm border border-gray-100 shrink-0">
                                            @if($warga->foto_profil)
                                                <img src="{{ asset('storage/' . $warga->foto_profil) }}"
                                                    alt="{{ $warga->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($warga->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm line-clamp-1">{{ $warga->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 font-mono">
                                                {{ $warga->nik ?? 'NIK Belum Diisi' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span
                                            class="text-[11px] text-gray-400 block">{{ $warga->created_at->diffForHumans() }}</span>
                                        <span
                                            class="text-[10px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">Warga</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-400 py-4">
                                    <i class="fas fa-users-slash text-2xl mb-2"></i>
                                    <p class="text-sm">Belum ada warga yang mendaftar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Status Sistem Panel --}}
                <div class="bg-gradient-to-br from-[#1a5e35] to-[#11442b] rounded-2xl shadow-sm p-6 text-white relative overflow-hidden h-fit">
                    <i class="fas fa-server absolute -right-6 -bottom-6 text-8xl opacity-10"></i>

                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-[#cfa03f]"></i> Status Sistem
                    </h3>

                    <ul class="space-y-5 relative z-10">
                        <li class="flex justify-between items-center border-b border-white/20 pb-3">
                            <span class="text-sm text-white/80">Versi Aplikasi</span>
                            {{-- Menggunakan teks manual atau bisa diganti env('APP_VERSION', 'v1.0.0') --}}
                            <span class="text-sm font-bold bg-white/20 px-2 py-0.5 rounded">v1.0.0</span>
                        </li>
                        <li class="flex justify-between items-center border-b border-white/20 pb-3">
                            <span class="text-sm text-white/80">Lingkungan Sistem</span>
                            <span class="text-sm font-bold capitalize text-blue-200">{{ env('APP_ENV', 'production') }}</span>
                        </li>
                        <li class="flex justify-between items-center border-b border-white/20 pb-3">
                            <span class="text-sm text-white/80">Status Server</span>
                            @if(app()->isDownForMaintenance())
                                <span class="text-sm font-bold text-red-300 flex items-center gap-1"><i class="fas fa-tools text-[10px]"></i> Maintenance</span>
                            @else
                                <span class="text-sm font-bold text-green-300 flex items-center gap-1"><i class="fas fa-circle text-[8px] animate-pulse"></i> Online</span>
                            @endif
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-sm text-white/80">Penyimpanan Cache</span>
                            <span class="text-sm font-bold text-[#cfa03f] flex items-center gap-1">
                                <i class="fas fa-hdd text-[10px]"></i> Aktif
                            </span>
                        </li>
                    </ul>

                    <div class="mt-8 pt-4 border-t border-white/20">
                        <p class="text-[11px] text-white/70 leading-relaxed italic">
                            Catatan: Segala perubahan master data (Warga, Operator, Kades) oleh Administrator akan
                            terekam dalam audit log. Harap berhati-hati dalam melakukan penghapusan akun permanen.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }
    </script>

    @include('partials.sweetalert')
</body>

</html>
