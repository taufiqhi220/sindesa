<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengaturan Surat - SINDESA</title>
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
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer">
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
            <h2 class="text-3xl font-bold text-[#1a5e35]">Pengaturan Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Pratinjau dan kelola Kop Surat untuk cetak dokumen PDF.</p>
        </div>

        <div id="previewContainer"
            class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <i class="fas fa-heading text-[#1a5e35] text-xl"></i>
                <h3 class="text-lg font-bold text-gray-800">Preview Kop Surat (Header PDF)</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">Kop surat ini akan otomatis tercetak di bagian atas dokumen PDF.</p>

            <div class="bg-white border border-gray-300 p-6 md:p-10 shadow-sm mx-auto max-w-4xl relative">
                <div class="flex items-center justify-between gap-4">
                    @if($pengaturan->logo_path)
                        <img src="{{ asset('storage/' . $pengaturan->logo_path) }}" alt="Logo"
                            class="w-20 md:w-[100px] object-contain">
                    @else
                        <img src="{{ asset('image/logo-pinrang.png') }}" alt="Logo Default"
                            class="w-20 md:w-[100px] object-contain">
                    @endif

                    <div class="flex-1 text-center text-black font-['Times_New_Roman',_Times,_serif]">
                        <h3 class="text-base md:text-xl uppercase leading-snug">
                            {{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}
                        </h3>
                        <h3 class="text-base md:text-xl uppercase leading-snug">
                            {{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}
                        </h3>
                        <h2 class="text-2xl md:text-3xl font-bold uppercase tracking-wide mt-1 mb-1">
                            {{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}
                        </h2>
                        <p class="text-[10px] md:text-xs">
                            {{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253' }}
                        </p>
                    </div>

                    <div class="w-20 md:w-[100px]"></div>
                </div>

                <div class="mt-4 border-b-[3px] border-black w-full"></div>
                <div class="mt-[2px] border-b border-black w-full"></div>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="toggleEditMode()"
                    class="bg-[#cfa03f] hover:bg-[#b88c34] text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Kop Surat
                </button>
            </div>
        </div>

        <div id="editContainer"
            class="hidden bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8 mb-8">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <i class="fas fa-edit text-[#1a5e35] text-xl"></i>
                <h3 class="text-lg font-bold text-gray-800">Edit Data Kop Surat</h3>
            </div>

            <form action="{{ route('operator.pengaturan-surat.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logo Kop Surat</label>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-20 h-20 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                                @if($pengaturan->logo_path)
                                    <img src="{{ asset('storage/' . $pengaturan->logo_path) }}" alt="Logo"
                                        class="w-full h-full object-contain p-2">
                                @else
                                    <img src="{{ asset('image/logo-pinrang.png') }}" alt="Logo Default"
                                        class="w-full h-full object-contain p-2">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" accept="image/png, image/jpeg, image/jpg"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#1a5e35]/10 file:text-[#1a5e35] hover:file:bg-[#1a5e35]/20 cursor-pointer">
                                <p class="text-[10px] text-gray-400 mt-2">*Format: JPG, JPEG, PNG. Maksimal 2MB. Biarkan
                                    kosong jika tidak ingin mengubah logo.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Header Baris 1</label>
                        <input type="text" name="header_1"
                            value="{{ old('header_1', $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Header Baris 2</label>
                        <input type="text" name="header_2"
                            value="{{ old('header_2', $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Desa (Baris 3)</label>
                        <input type="text" name="nama_desa"
                            value="{{ old('nama_desa', $pengaturan->nama_desa ?? 'DESA BUTTU SAWE') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm font-bold uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat & Kontak</label>
                        <textarea name="alamat" rows="2" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm">{{ old('alamat', $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleEditMode()"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold text-sm transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#1a5e35] hover:bg-[#2e7d32] text-white rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
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

        // Script untuk Toggle View Mode / Edit Mode
        function toggleEditMode() {
            const preview = document.getElementById('previewContainer');
            const edit = document.getElementById('editContainer');

            if (preview.classList.contains('hidden')) {
                preview.classList.remove('hidden');
                edit.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                edit.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>