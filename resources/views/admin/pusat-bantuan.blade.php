<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pusat Bantuan Admin - SINDESA</title>
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

    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        {{-- Header --}}
        <div class="relative overflow-hidden p-8 rounded-3xl bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white shadow-lg mb-8">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2"><i class="fas fa-life-ring mr-3"></i>Pusat Bantuan Administrator</h1>
                <p class="opacity-90 max-w-xl">Panduan lengkap pengelolaan sistem SINDESA. Pelajari cara mengelola pengguna, jenis surat, pengaturan sistem, dan fitur-fitur lainnya.</p>
            </div>
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full"></div>
        </div>

        {{-- Quick Nav --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="#kelola-pengguna" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors"><i class="fas fa-users-cog"></i></div>
                <p class="text-sm font-semibold text-gray-700">Kelola Pengguna</p>
            </a>
            <a href="#kelola-surat" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors"><i class="fas fa-file-contract"></i></div>
                <p class="text-sm font-semibold text-gray-700">Kelola Surat</p>
            </a>
            <a href="#pengaturan" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-amber-600 group-hover:text-white transition-colors"><i class="fas fa-cogs"></i></div>
                <p class="text-sm font-semibold text-gray-700">Pengaturan Sistem</p>
            </a>
            <a href="#faq" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all text-center group">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl mx-auto mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors"><i class="fas fa-question-circle"></i></div>
                <p class="text-sm font-semibold text-gray-700">FAQ</p>
            </a>
        </div>

        {{-- Kelola Pengguna --}}
        <div id="kelola-pengguna" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-users-cog"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Mengelola Data Pengguna</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-users"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Data Warga</h4>
                        <p class="text-xs text-gray-500 mt-1">Melihat, mencari, mengedit, dan menghapus data warga terdaftar. Anda juga bisa mengubah status akun warga (Aktif, Tidak Aktif, atau Ditangguhkan) dan mengekspor data ke PDF.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-user-shield"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Data Operator</h4>
                        <p class="text-xs text-gray-500 mt-1">Menambahkan Operator baru (petugas verifikasi surat), mengedit data, mengubah status, atau menghapus akun Operator.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Data Kepala Desa</h4>
                        <p class="text-xs text-gray-500 mt-1">Menambahkan, mengedit, atau menonaktifkan akun Kepala Desa. <strong>Hanya 1 Kades yang bisa aktif</strong> pada satu waktu. Mengaktifkan Kades baru akan otomatis menonaktifkan Kades sebelumnya. Upload spesimen TTD wajib format PNG.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <p class="text-xs text-yellow-800"><i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i><strong>Penting:</strong> Verifikasi akun warga baru dilakukan dengan mengubah status dari "Tidak Aktif" menjadi "Aktif" setelah memvalidasi NIK dan KK warga tersebut.</p>
            </div>
        </div>

        {{-- Kelola Surat --}}
        <div id="kelola-surat" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-file-contract"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Mengelola Jenis Surat</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-toggle-on text-emerald-500 mr-2"></i>Aktifkan / Nonaktifkan Surat</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Di halaman <strong>"Kelola Jenis Surat"</strong>, Anda bisa mengaktifkan atau menonaktifkan jenis surat tertentu menggunakan toggle switch. Surat yang dinonaktifkan tidak akan muncul di menu warga.</p>
                </div>
                <div class="p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-edit text-blue-500 mr-2"></i>Edit Kop Surat</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Klik tombol <strong>"Edit Kop Surat"</strong> untuk mengubah logo, nama instansi pemerintahan, nama desa, dan alamat kantor desa yang akan tampil di setiap surat resmi.</p>
                </div>
            </div>
        </div>

        {{-- Pengaturan Sistem --}}
        <div id="pengaturan" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 scroll-mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-cogs"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Pengaturan Sistem</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-9 h-9 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-building"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Identitas Desa</h4>
                        <p class="text-xs text-gray-500 mt-1">Ubah nama desa, kecamatan, kabupaten, alamat kantor, dan logo desa. Data ini digunakan di kop surat resmi.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-9 h-9 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-user-circle"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Profil Administrator</h4>
                        <p class="text-xs text-gray-500 mt-1">Ubah nama, email, foto profil, dan password akun Admin Anda.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-orange-50 rounded-xl border border-orange-200">
                    <div class="w-9 h-9 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-tools"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Mode Perbaikan (Maintenance)</h4>
                        <p class="text-xs text-gray-500 mt-1">Aktifkan mode perbaikan untuk sementara menutup akses publik saat melakukan pemeliharaan sistem. Halaman login dan area admin tetap bisa diakses.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-database"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Backup Database</h4>
                        <p class="text-xs text-gray-500 mt-1">Unduh salinan cadangan database dalam format SQL. Disarankan melakukan backup secara rutin (minimal 1x seminggu) untuk mencegah kehilangan data.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-purple-50 rounded-xl border border-purple-100">
                    <div class="w-9 h-9 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-sm shrink-0"><i class="fas fa-history"></i></div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Log Aktivitas</h4>
                        <p class="text-xs text-gray-500 mt-1">Memantau seluruh aktivitas yang terjadi di sistem (siapa melakukan apa, kapan, dan dari IP mana). Berguna untuk audit keamanan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alur Sistem --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-[#cfa03f]/10 text-[#cfa03f] rounded-xl flex items-center justify-center text-lg"><i class="fas fa-project-diagram"></i></div>
                <h2 class="text-xl font-bold text-gray-800">Alur Sistem SINDESA</h2>
            </div>

            <div class="guide-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-4 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-xs mb-1">Warga Daftar</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Warga mendaftarkan akun dengan NIK & KK.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-4 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-xs mb-1">Admin Verifikasi</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Admin mengecek dan mengaktifkan akun warga.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-4 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-xs mb-1">Warga Ajukan Surat</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Warga mengisi form dan mengunggah dokumen.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-4 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-xs mb-1">Operator Verifikasi</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Operator memeriksa data dan memberi nomor surat.</p>
                </div>
                <div class="step-card relative bg-gradient-to-br from-gray-50 to-white p-4 pt-6 rounded-xl border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-xs mb-1">Kades TTD</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Kepala Desa menandatangani surat secara elektronik.</p>
                </div>
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
                        ['q' => 'Bagaimana cara memverifikasi akun warga baru?', 'a' => 'Buka menu "Data Warga", cari akun warga yang berstatus "Tidak Aktif", lalu klik "Edit". Periksa NIK dan KK warga, lalu ubah status menjadi "Aktif".'],
                        ['q' => 'Apakah bisa menambahkan jenis surat baru?', 'a' => 'Saat ini, jenis surat sudah ditentukan oleh sistem (15 jenis surat). Anda hanya bisa mengaktifkan atau menonaktifkan jenis surat yang tersedia.'],
                        ['q' => 'Apa yang terjadi jika saya mengaktifkan Mode Perbaikan?', 'a' => 'Semua halaman publik dan pengguna biasa tidak bisa diakses. Hanya halaman login, logout, tentang kami, dan area admin (melalui URL khusus) yang tetap bisa diakses.'],
                        ['q' => 'Bagaimana cara mengganti Kepala Desa aktif?', 'a' => 'Buka menu "Data Kepala Desa", tambahkan Kades baru dengan status "Aktif", atau edit Kades yang ada dan ubah statusnya. Sistem akan otomatis menonaktifkan Kades sebelumnya.'],
                        ['q' => 'Seberapa sering saya harus melakukan backup database?', 'a' => 'Disarankan minimal 1x seminggu, atau sebelum melakukan perubahan besar pada data. File backup bisa digunakan untuk memulihkan data jika terjadi masalah.'],
                        ['q' => 'Apakah Log Aktivitas bisa dihapus?', 'a' => 'Log aktivitas bersifat permanen dan tidak bisa dihapus melalui antarmuka web, demi menjaga integritas audit trail (sesuai prinsip tata kelola COBIT 2019).'],
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
