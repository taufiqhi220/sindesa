<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Edit Keterangan Beda Nama - SINDESA</title>
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Edit Pengajuan Keterangan Beda Nama / Data</h2>
            <p class="text-gray-500">Perbaiki data pengajuan yang salah atau kurang lengkap.</p>
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

            <form action="{{ route('warga.form.beda-nama.update', $surat->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Pada Dokumen
                    1 (KTP / KK)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK (Sesuai KTP)</label>
                        <input type="number" name="nik_dok1"
                            value="{{ old('nik_dok1', $surat->data_tambahan['nik_dok1'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap (Sesuai KTP)</label>
                        <input type="text" name="nama_dok1"
                            value="{{ old('nama_dok1', $surat->data_tambahan['nama_dok1'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir_dok1"
                            value="{{ old('tempat_lahir_dok1', $surat->data_tambahan['tempat_lahir_dok1'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_dok1"
                            value="{{ old('tanggal_lahir_dok1', $surat->data_tambahan['tanggal_lahir_dok1'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin_dok1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none transition-all"
                            required>
                            @php $jkDefault = old('jenis_kelamin_dok1', $surat->data_tambahan['jenis_kelamin_dok1'] ?? ''); @endphp
                            <option value="Laki-Laki" {{ strcasecmp($jkDefault, 'Laki-laki') == 0 ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ strcasecmp($jkDefault, 'Perempuan') == 0 ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Lengkap (Sesuai KTP)</label>
                    <textarea name="alamat_dok1" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none transition-all"
                        required>{{ old('alamat_dok1', $surat->data_tambahan['alamat_dok1'] ?? '') }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#cfa03f] border-b-2 border-gray-100 pb-2 mb-6">Data Pada Dokumen
                    2 (Yang Berbeda)</h3>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Dokumen yang Berbeda</label>
                    <input type="text" name="nama_dokumen2"
                        value="{{ old('nama_dokumen2', $surat->data_tambahan['nama_dokumen2'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK / Nomor Referensi
                            (Opsional)</label>
                        <input type="text" name="nomor_dok2"
                            value="{{ old('nomor_dok2', $surat->data_tambahan['nomor_dok2'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap (Di Dokumen 2)</label>
                        <input type="text" name="nama_dok2"
                            value="{{ old('nama_dok2', $surat->data_tambahan['nama_dok2'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir (Di Dokumen 2)</label>
                        <input type="text" name="tempat_lahir_dok2"
                            value="{{ old('tempat_lahir_dok2', $surat->data_tambahan['tempat_lahir_dok2'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir (Di Dokumen 2)</label>
                        <input type="date" name="tanggal_lahir_dok2"
                            value="{{ old('tanggal_lahir_dok2', $surat->data_tambahan['tanggal_lahir_dok2'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin_dok2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none appearance-none"
                            required>
                            @php $jk2 = old('jenis_kelamin_dok2', $surat->data_tambahan['jenis_kelamin_dok2'] ?? ''); @endphp
                            <option value="" disabled>Pilih</option>
                            <option value="Laki-laki" {{ strcasecmp($jk2, 'Laki-laki') == 0 ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ strcasecmp($jk2, 'Perempuan') == 0 ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Lengkap (Di Dokumen 2)</label>
                    <textarea name="alamat_dok2" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#cfa03f] bg-orange-50 outline-none"
                        required>{{ old('alamat_dok2', $surat->data_tambahan['alamat_dok2'] ?? '') }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Rincian Data yang
                    Benar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Sebutkan Data yang Berbeda</label>
                        <input type="text" name="data_berbeda"
                            value="{{ old('data_berbeda', $surat->data_tambahan['data_berbeda'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Data yang Benar Merujuk Pada
                            Dokumen?</label>
                        <select name="acuan_kebenaran"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none"
                            required>
                            @php $acuan = old('acuan_kebenaran', $surat->data_tambahan['acuan_kebenaran'] ?? ''); @endphp
                            <option value="" disabled>-- Pilih Acuan Kebenaran --</option>
                            <option value="Dokumen 1 (KTP/KK)" {{ $acuan == 'Dokumen 1 (KTP/KK)' ? 'selected' : '' }}>
                                Dokumen 1 (KTP/KK)</option>
                            <option value="Dokumen 2" {{ $acuan == 'Dokumen 2' ? 'selected' : '' }}>Dokumen 2</option>
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Upload Berkas
                    Pendukung <span class="text-sm font-normal text-gray-500">(Abaikan jika tidak diubah)</span></h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Dokumen 1 (KTP / KK)</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($surat->data_tambahan['file_dok1']))
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
                            <a href="{{ asset('storage/' . $surat->data_tambahan['file_dok1']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-dok1" name="file_dok1" class="hidden"
                            onchange="updateFileName(this, 'name-dok1')">
                        <label for="file-dok1"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-dok1" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Dokumen 2 (Yang
                        Berbeda)</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($surat->data_tambahan['file_dok2']))
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
                            <a href="{{ asset('storage/' . $surat->data_tambahan['file_dok2']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-dok2" name="file_dok2" class="hidden"
                            onchange="updateFileName(this, 'name-dok2')">
                        <label for="file-dok2"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-dok2" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')"
                        class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                        required>
                    <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan bahwa kedua identitas
                        pada dokumen di atas adalah benar-benar orang yang sama dan saya menyetujui pembaruan
                        data.</label>
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
                fileNameSpan.textContent = input.files[0].name;
                fileNameSpan.classList.remove('italic', 'text-gray-500');
                fileNameSpan.classList.add('text-gray-800', 'font-medium');
            } else {
                fileNameSpan.textContent = "Tidak ada file baru dipilih";
                fileNameSpan.classList.add('italic', 'text-gray-500');
                fileNameSpan.classList.remove('text-gray-800', 'font-medium');
            }
        }
    </script>
</body>

</html>