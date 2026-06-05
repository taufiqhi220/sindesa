<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pusat Bantuan - SINDESA</title>
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

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-8 lg:ml-0 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        {{-- Header --}}
        <div class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2"><i class="fas fa-life-ring mr-3"></i>Pusat Bantuan</h1>
                <p class="opacity-90 max-w-xl">Panduan lengkap penggunaan layanan SINDESA untuk warga Desa. Temukan jawaban atas pertanyaan Anda di bawah ini.</p>
            </div>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
            <div class="absolute -right-4 -bottom-16 w-36 h-36 bg-white/5 rounded-full"></div>
        </div>

        {{-- Quick Navigation --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="#panduan-pengajuan" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors"><i class="fas fa-paper-plane"></i></div>
                <p class="text-sm font-semibold text-gray-700">Pengajuan Surat</p>
            </a>
            <a href="#panduan-status" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors"><i class="fas fa-tasks"></i></div>
                <p class="text-sm font-semibold text-gray-700">Cek Status</p>
            </a>
            <a href="#panduan-profil" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-amber-600 group-hover:text-white transition-colors"><i class="fas fa-user-edit"></i></div>
                <p class="text-sm font-semibold text-gray-700">Kelola Profil</p>
            </a>
            <a href="#faq" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors"><i class="fas fa-question-circle"></i></div>
                <p class="text-sm font-semibold text-gray-700">FAQ</p>
            </a>
        </div>

        {{-- Panduan Pengajuan Surat --}}
        <div id="panduan-pengajuan" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-paper-plane"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Cara Mengajukan Surat</h2>
            </div>

            <div class="guide-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Pilih Jenis Surat</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Klik menu <strong>"Buat Surat Baru"</strong> di sidebar kiri. Pilih kategori surat yang sesuai (Kependudukan, Umum, Perizinan, atau Sosial & Ekonomi).</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Isi Formulir</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Lengkapi semua data yang diminta pada formulir. Pastikan data sesuai dengan dokumen resmi Anda. Unggah dokumen pendukung (KTP, KK, dll).</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Kirim Pengajuan</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Centang persetujuan, lalu klik tombol <strong>"Kirim Pengajuan"</strong>. Surat Anda akan masuk ke antrian verifikasi Operator Desa.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-5 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2">Tunggu & Cetak</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Pantau status surat di menu <strong>"Riwayat & Status"</strong>. Setelah surat berstatus <em>Selesai</em>, Anda bisa langsung mengunduh file PDF-nya.</p>
                </div>
            </div>
        </div>

        {{-- Panduan Status Surat --}}
        <div id="panduan-status" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-tasks"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Memahami Status Surat</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-xs font-bold whitespace-nowrap mt-0.5">Menunggu Verifikasi</span>
                    <p class="text-sm text-gray-600">Surat Anda telah berhasil dikirim dan sedang menunggu untuk diperiksa oleh <strong>Operator Desa</strong>. Anda masih bisa mengedit atau membatalkan surat pada tahap ini.</p>
                </div>
                <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold whitespace-nowrap mt-0.5">Menunggu TTE Kades</span>
                    <p class="text-sm text-gray-600">Surat Anda telah <strong>lolos verifikasi</strong> Operator dan sedang diteruskan ke Kepala Desa untuk ditandatangani secara elektronik. Surat tidak bisa diedit lagi.</p>
                </div>
                <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-xs font-bold whitespace-nowrap mt-0.5">Selesai</span>
                    <p class="text-sm text-gray-600">Surat Anda telah <strong>ditandatangani Kepala Desa</strong> dan siap untuk diunduh. Klik tombol <em>"Cetak PDF"</em> pada halaman Riwayat untuk mengunduh surat resmi Anda.</p>
                </div>
                <div class="flex items-start gap-4 p-4 bg-red-50 rounded-xl border border-red-100">
                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-bold whitespace-nowrap mt-0.5">Ditolak</span>
                    <p class="text-sm text-gray-600">Surat Anda ditolak oleh Operator/Kepala Desa. Anda bisa melihat alasan penolakan dan <strong>merevisi data</strong>, lalu mengirim ulang pengajuan.</p>
                </div>
            </div>
        </div>

        {{-- Panduan Profil --}}
        <div id="panduan-profil" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-user-edit"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Mengelola Profil & Akun</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-id-card text-[#cfa03f] mr-2"></i>Lengkapi Data Diri</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Buka menu <strong>"Profil & Akun"</strong> di sidebar. Pastikan data NIK, No. KK, alamat, dan data lainnya sudah benar karena akan otomatis terisi di formulir pengajuan surat.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-camera text-[#cfa03f] mr-2"></i>Ubah Foto Profil</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Anda bisa mengunggah foto profil pada halaman Profil. Format yang diterima: JPG, JPEG, atau PNG dengan ukuran maksimal 2MB.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-lock text-[#cfa03f] mr-2"></i>Ubah Password</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Untuk keamanan, Anda disarankan mengubah password secara berkala. Masukkan password baru minimal 8 karakter dan konfirmasi ulang.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-shield-alt text-[#cfa03f] mr-2"></i>Verifikasi Akun</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Akun baru harus diverifikasi oleh Admin Desa. Selama status <em>"Menunggu Verifikasi"</em>, Anda belum bisa mengajukan surat. Pastikan data NIK dan KK sudah benar.</p>
                </div>
            </div>
        </div>

        {{-- Jenis Surat Tersedia --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#cfa03f]/10 text-[#cfa03f] rounded-xl flex items-center justify-center text-lg"><i class="fas fa-file-alt"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Jenis Surat yang Tersedia</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $daftarSurat = [
                        ['icon' => 'fa-baby', 'nama' => 'Pengantar Akta Kelahiran', 'desc' => 'Untuk mengurus pembuatan akta kelahiran di Disdukcapil.'],
                        ['icon' => 'fa-id-card', 'nama' => 'Pengantar KTP', 'desc' => 'Untuk pembuatan atau pembaharuan KTP elektronik.'],
                        ['icon' => 'fa-users', 'nama' => 'Pengantar Kartu Keluarga', 'desc' => 'Untuk pembuatan KK baru, perubahan, atau pecah KK.'],
                        ['icon' => 'fa-book-dead', 'nama' => 'Keterangan Kematian', 'desc' => 'Surat keterangan kematian untuk keperluan administrasi.'],
                        ['icon' => 'fa-truck-moving', 'nama' => 'Keterangan Pindah', 'desc' => 'Untuk pindah domisili ke daerah lain.'],
                        ['icon' => 'fa-map-marked-alt', 'nama' => 'Keterangan Domisili', 'desc' => 'Menyatakan bahwa Anda berdomisili di desa ini.'],
                        ['icon' => 'fa-ring', 'nama' => 'Keterangan Belum Menikah', 'desc' => 'Menyatakan status belum menikah.'],
                        ['icon' => 'fa-user-slash', 'nama' => 'Keterangan Janda/Duda', 'desc' => 'Menyatakan status janda atau duda.'],
                        ['icon' => 'fa-exchange-alt', 'nama' => 'Keterangan Beda Nama', 'desc' => 'Jika ada perbedaan nama di dokumen resmi.'],
                        ['icon' => 'fa-search', 'nama' => 'Keterangan Kehilangan', 'desc' => 'Untuk melapor kehilangan dokumen atau barang.'],
                        ['icon' => 'fa-user-shield', 'nama' => 'Pengantar SKCK', 'desc' => 'Pengantar untuk mengurus SKCK di Kepolisian.'],
                        ['icon' => 'fa-store', 'nama' => 'Keterangan Usaha', 'desc' => 'Menyatakan kepemilikan usaha di wilayah desa.'],
                        ['icon' => 'fa-bullhorn', 'nama' => 'Izin Keramaian', 'desc' => 'Untuk acara yang mengundang keramaian publik.'],
                        ['icon' => 'fa-hands-helping', 'nama' => 'Keterangan Tidak Mampu', 'desc' => 'Surat SKTM untuk keperluan bantuan sosial.'],
                        ['icon' => 'fa-money-bill-wave', 'nama' => 'Keterangan Penghasilan', 'desc' => 'Menyatakan jumlah penghasilan untuk keperluan tertentu.'],
                    ];
                @endphp

                @foreach($daftarSurat as $surat)
                    <div class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-emerald-50 text-[#1a5e35] rounded-lg flex items-center justify-center text-sm shrink-0">
                            <i class="fas {{ $surat['icon'] }}"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 text-sm">{{ $surat['nama'] }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $surat['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FAQ --}}
        <div id="faq" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-question-circle"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Pertanyaan Umum (FAQ)</h2>
            </div>

            <div class="space-y-3">
                @php
                    $faqs = [
                        ['q' => 'Apakah saya harus datang ke kantor desa untuk mengambil surat?', 'a' => 'Tidak. Setelah surat Anda berstatus "Selesai", Anda cukup mengunduh file PDF-nya melalui menu Riwayat. Surat sudah memiliki tanda tangan elektronik Kepala Desa dan QR Code verifikasi yang sah.'],
                        ['q' => 'Berapa lama proses pengajuan surat?', 'a' => 'Proses biasanya memakan waktu 1-3 hari kerja, tergantung antrian verifikasi Operator dan ketersediaan Kepala Desa untuk menandatangani surat.'],
                        ['q' => 'Bagaimana jika surat saya ditolak?', 'a' => 'Anda akan melihat alasan penolakan di halaman Riwayat. Klik "Edit / Detail" pada surat yang ditolak, perbaiki data yang diminta, lalu kirim ulang. Surat akan masuk antrian verifikasi kembali.'],
                        ['q' => 'Apakah data pribadi saya aman?', 'a' => 'Ya. SINDESA menggunakan enkripsi dan sistem autentikasi untuk melindungi data Anda. Hanya Anda, Operator, dan Kepala Desa yang bisa mengakses data pengajuan.'],
                        ['q' => 'Format file apa saja yang diterima untuk upload dokumen?', 'a' => 'Sistem menerima file berformat PDF, JPG, JPEG, dan PNG. Ukuran maksimal setiap file adalah 5MB.'],
                        ['q' => 'Akun saya masih berstatus "Menunggu Verifikasi", apa yang harus dilakukan?', 'a' => 'Pastikan data NIK dan No. KK yang Anda daftarkan sudah benar. Admin Desa akan memvalidasi data Anda. Jika sudah lama menunggu, silakan hubungi Kantor Desa secara langsung.'],
                        ['q' => 'Bisakah saya membatalkan pengajuan surat?', 'a' => 'Ya, selama status surat masih "Menunggu Verifikasi", Anda bisa membatalkan pengajuan melalui halaman Riwayat. Setelah surat diproses Operator, pembatalan tidak bisa dilakukan.'],
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

        {{-- Kontak Bantuan --}}
        <div class="bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] rounded-2xl p-6 md:p-8 text-white">
            <h3 class="text-lg font-bold mb-2"><i class="fas fa-headset mr-2"></i>Masih Butuh Bantuan?</h3>
            <p class="text-sm opacity-90 mb-4">Jika Anda masih mengalami kendala, silakan hubungi Kantor Desa secara langsung untuk mendapatkan bantuan lebih lanjut.</p>
            <div class="flex flex-wrap gap-4">
                <div class="bg-white/10 border border-white/20 rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-[#cfa03f]"></i>
                    <span class="text-sm">Kantor Desa Buttu Sawe</span>
                </div>
                <div class="bg-white/10 border border-white/20 rounded-xl px-4 py-3 flex items-center gap-3">
                    <i class="fas fa-clock text-[#cfa03f]"></i>
                    <span class="text-sm">Senin - Jumat, 08:00 - 16:00 WITA</span>
                </div>
            </div>
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

        function toggleFaq(index) {
            const answer = document.getElementById('faq-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            answer.classList.toggle('open');
            icon.classList.toggle('rotate');
        }
    </script>
    @include('partials.sweetalert')
</body>

</html>
