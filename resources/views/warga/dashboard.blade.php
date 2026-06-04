<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Dashboard Warga - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-8 lg:ml-0 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <h2 class="text-3xl font-bold mb-2">Halo, {{ Auth::user()->name }}!</h2>
            <p class="opacity-90 max-w-xl">Selamat datang di Dashboard Layanan Mandiri Desa Buttu Sawe. Silakan pilih layanan surat yang Anda butuhkan di menu samping.</p>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        </div>

        @php 
            $statusUser = Auth::user()->status;
            $isActiveUser = $statusUser === 'active'; 
        @endphp

        @if($statusUser === 'inactive')
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl mb-8 flex gap-4 items-start shadow-sm">
                <i class="fas fa-user-clock text-amber-500 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-amber-800 text-sm">Akun Menunggu Verifikasi</h4>
                    <p class="text-amber-700 text-xs mt-1">Pengajuan surat Anda akan diproses setelah Admin memvalidasi NIK dan KK Anda.</p>
                </div>
            </div>
        @elseif($statusUser === 'suspended')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-8 flex gap-4 items-start shadow-sm">
                <i class="fas fa-ban text-red-500 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-red-800 text-sm">Akun Ditangguhkan</h4>
                    <p class="text-red-700 text-xs mt-1">Akun Anda ditangguhkan oleh Admin. Akses layanan surat dinonaktifkan. Silakan hubungi Kantor Desa.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <a href="{{ route('warga.riwayat') ?? '#' }}" class="bg-white p-6 rounded-2xl shadow-sm border-l-[6px] border-orange-500 hover:-translate-y-1 transition-transform flex items-center justify-between">
                <div class="w-14 h-14 bg-orange-500/10 text-orange-500 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-history"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPengajuan ?? 0 }}</h3>
                    <p class="text-sm text-gray-400 font-medium tracking-wide uppercase">Total Pengajuan</p>
                </div>
            </a>

            <a href="{{ route('warga.verifikasi') ?? '#' }}" class="bg-white p-6 rounded-2xl shadow-sm border-l-[6px] border-blue-500 hover:-translate-y-1 transition-transform flex items-center justify-between">
                <div class="w-14 h-14 bg-blue-500/10 text-blue-500 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $diproses ?? 0 }}</h3>
                    <p class="text-sm text-gray-400 font-medium tracking-wide uppercase">Diproses Operator</p>
                </div>
            </a>

            <a href="{{ route('warga.selesai') }}" class="bg-white p-6 rounded-2xl shadow-sm border-l-[6px] border-emerald-500 hover:-translate-y-1 transition-transform flex items-center justify-between">
                <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $selesai ?? 0 }}</h3>
                    <p class="text-sm text-gray-400 font-medium tracking-wide uppercase">Selesai</p>
                </div>
            </a>

            <a href="#" class="bg-white p-6 rounded-2xl shadow-sm border-l-[6px] border-[#cfa03f] hover:-translate-y-1 transition-transform flex items-center justify-between">
                <div class="w-14 h-14 bg-[#cfa03f]/10 text-[#cfa03f] rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-3xl font-bold text-gray-800">Help</h3>
                    <p class="text-sm text-gray-400 font-medium tracking-wide uppercase">Pusat Bantuan</p>
                </div>
            </a>
        </div>

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-layer-group text-[#cfa03f] mr-2"></i> Layanan Surat Sering Digunakan</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-10">
            @php
                $layananAktif = \App\Models\JenisSurat::where('is_active', 1)->take(4)->get();
            @endphp

            @forelse($layananAktif as $layanan)
                @php
                    $jenis = strtolower($layanan->nama_surat);
                    $routeSlug = '';
                    $icon = 'fa-file-alt';
                    
                    if (str_contains($jenis, 'akta')) { $routeSlug = 'akta-lahir'; $icon = 'fa-baby'; }
                    elseif (str_contains($jenis, 'ktp')) { $routeSlug = 'ktp'; $icon = 'fa-id-card'; }
                    elseif (str_contains($jenis, 'kk') || str_contains($jenis, 'keluarga')) { $routeSlug = 'kk'; $icon = 'fa-users'; }
                    elseif (str_contains($jenis, 'kematian')) { $routeSlug = 'kematian'; $icon = 'fa-book-dead'; }
                    elseif (str_contains($jenis, 'pindah')) { $routeSlug = 'pindah'; $icon = 'fa-truck-moving'; }
                    elseif (str_contains($jenis, 'domisili')) { $routeSlug = 'domisili'; $icon = 'fa-map-marked-alt'; }
                    elseif (str_contains($jenis, 'belum menikah') || str_contains($jenis, 'belum_menikah')) { $routeSlug = 'belum-menikah'; $icon = 'fa-ring'; }
                    elseif (str_contains($jenis, 'janda') || str_contains($jenis, 'duda')) { $routeSlug = 'janda-duda'; $icon = 'fa-user-slash'; }
                    elseif (str_contains($jenis, 'usaha')) { $routeSlug = 'usaha'; $icon = 'fa-store'; }
                    elseif (str_contains($jenis, 'tidak mampu') || str_contains($jenis, 'sktm')) { $routeSlug = 'tidak-mampu'; $icon = 'fa-hands-helping'; }
                    elseif (str_contains($jenis, 'penghasilan')) { $routeSlug = 'penghasilan'; $icon = 'fa-money-bill-wave'; }

                    $url = ($isActiveUser && $routeSlug != '') ? route('warga.form.' . $routeSlug) : 'javascript:void(0)';
                @endphp

                <a href="{{ $url }}"
                    @if(!$isActiveUser) onclick="showInactiveAlert()" @endif
                    class="bg-white group transition-all duration-300 p-6 rounded-2xl shadow-sm border border-gray-100 hover:bg-[#1a5e35] hover:shadow-lg hover:-translate-y-1 relative overflow-hidden flex flex-col h-full cursor-pointer">
                    
                    <div class="w-12 h-12 bg-emerald-50 group-hover:bg-white/20 text-[#1a5e35] group-hover:text-white rounded-xl flex items-center justify-center text-xl mb-4 transition-all duration-300">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    
                    <h3 class="font-bold text-gray-800 group-hover:text-white mb-2 transition-colors duration-300 line-clamp-1">
                        {{ $layanan->nama_surat }}
                    </h3>
                    
                    <p class="text-xs text-gray-500 group-hover:text-gray-100 transition-colors duration-300 line-clamp-2 flex-1">
                        {{ $layanan->deskripsi ?? 'Pengajuan untuk ' . $layanan->nama_surat }}
                    </p>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100 group-hover:border-white/20 text-xs font-semibold text-[#cfa03f] group-hover:text-white flex items-center gap-2 transition-colors duration-300">
                        Buat Surat <i class="fas fa-arrow-right text-[10px]"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full p-6 bg-white border border-gray-100 rounded-xl text-center text-gray-500 text-sm">
                    Belum ada layanan surat yang aktif.
                </div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Pengajuan Terakhir</h3>
                <a href="{{ route('warga.riwayat') ?? '#' }}" class="text-sm text-[#1a5e35] font-semibold hover:underline">Lihat Semua</a>
            </div>

            @if(isset($pengajuanTerbaru) && $pengajuanTerbaru->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Jenis Surat</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @foreach($pengajuanTerbaru as $index => $pengajuan)
                                @php
                                    $jenis = strtolower($pengajuan->jenis_surat);
                                    $routeSlug = '';
                                    
                                    if (str_contains($jenis, 'akta')) $routeSlug = 'akta-lahir';
                                    elseif (str_contains($jenis, 'ktp')) $routeSlug = 'ktp';
                                    elseif (str_contains($jenis, 'kk')) $routeSlug = 'kk';
                                    elseif (str_contains($jenis, 'kematian')) $routeSlug = 'kematian';
                                    elseif (str_contains($jenis, 'pindah')) $routeSlug = 'pindah';
                                    elseif (str_contains($jenis, 'domisili')) $routeSlug = 'domisili';
                                    elseif (str_contains($jenis, 'belum_menikah') || str_contains($jenis, 'belum menikah')) $routeSlug = 'belum-menikah';
                                    elseif (str_contains($jenis, 'janda') || str_contains($jenis, 'duda')) $routeSlug = 'janda-duda';
                                    elseif (str_contains($jenis, 'beda_nama') || str_contains($jenis, 'beda nama')) $routeSlug = 'beda-nama';
                                    elseif (str_contains($jenis, 'kehilangan')) $routeSlug = 'kehilangan';
                                    elseif (str_contains($jenis, 'skck')) $routeSlug = 'skck';
                                    elseif (str_contains($jenis, 'usaha')) $routeSlug = 'usaha';
                                    elseif (str_contains($jenis, 'keramaian')) $routeSlug = 'izin-keramaian';
                                    elseif (str_contains($jenis, 'tidak_mampu') || str_contains($jenis, 'sktm')) $routeSlug = 'tidak-mampu';
                                    elseif (str_contains($jenis, 'penghasilan')) $routeSlug = 'penghasilan';
                                @endphp

                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $pengajuan->jenis_surat) }}</td>
                                    <td class="px-4 py-4">{{ $pengajuan->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-4">
                                        @if($pengajuan->status == 'menunggu_verifikasi')
                                            <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-xs font-medium">Menunggu Verifikasi</span>
                                        @elseif($pengajuan->status == 'selesai')
                                            <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-xs font-medium">Selesai</span>
                                        @elseif($pengajuan->status == 'ditolak')
                                            <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-medium">Ditolak</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-medium">Menunggu TTE Kades</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($routeSlug != '')
                                            <a href="{{ route('warga.form.' . $routeSlug . '.edit', $pengajuan->id) }}" class="text-[#cfa03f] hover:text-[#b88e32] font-semibold text-xs transition-colors">
                                                <i class="fas fa-edit"></i> Edit / Detail
                                            </a>
                                        @else
                                            <a href="{{ route('warga.riwayat') }}" class="text-[#cfa03f] hover:text-[#b88e32] font-semibold text-xs transition-colors">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada pengajuan surat.</p>
                    <p class="text-xs text-gray-400 mt-1">Buat surat melalui menu di atas atau di sebelah kiri.</p>
                </div>
            @endif
        </div>

    </main>

    <script>
        function toggleMenu(menuId, element) {
            const submenu = document.getElementById(menuId);
            const icon = element.querySelector('.fa-chevron-down');

            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function showInactiveAlert() {
            const status = '{{ Auth::user()->status }}';
            const pesan = status === 'suspended' 
                ? 'Akun Anda ditangguhkan. Silakan hubungi Admin Desa.' 
                : 'Akun Anda belum diverifikasi oleh Admin.';

            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: pesan,
                confirmButtonColor: '#cfa03f'
            });
        }
    </script>
    @include('partials.sweetalert')
</body>

</html>