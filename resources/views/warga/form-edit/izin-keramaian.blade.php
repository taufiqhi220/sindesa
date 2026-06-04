<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengajuan Izin Keramaian - SINDESA</title>
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Edit Pengajuan Pengantar Izin Keramaian</h2>
            <p class="text-gray-500">Perbaiki rincian acara atau identitas yang salah pada pengajuan sebelumnya.</p>
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

            <form action="{{ route('warga.form.izin-keramaian.update', $surat->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php $data = $surat->data_tambahan; @endphp

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas
                    Penanggung Jawab Acara</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK</label>
                        <input type="number" name="nik_penanggung_jawab"
                            value="{{ old('nik_penanggung_jawab', $data['nik_penanggung_jawab'] ?? Auth::user()->nik) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                        <input type="text" name="nama_penanggung_jawab"
                            value="{{ old('nama_penanggung_jawab', $data['nama_penanggung_jawab'] ?? Auth::user()->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $data['tempat_lahir'] ?? Auth::user()->tempat_lahir) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $data['tanggal_lahir'] ?? Auth::user()->tanggal_lahir) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            @php $jk = old('jenis_kelamin', $data['jenis_kelamin'] ?? Auth::user()->jenis_kelamin); @endphp
                            <option value="" disabled>Pilih</option>
                            <option value="Laki-laki" {{ strcasecmp($jk, 'Laki-laki') == 0 ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ strcasecmp($jk, 'Perempuan') == 0 ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                        <select name="agama"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            @php $agm = old('agama', $data['agama'] ?? Auth::user()->agama); @endphp
                            <option value="Islam" {{ $agm == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ $agm == 'Kristen Protestan' ? 'selected' : '' }}>Kristen
                                Protestan</option>
                            <option value="Katolik" {{ $agm == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ $agm == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ $agm == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ $agm == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Pekerjaan</label>
                        <input type="text" name="pekerjaan"
                            value="{{ old('pekerjaan', $data['pekerjaan'] ?? Auth::user()->pekerjaan) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>{{ old('alamat', $data['alamat'] ?? Auth::user()->alamat_lengkap) }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Rincian Acara
                    Keramaian</h3>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Acara / Keramaian</label>
                    <input type="text" name="jenis_acara" value="{{ old('jenis_acara', $data['jenis_acara'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $data['tanggal_mulai'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $data['tanggal_selesai'] ?? '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Lokasi Acara Berlangsung</label>
                    <textarea name="lokasi_acara" rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>{{ old('lokasi_acara', $data['lokasi_acara'] ?? '') }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Upload Berkas
                    Pendukung <span class="text-sm font-normal text-gray-500">(Abaikan jika tidak diubah)</span></h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KTP Penanggung Jawab</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($data['file_ktp']))
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
                            <a href="{{ asset('storage/' . $data['file_ktp']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-ktp" name="file_ktp" class="hidden"
                            onchange="updateFileName(this, 'name-ktp')">
                        <label for="file-ktp"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-ktp" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Pengantar RT / RW
                        (Opsional)</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB (PDF/JPG/PNG).</span>

                    @if(isset($data['file_pengantar_rt']) && $data['file_pengantar_rt'])
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
                            <a href="{{ asset('storage/' . $data['file_pengantar_rt']) }}" target="_blank"
                                class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-pengantar" name="file_pengantar_rt" class="hidden"
                            onchange="updateFileName(this, 'name-pengantar')">
                        <label for="file-pengantar"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Ganti File
                        </label>
                        <span id="name-pengantar" class="text-sm text-gray-500 italic truncate">Tidak ada file baru
                            dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-red-50 p-4 rounded-xl border border-red-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent"
                        class="mt-1 w-4 h-4 text-red-600 bg-white border-gray-300 rounded focus:ring-red-600" required>
                    <label for="consent" class="text-sm text-gray-800 font-medium">
                        Saya bersedia menjaga keamanan dan ketertiban. <span class="text-red-600">Dalam acara tersebut
                            saya menjamin tidak akan ada aktivitas Pornografi, Porno Aksi / Candoleng-doleng, Perjudian,
                            dan mengkonsumsi minuman keras (Miras)</span> yang bertentangan dengan hukum.
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