<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengaturan Akun - SINDESA</title>
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

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 shrink-0">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
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

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
            <a href="{{ route('operator.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.dashboard') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.verifikasi*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-inbox w-5 text-center"></i> Verifikasi Masuk
                </div>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('operator.menunggu-ttd') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.menunggu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>

            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.ditolak*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-times-circle w-5 text-center"></i> Surat Ditolak
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('operator.pengaturan-surat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-surat') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-contract w-5 text-center"></i> Pengaturan Surat
            </a>

            <a href="{{ route('operator.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer font-medium">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        @if(session('success'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-xl"></i>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#1a5e35]">Pengaturan Akun</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <i class="fas fa-user-edit text-[#1a5e35] text-xl"></i>
                <h3 class="text-lg font-bold text-gray-800">Profil & Data Diri</h3>
            </div>

            <form action="{{ route('operator.pengaturan-akun.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="flex flex-col md:flex-row gap-8">

                    <div class="flex flex-col items-center gap-4 md:w-1/3">
                        <div class="relative group">
                            <div
                                class="w-32 h-32 bg-[#cfa03f] rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-md border-4 border-white outline outline-1 outline-gray-200 overflow-hidden">
                                <img id="preview_foto"
                                    src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : '' }}"
                                    class="w-full h-full object-cover {{ Auth::user()->foto_profil ? '' : 'hidden' }}">
                                <span id="inisial_foto"
                                    class="{{ Auth::user()->foto_profil ? 'hidden' : '' }}">{{ substr(Auth::user()->name ?? 'O', 0, 1) }}</span>
                            </div>
                            <label for="upload_foto"
                                class="absolute bottom-0 right-0 w-10 h-10 bg-[#1a5e35] text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-[#2e7d32] transition-colors border-2 border-white">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="foto_profil" id="upload_foto" class="hidden" accept="image/*"
                                onchange="previewImage(event)">
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-2">Format: JPG, PNG. Maks: 2MB.</p>
                            @error('foto_profil')
                                <p class="text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm"
                                required>
                            @error('name')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" value="{{ old('nik', Auth::user()->nik ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm">
                            @error('nik')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">NIP (Pegawai) <span class="text-[10px] text-gray-400 font-normal ml-1">Boleh dikosongkan</span></label>
                            <input type="text" name="nip" value="{{ old('nip', Auth::user()->nip ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm">
                            @error('nip')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm">
                            @error('no_hp')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm"
                                required>
                            @error('email')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Jabatan <span
                                    class="text-xs text-red-500 ml-1">(Tidak dapat diubah)</span></label>
                            <input type="text" value="Petugas Verifikator"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed outline-none text-sm font-semibold"
                                readonly>
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit"
                                class="bg-[#1a5e35] hover:bg-[#2e7d32] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <i class="fas fa-shield-alt text-[#cfa03f] text-xl"></i>
                <h3 class="text-lg font-bold text-gray-800">Keamanan Akun</h3>
            </div>

            <form action="{{ route('operator.pengaturan-akun.password') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-800">Password Lama</label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] focus:ring-4 focus:ring-[#cfa03f]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm"
                            required>
                        @error('current_password')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Password Baru</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] focus:ring-4 focus:ring-[#cfa03f]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm"
                            required>
                        @error('password')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] focus:ring-4 focus:ring-[#cfa03f]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm"
                            required>
                    </div>

                    <div class="md:col-span-2 flex justify-end mt-2">
                        <button type="submit"
                            class="bg-[#cfa03f] hover:bg-[#b88e32] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm">
                            <i class="fas fa-key"></i> Perbarui Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </main>

    @include('partials.sweetalert')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('preview_foto');
                const inisial = document.getElementById('inisial_foto');
                output.src = reader.result;
                output.classList.remove('hidden');
                inisial.classList.add('hidden');
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>

</html>