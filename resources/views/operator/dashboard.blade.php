<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Dashboard Operator - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-green: #1a5e35;
            --secondary-green: #2e7d32;
            --sidebar-bg: linear-gradient(180deg, #11442b 0%, #1a5e35 100%);
            --gold-accent: #cfa03f;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        /* Sidebar Gradasi Asli */
        .sidebar-sindesa {
            background: var(--sidebar-bg);
        }

        /* Stat Card dengan Border Kiri */
        .stat-card {
            background: white;
            padding: 1.25rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 6px solid;
            transition: 0.3s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Icon Box di Stats */
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
</head>

<body class="flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed lg:static inset-y-0 left-0 w-[280px] sidebar-sindesa text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 shrink-0">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto {{ Auth::user()->name }}"
                        class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Operator' }}</h4>
                <p class="text-[10px] opacity-70">Petugas Verifikator</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1">
            <a href="{{ route('operator.dashboard') }}"
                class="flex items-center gap-3 p-3 bg-[#cfa03f] rounded-lg text-white font-medium mb-2 shadow-md">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center justify-between p-3 {{ request()->routeIs('operator.verifikasi') ? 'bg-[#cfa03f] text-white' : 'text-white/80' }} hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-inbox w-5 text-center"></i> Verifikasi Masuk
                </div>

                {{-- Notifikasi Angka --}}
                @if($unreadCount > 0)
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('operator.menunggu-ttd') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>

            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.ditolak*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-times-circle w-5 text-center"></i> Surat Ditolak
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('operator.pengaturan-surat') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all">
                <i class="fas fa-file-contract w-5 text-center"></i> Pengaturan Surat
            </a>

            <a href="{{ route('operator.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all font-medium cursor-pointer">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 min-w-0 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div
            class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <h2 class="text-3xl font-bold mb-2">Halo, {{ Auth::user()->name ?? 'Solihin' }}!</h2>
            <p class="opacity-90 max-w-xl leading-relaxed">Selamat bekerja! Berikut adalah ringkasan aktivitas
                verifikasi dan layanan administrasi desa hari ini.</p>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('operator.verifikasi') }}" class="stat-card" style="border-left-color: #f59e0b;">
                <div class="icon-box bg-[#f59e0b1a] text-[#f59e0b]"><i class="fas fa-file-import"></i></div>
                <div class="text-right leading-tight">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $countMasuk }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Permintaan Masuk</p>
                </div>
            </a>

            <a href="{{ route('operator.menunggu-ttd') }}" class="stat-card" style="border-left-color: #3b82f6;">
                <div class="icon-box bg-[#3b82f61a] text-[#3b82f6]"><i class="fas fa-file-signature"></i></div>
                <div class="text-right leading-tight">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $countMenungguTtd }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Menunggu TTD Kades</p>
                </div>
            </a>

            <a href="{{ route('operator.riwayat') }}" class="stat-card" style="border-left-color: #10b981;">
                <div class="icon-box bg-[#10b9811a] text-[#10b981]"><i class="fas fa-check-circle"></i></div>
                <div class="text-right leading-tight">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $countSelesai }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Surat Selesai</p>
                </div>
            </a>

            <a href="{{ route('operator.ditolak') }}" class="stat-card" style="border-left-color: #ef4444;">
                <div class="icon-box bg-[#ef44441a] text-[#ef4444]"><i class="fas fa-times-circle"></i></div>
                <div class="text-right leading-tight">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $countDitolak }}</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Pengajuan Ditolak</p>
                </div>
            </a>

            <a href="{{ route('operator.bantuan') }}" class="stat-card" style="border-left-color: #8b5cf6;">
                <div class="icon-box bg-[#8b5cf61a] text-[#8b5cf6]"><i class="fas fa-question-circle"></i></div>
                <div class="text-right leading-tight">
                    <h3 class="text-2xl font-bold text-gray-800">Help</h3>
                    <p class="text-[11px] text-gray-400 font-medium mt-1">Pusat Bantuan</p>
                </div>
            </a>

        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Permintaan Surat Terbaru</h3>
                <a href="{{ route('operator.riwayat') }}"
                    class="text-[11px] font-bold text-[--primary-green] hover:underline flex items-center gap-1 uppercase">
                    Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-3 items-center">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mr-2"><i
                            class="fas fa-info-circle mr-1"></i> Kategori:</span>
                    <span
                        class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded text-[10px] font-bold">Kependudukan</span>
                    <span
                        class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded text-[10px] font-bold">Sosial/Ekonomi</span>
                    <span
                        class="bg-orange-50 text-orange-600 border border-orange-100 px-2 py-0.5 rounded text-[10px] font-bold">Perizinan</span>
                    <span
                        class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded text-[10px] font-bold">Umum</span>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 text-[10px] text-gray-400 uppercase tracking-widest font-bold border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-4">No</th>
                            <th class="py-4 px-4">Waktu</th>
                            <th class="py-4 px-4">Pemohon</th>
                            <th class="py-4 px-4">Layanan</th>
                            <th class="py-4 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($suratTerbaru as $index => $surat)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                <td class="py-4 px-4 font-bold text-gray-500">{{ $index + 1 }}</td>
                                <td class="py-4 px-4 text-gray-500">{{ $surat->created_at->format('H:i') }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3 font-bold text-gray-800">
                                        <i class="fas fa-user-circle text-xl text-gray-300"></i>
                                        <span class="break-words">{{ $surat->user->name ?? 'Warga' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Jenis Surat dengan Warna Dinamis --}}
                                <td class="py-4 px-4">
                                    @php
                                        $adminduk = ['pengantar_akta_lahir', 'pengantar_ktp', 'pengantar_kk', 'keterangan_kematian', 'keterangan_pindah'];
                                        $sosial = ['keterangan_tidak_mampu', 'keterangan_penghasilan'];
                                        $perizinan = ['keterangan_usaha', 'izin_keramaian', 'keterangan_kehilangan'];

                                        if (in_array($surat->jenis_surat, $adminduk)) {
                                            $colorClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                                        } elseif (in_array($surat->jenis_surat, $sosial)) {
                                            $colorClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                        } elseif (in_array($surat->jenis_surat, $perizinan)) {
                                            $colorClass = 'bg-orange-50 text-orange-600 border border-orange-100';
                                        } else {
                                            $colorClass = 'bg-purple-50 text-purple-600 border border-purple-100';
                                        }
                                    @endphp

                                    <span
                                        class="{{ $colorClass }} inline-block whitespace-normal leading-relaxed px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider max-w-[120px] md:max-w-none">
                                        {{ ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('operator.verifikasi.show', $surat->id) }}"
                                        class="inline-flex bg-[#1a5e35] hover:bg-[#2e7d32] text-white px-4 py-2 rounded-xl text-[10px] font-bold items-center gap-2 mx-auto whitespace-nowrap transition-colors">
                                        <i class="fas fa-bolt"></i> PROSES
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-gray-400 italic">
                                    Tidak ada permintaan surat baru saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.sweetalert')
</body>

</html>