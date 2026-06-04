<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Edit Akta Lahir - SINDESA</title>
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

        <div class="max-w-3xl mx-auto text-center mb-10 mt-10">
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Edit Pengajuan Surat Pengantar Akta Lahir</h2>
            <p class="text-gray-500">Perbarui data di bawah ini jika terdapat kesalahan pada pengajuan sebelumnya.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                    {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
                    <ul class="list-disc ml-4">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            {{-- ALERT PESAN PENOLAKAN --}}
            @if($surat->status == 'ditolak' && $surat->pesan_penolakan)
                <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-xl mb-8 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex shrink-0 items-center justify-center text-lg">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-800 text-base mb-1">Surat Ditolak / Perlu Revisi</h4>
                            <p class="text-red-700 text-sm font-medium">Catatan Admin: <span
                                    class="font-normal">{{ $surat->pesan_penolakan }}</span></p>
                            <p class="text-xs text-red-500 mt-2 italic">*Silakan perbaiki data atau dokumen di bawah ini
                                sesuai catatan, lalu tekan "Simpan Perubahan".</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('warga.form.akta-lahir.update', $surat->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas
                    Anak/Pemohon</h3>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                    <input type="text" name="nama_anak"
                        value="{{ old('nama_anak', $surat->data_tambahan['nama_anak'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir_anak"
                            value="{{ old('tempat_lahir_anak', $surat->data_tambahan['tempat_lahir_anak'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_anak"
                            value="{{ old('tanggal_lahir_anak', $surat->data_tambahan['tanggal_lahir_anak'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin_anak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none transition-all"
                            required>
                            @php $jk = old('jenis_kelamin_anak', $surat->data_tambahan['jenis_kelamin_anak'] ?? ''); @endphp
                            <option value="Laki-laki" {{ $jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                        <select name="agama_anak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none transition-all"
                            required>
                            @php $agama = old('agama_anak', $surat->data_tambahan['agama_anak'] ?? ''); @endphp
                            <option value="Islam" {{ $agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ $agama == 'Kristen Protestan' ? 'selected' : '' }}>
                                Kristen Protestan</option>
                            <option value="Katolik" {{ $agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ $agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ $agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ $agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan_anak"
                            value="{{ old('kewarganegaraan_anak', $surat->data_tambahan['kewarganegaraan_anak'] ?? 'Indonesia') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Anak Ke-</label>
                        <input type="number" name="anak_ke"
                            value="{{ old('anak_ke', $surat->data_tambahan['anak_ke'] ?? '') }}" min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Lengkap</label>
                    <textarea name="alamat_anak" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                        required>{{ old('alamat_anak', $surat->data_tambahan['alamat_anak'] ?? '') }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap Ayah</label>
                        <input type="text" name="nama_ayah"
                            value="{{ old('nama_ayah', $surat->data_tambahan['nama_ayah'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Ayah</label>
                        <input type="number" name="nik_ayah"
                            value="{{ old('nik_ayah', $surat->data_tambahan['nik_ayah'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap Ibu</label>
                        <input type="text" name="nama_ibu"
                            value="{{ old('nama_ibu', $surat->data_tambahan['nama_ibu'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Ibu</label>
                        <input type="number" name="nik_ibu"
                            value="{{ old('nik_ibu', $surat->data_tambahan['nik_ibu'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Dokumen Pendukung
                    <span class="text-sm font-normal text-gray-500">(Abaikan jika tidak diubah)</span>
                </h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Foto Copy KK</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($surat->data_tambahan['file_kk']))
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">File Saat Ini</p>
                                    <p class="text-xs text-gray-500">Telah tersimpan</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $surat->data_tambahan['file_kk']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-kk" name="file_kk" class="hidden"
                            onchange="updateFileName(this, 'name-kk')">
                        <label for="file-kk"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-kk" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Surat Pernyataan 2 Orang
                        Saksi</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($surat->data_tambahan['file_saksi']))
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">File Saat Ini</p>
                                    <p class="text-xs text-gray-500">Telah tersimpan</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $surat->data_tambahan['file_saksi']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-saksi" name="file_saksi" class="hidden"
                            onchange="updateFileName(this, 'name-saksi')">
                        <label for="file-saksi"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-saksi" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Dokumen Lain-lain (Opsional)</label>
                    <span class="block text-xs text-gray-500 mb-4">Maks 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($surat->data_tambahan['file_lain']) && $surat->data_tambahan['file_lain'])
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">File Saat Ini</p>
                                    <p class="text-xs text-gray-500">Telah tersimpan</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $surat->data_tambahan['file_lain']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-lain" name="file_lain" class="hidden"
                            onchange="updateFileName(this, 'name-lain')">
                        <label for="file-lain"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-lain" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent"
                        class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                        required>
                    <label for="consent" class="text-sm text-gray-700">
                        Dengan ini saya menyatakan bahwa data anak dan orang tua yang saya perbarui adalah benar sesuai
                        dengan dokumen aslinya, dan saya bertanggung jawab penuh atas keabsahan data tersebut dalam
                        pengajuan Akta Kelahiran ini.
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                        class="bg-[#cfa03f] hover:bg-[#b88e32] text-white px-8 py-3.5 rounded-xl font-semibold transition-all inline-flex items-center justify-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm w-full sm:w-auto">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('warga.riwayat') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3.5 rounded-xl font-semibold transition-all inline-flex items-center justify-center gap-2 text-sm w-full sm:w-auto">
                        Batal
                    </a>
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
                fileNameSpan.textContent = "File baru: " + input.files[0].name;
                fileNameSpan.classList.remove('italic', 'text-gray-500');
                fileNameSpan.classList.add('text-[#1a5e35]', 'font-medium');
            } else {
                fileNameSpan.textContent = "Pilihan dibatalkan, file lama tetap digunakan";
                fileNameSpan.classList.add('italic', 'text-gray-500');
                fileNameSpan.classList.remove('text-[#1a5e35]', 'font-medium');
            }
        }
    </script>
</body>

</html>