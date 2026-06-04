<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengaturan Akun Kades - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5">
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

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center justify-between p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-signature w-5 text-center"></i> Perlu Tanda Tangan
                </div>
                @if(isset($unreadCountKades) && $unreadCountKades > 0) <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $unreadCountKades }}</span> @endif
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 bg-[#cfa03f] rounded-lg text-white font-medium mb-2 shadow-md">
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengaturan Akun</h2>
            <p class="text-gray-500">Kelola informasi profil, kontak, spesimen tanda tangan, dan keamanan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 text-center flex flex-col items-center">

                    <form action="{{ route('kades.pengaturan.update-foto') }}" method="POST"
                        enctype="multipart/form-data" id="formFotoProfil">
                        @csrf
                        @method('PATCH')
                        <label
                            class="w-24 h-24 bg-[#cfa03f] rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-md relative group cursor-pointer overflow-hidden block mx-auto">
                            @if(Auth::user()->foto_profil)
                                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}?v={{ time() }}"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name ?? 'H', 0, 1) }}
                            @endif
                            <div
                                class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center transition-all">
                                <i class="fas fa-camera text-xl"></i>
                            </div>
                            <input type="file" name="foto_profil" class="hidden"
                                accept="image/png, image/jpeg, image/jpg"
                                onchange="document.getElementById('formFotoProfil').submit();">
                        </label>
                    </form>

                    <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->name ?? 'H. Burhanuddin' }}</h3>
                    <p class="text-gray-500 text-sm mb-4">Kepala Desa</p>
                    <span
                        class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-4 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fas fa-check-circle"></i> Akun Aktif
                    </span>
                </div>
            </div>

            <div class="lg:col-span-2 flex flex-col gap-8">

                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
                    <h3 class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                        <i class="fas fa-user-edit mr-2"></i> Informasi Profil
                    </h3>
                    <form action="{{ route('kades.pengaturan.update-profil') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap beserta
                                    Gelar</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm"
                                    required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" name="nik" value="{{ Auth::user()->nik ?? '' }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">NIP (Pegawai) <span class="text-[10px] text-gray-400 font-normal ml-1">Boleh dikosongkan</span></label>
                                <input type="text" name="nip" value="{{ Auth::user()->nip ?? '' }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">Jabatan</label>
                                <input type="text" value="Kepala Desa"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 transition-all outline-none text-sm text-gray-600"
                                    readonly>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm"
                                    required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-[#1a5e35] hover:bg-[#2e7d32] text-white px-6 py-3 rounded-xl font-bold transition-all inline-flex items-center gap-2 shadow-sm text-sm">
                                <i class="fas fa-save"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
                    <h3 class="text-lg font-bold text-[#cfa03f] border-b-2 border-gray-50 pb-3 mb-6">
                        <i class="fas fa-signature mr-2"></i> Spesimen Tanda Tangan Elektronik
                    </h3>
                    <form action="{{ route('kades.pengaturan.update-ttd') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="flex flex-col md:flex-row gap-6 mb-6">

                            <div
                                class="w-full md:w-1/3 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50 min-h-[120px] relative overflow-hidden">
                                @if(!empty(Auth::user()->ttd_path))
                                    <img src="{{ asset('storage/' . trim(Auth::user()->ttd_path)) }}?t={{ time() }}"
                                        alt="TTD Kades" class="max-h-[100px] w-auto object-contain relative z-10"
                                        onerror="this.style.display='none'; document.getElementById('ttd-error').classList.remove('hidden');">

                                    <div id="ttd-error" class="hidden text-[10px] text-red-500 text-center">
                                        File ada tapi tidak bisa dimuat.<br>Path: {{ Auth::user()->ttd_path }}
                                    </div>
                                @else
                                    <i class="fas fa-file-signature text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-xs text-gray-500 text-center font-medium">Belum ada spesimen</p>
                                @endif
                            </div>

                            <div class="w-full md:w-2/3">
                                <label class="block mb-2 text-sm font-medium text-gray-800">Upload Gambar TTD
                                    Baru</label>
                                <span class="block text-[11px] text-gray-500 mb-3 leading-relaxed">
                                    Gunakan file berformat <strong>.PNG</strong> transparan.
                                </span>
                                <input type="file" name="ttd_image" accept="image/png" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 text-sm cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#cfa03f] file:text-white hover:file:bg-[#b88e32] outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-[#cfa03f] hover:bg-[#b88e32] text-white px-6 py-3 rounded-xl font-bold transition-all inline-flex items-center gap-2 shadow-sm text-sm">
                                <i class="fas fa-upload"></i> Unggah Spesimen
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
                    <h3 class="text-lg font-bold text-red-600 border-b-2 border-gray-50 pb-3 mb-6">
                        <i class="fas fa-lock mr-2"></i> Keamanan Akun
                    </h3>
                    <form action="{{ route('kades.pengaturan.update-password') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-800">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="Masukkan password lama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm"
                                required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm"
                                    required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-800">Konfirmasi Password
                                    Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all outline-none text-sm"
                                    required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-xl font-bold transition-all inline-flex items-center gap-2 shadow-sm text-sm">
                                <i class="fas fa-key"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Tampilkan SweetAlert jika ada Session Success/Error dari Controller
        @if(Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ Session::get('success') }}',
                confirmButtonColor: '#1a5e35'
            });
        @endif

        @if(Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ Session::get('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan!',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
</body>

</html>