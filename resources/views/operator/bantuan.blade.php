<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pusat Bantuan Operator - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --primary-green: #1a5e35; --gold-accent: #cfa03f; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .sidebar-sindesa { background: linear-gradient(180deg, #11442b 0%, #1a5e35 100%); }
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

<body class="flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar Inline (sama dengan operator/dashboard.blade.php) --}}
    <aside id="sidebar"
        class="fixed lg:static inset-y-0 left-0 w-[280px] sidebar-sindesa text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 shrink-0">
            <div class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto" class="w-full h-full object-cover">
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
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all">
                <i class="fas fa-inbox w-5 text-center"></i> Verifikasi Masuk
            </a>
            <a href="{{ route('operator.menunggu-ttd') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>
            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>
            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
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
            <a href="{{ route('operator.bantuan') }}"
                class="flex items-center gap-3 p-3 bg-[#cfa03f] text-white font-medium shadow-md rounded-lg transition-all">
                <i class="fas fa-question-circle w-5 text-center"></i> Pusat Bantuan
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

        {{-- Header --}}
        <div class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2"><i class="fas fa-life-ring mr-3"></i>Pusat Bantuan Operator</h1>
                <p class="opacity-90 max-w-xl">Panduan lengkap tugas dan fitur yang tersedia untuk peran Operator (Petugas Verifikator) di sistem SINDESA.</p>
            </div>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        </div>

        {{-- Panduan Verifikasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-clipboard-check"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Alur Verifikasi Surat</h2>
            </div>

            <div class="guide-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Terima Pengajuan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Buka menu <strong>"Verifikasi Masuk"</strong>. Surat dari warga akan muncul secara otomatis beserta notifikasi jumlah surat baru.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Periksa Data</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Klik <strong>"Proses"</strong> untuk melihat detail surat. Periksa kesesuaian data warga, kelengkapan dokumen pendukung, dan kevalidan informasi.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Setujui / Tolak</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Jika data valid, isi nomor surat lalu klik <strong>"Setujui & Teruskan ke Kades"</strong>. Jika data tidak lengkap, klik <strong>"Tolak"</strong> dengan memberikan alasan penolakan.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Pantau Status</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Surat yang sudah disetujui akan masuk ke <strong>"Menunggu TTD"</strong>. Setelah Kades menandatangani, surat pindah ke <strong>"Riwayat Surat"</strong>.</p>
                </div>
            </div>
        </div>

        {{-- Menu-menu Operator --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-th-list"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Penjelasan Fitur Menu</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-inbox"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Verifikasi Masuk</h4>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan semua pengajuan surat baru dari warga yang belum diproses. Badge merah menunjukkan jumlah surat baru yang belum dibaca.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-file-signature"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Menunggu TTD</h4>
                        <p class="text-xs text-gray-500 mt-1">Daftar surat yang sudah Anda verifikasi dan sekarang sedang menunggu tanda tangan elektronik dari Kepala Desa. Anda bisa menarik kembali surat jika diperlukan.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-history"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Riwayat Surat</h4>
                        <p class="text-xs text-gray-500 mt-1">Arsip semua surat yang sudah selesai diproses, lengkap dengan data rekapitulasi dan grafik. Anda bisa mencetak laporan dalam format PDF.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-red-50 rounded-xl border border-red-100">
                    <div class="w-9 h-9 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Surat Ditolak</h4>
                        <p class="text-xs text-gray-500 mt-1">Daftar surat yang sudah ditolak oleh Anda atau Kepala Desa beserta alasan penolakan.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-9 h-9 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-cog"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Pengaturan Surat & Akun</h4>
                        <p class="text-xs text-gray-500 mt-1">Atur template catatan surat, ubah foto profil, dan perbarui password akun Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ Operator --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-question-circle"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Pertanyaan Umum (FAQ)</h2>
            </div>

            <div class="space-y-3">
                @php
                    $faqs = [
                        ['q' => 'Apa yang harus saya periksa saat verifikasi surat?', 'a' => 'Pastikan: (1) Data identitas warga sesuai KTP/KK, (2) Dokumen pendukung yang diunggah lengkap dan jelas, (3) Jenis surat sesuai dengan keperluan yang dimaksud.'],
                        ['q' => 'Bagaimana cara menarik kembali surat yang sudah diteruskan ke Kades?', 'a' => 'Buka menu "Menunggu TTD", cari surat yang ingin ditarik, lalu klik tombol "Tarik Kembali". Surat akan kembali ke status verifikasi.'],
                        ['q' => 'Bagaimana format penomoran surat?', 'a' => 'Format nomor surat mengikuti standar administrasi desa. Contoh: 045/DS-BS/VI/2026. Pastikan nomor tidak duplikat dengan surat lain.'],
                        ['q' => 'Apakah saya bisa mencetak laporan rekapitulasi?', 'a' => 'Ya. Buka menu "Riwayat Surat", pilih rentang tanggal, lalu klik tombol "Cetak Laporan". Laporan akan diunduh dalam format PDF.'],
                        ['q' => 'Apa yang terjadi jika saya salah menolak surat?', 'a' => 'Surat yang ditolak akan dikembalikan ke warga untuk direvisi. Warga bisa memperbaiki dan mengirim ulang. Anda bisa memverifikasi ulang surat tersebut.'],
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
            <p class="text-sm opacity-90 mb-4">Jika Anda mengalami kendala teknis pada sistem, hubungi Administrator SINDESA untuk mendapatkan bantuan.</p>
            <div class="flex flex-wrap gap-4">
                <div class="bg-white/10 border border-white/20 rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fas fa-user-shield text-[#cfa03f]"></i>
                    <span class="text-sm">Hubungi Administrator</span>
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
        function toggleFaq(index) {
            const answer = document.getElementById('faq-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            answer.classList.toggle('open');
            icon.classList.toggle('rotate');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.sweetalert')
</body>

</html>
