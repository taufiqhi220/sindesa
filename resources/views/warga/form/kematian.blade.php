<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Surat Keterangan Kematian - SINDESA</title>
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

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="max-w-3xl mx-auto text-center mb-10">
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengajuan Surat Keterangan Kematian</h2>
            <p class="text-gray-500">Silakan lengkapi formulir di bawah ini dengan data almarhum/almarhumah yang valid.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                    {{ session('success') }}</div>
            @endif
            

            <form action="{{ route('warga.form.kematian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Almarhum /
                    Almarhumah</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Almarhum/ah</label>
                        <input type="number" name="nik_almarhum" value="{{ old('nik_almarhum') }}"
                            placeholder="16 Digit NIK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nomor Kartu Keluarga (KK)</label>
                        <input type="number" name="kk_almarhum" value="{{ old('kk_almarhum') }}"
                            placeholder="16 Digit Nomor KK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                    <input type="text" name="nama_almarhum" value="{{ old('nama_almarhum') }}"
                        placeholder="Sesuai KTP/KK"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir_almarhum" value="{{ old('tempat_lahir_almarhum') }}"
                            placeholder="Kota/Kabupaten"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_almarhum" value="{{ old('tanggal_lahir_almarhum') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin_almarhum"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin_almarhum') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin_almarhum') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                        <select name="agama_almarhum"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            <option value="" disabled selected>-- Pilih Agama --</option>
                            <option value="Islam" {{ old('agama_almarhum') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ old('agama_almarhum') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                            <option value="Katolik" {{ old('agama_almarhum') == 'Katolik' ? 'selected' : '' }}>Katolik
                            </option>
                            <option value="Hindu" {{ old('agama_almarhum') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama_almarhum') == 'Buddha' ? 'selected' : '' }}>Buddha
                            </option>
                            <option value="Konghucu" {{ old('agama_almarhum') == 'Konghucu' ? 'selected' : '' }}>Konghucu
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan_almarhum"
                            value="{{ old('kewarganegaraan_almarhum', 'Indonesia') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Status Perkawinan</label>
                        <select name="status_perkawinan_almarhum"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Belum Kawin" {{ old('status_perkawinan_almarhum') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="Kawin" {{ old('status_perkawinan_almarhum') == 'Kawin' ? 'selected' : '' }}>
                                Kawin</option>
                            <option value="Cerai Hidup" {{ old('status_perkawinan_almarhum') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="Cerai Mati" {{ old('status_perkawinan_almarhum') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Pekerjaan</label>
                    <input type="text" name="pekerjaan_almarhum" value="{{ old('pekerjaan_almarhum') }}"
                        placeholder="Sesuai KTP"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Terakhir</label>
                    <textarea name="alamat_almarhum" rows="2" placeholder="Dusun / Jalan"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>{{ old('alamat_almarhum') }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Keterangan /
                    Detail Kematian</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Kematian</label>
                        <input type="date" name="tanggal_kematian" value="{{ old('tanggal_kematian') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Umur pada saat Kematian</label>
                        <input type="number" name="umur_kematian" value="{{ old('umur_kematian') }}"
                            placeholder="Contoh: 60"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Kematian</label>
                        <input type="text" name="tempat_kematian" value="{{ old('tempat_kematian') }}"
                            placeholder="Contoh: Rumah Sakit / Dusun Waru"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Sebab Kematian</label>
                        <input type="text" name="sebab_kematian" value="{{ old('sebab_kematian') }}"
                            placeholder="Contoh: Sakit / Kecelakaan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Pelapor</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama yang Melapor</label>
                        <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor', Auth::user()->name) }}"
                            placeholder="Nama Lengkap Pelapor"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Hubungan dengan yang
                            Meninggal</label>
                        <input type="text" name="hubungan_pelapor" value="{{ old('hubungan_pelapor') }}"
                            placeholder="Contoh: Anak Kandung / Suami / Istri"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Dokumen Pendukung
                </h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KTP Almarhum/ah</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB. Format: PDF, JPG, PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-ktp-almarhum" name="file_ktp_almarhum" class="hidden"
                            onchange="updateFileName(this, 'name-ktp-almarhum')" required>
                        <label for="file-ktp-almarhum"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-ktp-almarhum" class="text-sm text-gray-500 italic truncate">Belum ada file
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KK Almarhum/ah</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB. Format: PDF, JPG, PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-kk-almarhum" name="file_kk_almarhum" class="hidden"
                            onchange="updateFileName(this, 'name-kk-almarhum')" required>
                        <label for="file-kk-almarhum"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-kk-almarhum" class="text-sm text-gray-500 italic truncate">Belum ada file
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KTP Pelapor</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB. Format: PDF, JPG, PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-ktp-pelapor" name="file_ktp_pelapor" class="hidden"
                            onchange="updateFileName(this, 'name-ktp-pelapor')" required>
                        <label for="file-ktp-pelapor"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-ktp-pelapor" class="text-sm text-gray-500 italic truncate">Belum ada file
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Keterangan Dokter/Rumah Sakit
                        (Opsional)</label>
                    <span class="block text-xs text-gray-500 mb-4">Jika meninggal di fasilitas kesehatan. Format: PDF,
                        JPG, PNG (Maks 5MB).</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-rs" name="file_rs" class="hidden"
                            onchange="updateFileName(this, 'name-rs')">
                        <label for="file-rs"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-rs" class="text-sm text-gray-500 italic truncate">Belum ada file dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent"
                        class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                        required>
                    <label for="consent" class="text-sm text-gray-700">Keterangan ini dibuat atas dasar yang sebenarnya.
                        Dengan ini saya menyatakan data yang dikirimkan siap diproses ke Kantor Desa Buttu Sawe.</label>
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