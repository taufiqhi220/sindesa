<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Dashboard Kepala Desa - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5 shrink-0">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
                        class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'H', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'H. Burhanuddin' }}</h4>
                <p class="text-[10px] opacity-70">Kepala Desa</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.dashboard') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.perlu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-signature w-5 text-center"></i> Perlu Tanda Tangan
                </div>
                @if(isset($unreadCountKades) && $unreadCountKades > 0) <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $unreadCountKades }}</span> @endif
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 text-white/80 hover:bg-[#cfa03f] hover:text-white">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan
            </div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">

        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Dashboard Kepala Desa</h2>
            <p class="text-gray-500">Selamat Datang, Bapak Kepala Desa. Ada <strong
                    class="text-gray-700">{{ $countPerluTtd }} surat</strong> menunggu tanda tangan Anda hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <a href="{{ route('kades.perlu-ttd') }}"
                class="bg-white rounded-2xl p-6 shadow-[0_5px_20px_rgba(0,0,0,0.05)] border-l-4 border-amber-500 flex justify-between items-center hover:-translate-y-1 transition-transform cursor-pointer group">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:text-amber-600 transition-colors">
                        {{ $countPerluTtd }}</h3>
                    <p class="text-sm text-gray-500 font-medium">Perlu Tanda Tangan</p>
                </div>
                <div
                    class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-pen-nib"></i>
                </div>
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="bg-white rounded-2xl p-6 shadow-[0_5px_20px_rgba(0,0,0,0.05)] border-l-4 border-emerald-500 flex justify-between items-center hover:-translate-y-1 transition-transform cursor-pointer group">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:text-emerald-600 transition-colors">
                        {{ $countTtdHariIni }}</h3>
                    <p class="text-sm text-gray-500 font-medium">Ditandatangani Hari Ini</p>
                </div>
                <div
                    class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-double"></i>
                </div>
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="bg-white rounded-2xl p-6 shadow-[0_5px_20px_rgba(0,0,0,0.05)] border-l-4 border-blue-500 flex justify-between items-center hover:-translate-y-1 transition-transform cursor-pointer group">
                <div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">
                        {{ $countSuratBulanIni }}</h3>
                    <p class="text-sm text-gray-500 font-medium">Total Surat Bulan Ini</p>
                </div>
                <div
                    class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-open"></i>
                </div>
            </a>

        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Dokumen Menunggu Persetujuan</h3>
                <a href="{{ route('kades.perlu-ttd') }}"
                    class="text-sm text-[#1a5e35] font-semibold hover:underline">Lihat Semua <i
                        class="fas fa-arrow-right ml-1"></i></a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Masuk</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                                Pemohon</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis
                                Surat</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[0.95rem] text-gray-800">
                        @forelse($suratTerbaru as $index => $surat)
                            @php
                                // Perbaikan: Menambahkan formatting Jenis Surat seperti di Riwayat
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

                                $namaSuratLengkap = [
                                    'pengantar_akta_lahir' => 'Surat Pengantar Akta Lahir',
                                    'pengantar_ktp' => 'Surat Pengantar Pembuatan KTP',
                                    'pengantar_kk' => 'Surat Pengantar Kartu Keluarga (KK)',
                                    'keterangan_kematian' => 'Surat Keterangan Kematian',
                                    'keterangan_pindah' => 'Surat Keterangan Pindah Domisili',
                                    'keterangan_usaha' => 'Surat Keterangan Usaha (SKU)',
                                    'izin_keramaian' => 'Surat Izin Keramaian',
                                    'keterangan_kehilangan' => 'Surat Keterangan Kehilangan',
                                    'keterangan_tidak_mampu' => 'Surat Keterangan Tidak Mampu (SKTM)',
                                    'keterangan_penghasilan' => 'Surat Keterangan Penghasilan',
                                    'keterangan_beda_nama' => 'Surat Keterangan Beda Nama Identitas',
                                    'keterangan_belum_menikah' => 'Surat Keterangan Belum Menikah',
                                    'keterangan_janda_duda' => 'Surat Keterangan Status Janda/Duda',
                                    'pengantar_skck' => 'Surat Pengantar SKCK',
                                    'keterangan_domisili' => 'Surat Keterangan Domisili'
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    {{ $surat->updated_at->diffForHumans() }}
                                    <div class="text-[10px] text-gray-400">{{ $surat->updated_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 flex justify-center items-center font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($surat->user->name ?? 'W', 0, 1) }}
                                        </div>
                                        <span class="font-semibold">{{ $surat->user->name ?? 'Tanpa Nama' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="{{ $colorClass }} px-3 py-1.5 rounded-md text-xs font-bold inline-flex max-w-[220px] whitespace-normal leading-snug">
                                        {{ $namaSuratLengkap[$surat->jenis_surat] ?? ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('kades.surat.detail', ['type' => $surat->jenis_surat, 'id' => $surat->id]) }}"
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('kades.surat.detail', ['type' => $surat->jenis_surat, 'id' => $surat->id]) }}"
                                            class="bg-[#1a5e35] hover:bg-[#2e7d32] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors text-sm font-semibold shadow-sm border border-transparent">
                                            <i class="fas fa-file-signature"></i> Tanda Tangani
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-check-circle text-3xl mb-2 text-gray-300 block"></i>
                                    Tidak ada dokumen yang menunggu tanda tangan saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
</body>

</html>