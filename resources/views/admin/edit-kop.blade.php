<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Edit Kop Surat - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">

        {{-- Header Mobile --}}
        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1a5e35]">Edit Kop Surat</h2>
            <p class="text-gray-500 text-sm mt-1">Sesuaikan identitas instansi yang akan tampil pada cetakan dokumen
                PDF.</p>
        </div>

        <div
            class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8 max-w-4xl">
            <form action="{{ route('admin.kelola-surat.update-kop') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Pemerintah (Header
                            1)</label>
                        <input type="text" name="header_1" value="{{ $pengaturan->header_1 ?? '' }}"
                            placeholder="Contoh: PEMERINTAH KABUPATEN PINRANG"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan (Header 2)</label>
                        <input type="text" name="header_2" value="{{ $pengaturan->header_2 ?? '' }}"
                            placeholder="Contoh: KECAMATAN DUAMPANUA"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Desa</label>
                    <input type="text" name="nama_desa" value="{{ $pengaturan->nama_desa ?? '' }}"
                        placeholder="Contoh: DESA BUTTU SAWE"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Beserta Kode
                        Pos)</label>
                    <input type="text" name="alamat" value="{{ $pengaturan->alamat ?? '' }}"
                        placeholder="Contoh: Jl. Poros Pinrang - Polman Km. 25, Kode Pos 91254"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20">
                </div>

                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Logo Instansi</label>

                    <div class="flex items-center gap-6">
                        <div
                            class="w-24 h-24 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center shrink-0 p-2 overflow-hidden">
                            @if($pengaturan && $pengaturan->logo_path)
                                <img src="{{ asset('storage/' . $pengaturan->logo_path) }}" alt="Logo Instansi"
                                    class="w-full h-full object-contain">
                            @else
                                <img src="{{ asset('image/logo-pinrang.png') }}" alt="Logo Default"
                                    class="w-full h-full object-contain">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah logo saat
                                ini. (Format: PNG, Maks: 2MB).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="bg-[#1a5e35] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#2e7d32] transition-colors shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.kelola-surat') }}"
                        class="px-6 py-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-100 transition-colors border border-gray-200 bg-white">
                        Batal
                    </a>
                </div>
            </form>
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


    @include('partials.sweetalert')
</body>

</html>
