<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pusat Bantuan Kepala Desa - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease; }
        .faq-answer.open { max-height: 600px; padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .faq-icon { transition: transform 0.3s ease; }
        .faq-icon.rotate { transform: rotate(45deg); }
        .step-card { counter-increment: step-counter; }
        .step-card::before {
            content: counter(step-counter);
            position: absolute; top: -12px; left: -12px;
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #1a5e35, #2e7d32);
            color: white; font-weight: 700; font-size: 14px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(26, 94, 53, 0.3);
        }
        .guide-section { counter-reset: step-counter; }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5 shrink-0">
            <div class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil" class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'H', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Kepala Desa' }}</h4>
                <p class="text-[10px] opacity-70">Kepala Desa</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 text-white/80 hover:bg-[#cfa03f] hover:text-white">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 text-white/80 hover:bg-[#cfa03f] hover:text-white">
                <i class="fas fa-file-signature w-5 text-center"></i> Perlu Tanda Tangan
            </a>
            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 text-white/80 hover:bg-[#cfa03f] hover:text-white">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>
            <a href="{{ route('kades.bantuan') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 bg-[#cfa03f] text-white shadow-md font-medium">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 text-white/80 hover:bg-[#cfa03f] hover:text-white">
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

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        {{-- Header --}}
        <div class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2"><i class="fas fa-life-ring mr-3"></i>Pusat Bantuan Kepala Desa</h1>
                <p class="opacity-90 max-w-xl">Panduan lengkap penggunaan sistem SINDESA untuk Kepala Desa. Pelajari cara menandatangani surat, melihat riwayat, dan mengelola akun.</p>
            </div>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        </div>

        {{-- Alur Tanda Tangan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-pen-nib"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Cara Menandatangani Surat</h2>
            </div>

            <div class="guide-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Buka Menu Perlu TTD</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Klik menu <strong>"Perlu Tanda Tangan"</strong> di sidebar. Anda akan melihat daftar surat yang sudah diverifikasi Operator dan menunggu tanda tangan Anda.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Periksa Detail Surat</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Klik <strong>"Tanda Tangani"</strong> untuk melihat detail lengkap surat. Pastikan semua data sudah benar sebelum menandatangani.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Setujui atau Tolak</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Pilih metode tanda tangan, lalu klik <strong>"Setujui & Tanda Tangani"</strong>. Jika ada kekeliruan, Anda bisa menolak surat dengan menuliskan alasan penolakan.</p>
                </div>
            </div>
        </div>

        {{-- Menu Kades --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-th-list"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Penjelasan Fitur Menu</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-pen-nib"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Perlu Tanda Tangan</h4>
                        <p class="text-xs text-gray-500 mt-1">Daftar surat yang sudah diverifikasi Operator dan menunggu persetujuan serta tanda tangan Anda. Badge merah menunjukkan jumlah surat baru.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-history"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Riwayat Surat</h4>
                        <p class="text-xs text-gray-500 mt-1">Arsip semua surat yang sudah Anda tandatangani. Anda bisa mencari berdasarkan nama, jenis surat, atau filter berdasarkan bulan. Tersedia fitur cetak laporan bulanan dalam format PDF.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-9 h-9 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-cog"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Pengaturan Akun</h4>
                        <p class="text-xs text-gray-500 mt-1">Kelola profil Anda termasuk foto profil, data pribadi (nama, NIK, NIP), spesimen tanda tangan elektronik (format PNG), dan password akun.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panduan TTD Elektronik --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#cfa03f]/10 text-[#cfa03f] rounded-xl flex items-center justify-center text-lg"><i class="fas fa-signature"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Tentang Tanda Tangan Elektronik</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-upload text-[#cfa03f] mr-2"></i>Upload Spesimen</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Unggah gambar tanda tangan Anda melalui menu <strong>"Pengaturan Akun"</strong>. Format wajib PNG dengan latar belakang transparan untuk hasil cetak yang rapi.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-qrcode text-[#cfa03f] mr-2"></i>QR Code Verifikasi</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Setiap surat yang ditandatangani akan memiliki QR Code unik yang bisa dipindai untuk memverifikasi keaslian surat secara online.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-file-pdf text-[#cfa03f] mr-2"></i>Cetak Surat</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Surat yang sudah selesai bisa dicetak menjadi PDF resmi lengkap dengan kop surat, data warga, tanda tangan, dan QR Code.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-chart-bar text-[#cfa03f] mr-2"></i>Laporan Bulanan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Di menu Riwayat, Anda bisa mencetak laporan rekapitulasi surat bulanan untuk keperluan pelaporan ke instansi terkait.</p>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-question-circle"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Pertanyaan Umum (FAQ)</h2>
            </div>

            <div class="space-y-3">
                @php
                    $faqs = [
                        ['q' => 'Apakah tanda tangan saya aman di sistem?', 'a' => 'Ya. Spesimen tanda tangan Anda disimpan secara aman di server dan hanya digunakan untuk membubuhkan tanda tangan pada surat resmi desa yang Anda setujui.'],
                        ['q' => 'Bisakah saya menolak surat yang sudah diverifikasi Operator?', 'a' => 'Ya. Jika Anda menemukan kekeliruan data, klik "Tolak" dan tuliskan alasan penolakan. Surat akan dikembalikan ke warga untuk direvisi.'],
                        ['q' => 'Apa format gambar tanda tangan yang disarankan?', 'a' => 'Gunakan format PNG dengan latar belakang transparan. Ukuran maksimal 2MB. Anda bisa membuat tanda tangan digital menggunakan aplikasi atau memindai tanda tangan basah Anda.'],
                        ['q' => 'Bagaimana cara melihat laporan surat bulanan?', 'a' => 'Buka menu "Riwayat Surat", pilih bulan yang diinginkan menggunakan filter, lalu klik tombol "Cetak Laporan". Laporan akan dihasilkan dalam format PDF.'],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <button onclick="toggleFaq({{ $index }})" class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-sm text-gray-800 pr-4">{{ $faq['q'] }}</span>
                            <i id="faq-icon-{{ $index }}" class="fas fa-plus text-[#cfa03f] text-sm faq-icon shrink-0"></i>
                        </button>
                        <div id="faq-{{ $index }}" class="faq-answer px-4 text-sm text-gray-500 leading-relaxed border-t border-gray-50">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kontak --}}
        <div class="bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] rounded-2xl p-6 md:p-8 text-white">
            <h3 class="text-lg font-bold mb-2"><i class="fas fa-headset mr-2"></i>Butuh Bantuan Teknis?</h3>
            <p class="text-sm opacity-90">Jika Anda mengalami kendala teknis, silakan hubungi Administrator SINDESA untuk mendapatkan bantuan.</p>
        </div>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        function toggleFaq(index) {
            const answer = document.getElementById('faq-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            answer.classList.toggle('open');
            icon.classList.toggle('rotate');
        }
    </script>
</body>

</html>
