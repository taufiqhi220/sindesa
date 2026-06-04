<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengajuan Pengantar KK - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CSS untuk mempercantik scrollbar di dalam menu navigasi sidebar */
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

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengajuan Surat Pengantar Kartu Keluarga</h2>
                <p class="text-gray-500">Silakan lengkapi formulir untuk pembuatan baru atau pembaharuan KK Anda.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                        {{ session('success') }}</div>
                @endif
                

                <form action="{{ route('warga.form.kk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas
                        Pemohon
                    </h3>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tujuan Pengajuan</label>
                        <select name="tujuan_pengajuan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-gray-50 focus:bg-white transition-all outline-none text-sm appearance-none"
                            required>
                            <option value="" disabled selected>-- Pilih Tujuan --</option>
                            <option value="Pembuatan Baru" {{ old('tujuan_pengajuan') == 'Pembuatan Baru' ? 'selected' : '' }}>Pembuatan Baru</option>
                            <option value="Pembaharuan" {{ old('tujuan_pengajuan') == 'Pembaharuan' ? 'selected' : '' }}>
                                Pembaharuan / Perubahan Data</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">NIK (Nomor Induk
                                Kependudukan)</label>
                            <input type="number" name="nik" value="{{ old('nik', Auth::user()->nik) }}"
                                placeholder="16 Digit NIK"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Nomor Kartu Keluarga (Jika
                                ada)</label>
                            <input type="number" name="kk_lama" value="{{ old('no_kk', Auth::user()->no_kk) }}"
                                placeholder="16 Digit Nomor KK Lama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}"
                            placeholder="Nama Lengkap sesuai KTP/Dokumen"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir"
                                value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}"
                                placeholder="Kota/Kabupaten"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                                required>
                                @php $jk = old('jenis_kelamin', Auth::user()->jenis_kelamin); @endphp
                                <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ $jk == 'Laki-Laki' || $jk == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ $jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                            <select name="agama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                                required>
                                @php $agm = old('agama', Auth::user()->agama); @endphp
                                <option value="" disabled>-- Pilih Agama --</option>
                                <option value="Islam" {{ $agm == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen Protestan" {{ $agm == 'Kristen Protestan' ? 'selected' : '' }}>
                                    Kristen
                                    Protestan</option>
                                <option value="Katolik" {{ $agm == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ $agm == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ $agm == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ $agm == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Status Perkawinan</label>
                            <select name="status_perkawinan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                                required>
                                @php $sp = old('status_perkawinan', Auth::user()->status_perkawinan); @endphp
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="Belum Kawin" {{ $sp == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin
                                </option>
                                <option value="Kawin" {{ $sp == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ $sp == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup
                                </option>
                                <option value="Cerai Mati" {{ $sp == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', Auth::user()->pekerjaan) }}"
                                placeholder="Contoh: Mengurus Rumah Tangga / Wiraswasta"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Kepala Keluarga
                            (Tujuan)</label>
                        <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}"
                            placeholder="Masukkan Nama Kepala Keluarga yang akan dicetak di KK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-800">Alamat (Dusun/Jalan)</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                placeholder="Contoh: Dusun Passolengang"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                required>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium text-gray-800">RT</label>
                                <input type="number" name="rt" value="{{ old('rt') }}" placeholder="000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                    required>
                            </div>
                            <div class="w-1/2">
                                <label class="block mb-2 text-sm font-medium text-gray-800">RW</label>
                                <input type="number" name="rw" value="{{ old('rw') }}" placeholder="000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                                    required>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Dokumen
                        Pendukung
                    </h3>

                    <div
                        class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KK Lama / Surat
                            Kehilangan</label>
                        <span class="block text-xs text-gray-500 mb-4">Jika pembaharuan KK wajib lampirkan KK Lama.
                            Format:
                            PDF, JPG, PNG (Maks 5MB).</span>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <input type="file" id="file-kk-lama" name="file_kk_lama" class="hidden"
                                onchange="updateFileName(this, 'name-kk-lama')">
                            <label for="file-kk-lama"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-upload-alt"></i> Pilih File
                            </label>
                            <span id="name-kk-lama" class="text-sm text-gray-500 italic truncate">Belum ada file
                                dipilih</span>
                        </div>
                    </div>

                    <div
                        class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Buku Nikah / Akta Cerai
                            (Jika
                            Ada)</label>
                        <span class="block text-xs text-gray-500 mb-4">Diperlukan jika status perkawinan berubah.
                            Format:
                            PDF, JPG, PNG (Maks 5MB).</span>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <input type="file" id="file-nikah" name="file_nikah" class="hidden"
                                onchange="updateFileName(this, 'name-nikah')">
                            <label for="file-nikah"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-upload-alt"></i> Pilih File
                            </label>
                            <span id="name-nikah" class="text-sm text-gray-500 italic truncate">Belum ada file
                                dipilih</span>
                        </div>
                    </div>

                    <div
                        class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Dokumen Pendukung Lain
                            (Opsional)</label>
                        <span class="block text-xs text-gray-500 mb-4">Contoh: Akta Kelahiran Anak, Surat Pindah, dll.
                            Format: PDF, JPG, PNG (Maks 5MB).</span>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <input type="file" id="file-lain" name="file_lain" class="hidden"
                                onchange="updateFileName(this, 'name-lain')">
                            <label for="file-lain"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-upload-alt"></i> Pilih File
                            </label>
                            <span id="name-lain" class="text-sm text-gray-500 italic truncate">Belum ada file
                                dipilih</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                        <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent"
                            class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                            required>
                        <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan data yang saya
                            kirimkan adalah benar dan siap diproses ke Kantor Desa Buttu Sawe.</label>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit"
                            class="bg-[#cfa03f] hover:bg-[#b88e32] text-white px-8 py-3.5 rounded-xl font-semibold transition-all inline-flex items-center justify-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm w-full sm:w-auto">
                            <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <script>
            function toggleMenu(menuId, element) {
                const submenu = document.getElementById(menuId);
                const icon = element.querySelector('.fa-chevron-down');

                if (submenu.classList.contains('hidden')) {
                    submenu.classList.remove('hidden');
                    submenu.classList.add('block');
                    icon.classList.add('rotate-180');
                } else {
                    submenu.classList.add('hidden');
                    submenu.classList.remove('block');
                    icon.classList.remove('rotate-180');
                }
            }

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            function updateFileName(input, spanId) {
                const fileNameSpan = document.getElementById(spanId);
                if (input.files && input.files.length > 0) {
                    fileNameSpan.textContent = input.files[0].name;
                    fileNameSpan.classList.remove('italic', 'text-gray-500');
                    fileNameSpan.classList.add('text-gray-800', 'font-medium');
                } else {
                    fileNameSpan.textContent = "Belum ada file dipilih";
                    fileNameSpan.classList.add('italic', 'text-gray-500');
                    fileNameSpan.classList.remove('text-gray-800', 'font-medium');
                }
            }
        </script>
</body>

</html>