<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengajuan Keterangan Pindah - SINDESA</title>
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengajuan Keterangan Pindah</h2>
            <p class="text-gray-500">Silakan lengkapi formulir untuk penerbitan surat pengantar pindah alamat.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                    {{ session('success') }}</div>
            @endif
            

            <form action="{{ route('warga.form.pindah.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas Pemohon
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Pemohon</label>
                        <input type="number" name="nik" value="{{ old('nik', Auth::user()->nik) }}"
                            placeholder="16 Digit NIK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nomor Kartu Keluarga (KK)</label>
                        <input type="number" name="no_kk" value="{{ old('no_kk', Auth::user()->no_kk) }}"
                            placeholder="16 Digit Nomor KK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', Auth::user()->name) }}"
                        placeholder="Nama Lengkap sesuai Dokumen"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', Auth::user()->tempat_lahir) }}" placeholder="Kota/Kabupaten"
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
                            <option value="Kristen Protestan" {{ $agm == 'Kristen Protestan' ? 'selected' : '' }}>Kristen
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
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>
                                Belum Kawin</option>
                            <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin
                            </option>
                            <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>
                                Cerai Hidup</option>
                            <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>
                                Cerai Mati</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                            placeholder="Contoh: Petani, Mahasiswa, Wiraswasta"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan') }}"
                        placeholder="Contoh: Tamat SD Sederajat, SMA, S1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Keterangan
                    Kepindahan</h3>

                <div class="mb-6 bg-gray-50 p-5 rounded-2xl border border-gray-200">
                    <label class="block mb-2 text-sm font-medium text-[#1a5e35]">Alamat Asal (Desa Buttu Sawe)</label>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <input type="text" name="alamat_asal_dusun" value="{{ old('alamat_asal_dusun') }}"
                                placeholder="Nama Dusun"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm"
                                required>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="alamat_asal_rt" value="{{ old('alamat_asal_rt') }}"
                                placeholder="RT"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm"
                                required>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="alamat_asal_rw" value="{{ old('alamat_asal_rw') }}"
                                placeholder="RW"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm"
                                required>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-[#cfa03f]">Alamat Tujuan Pindah</label>
                    <input type="text" name="alamat_tujuan_jalan" value="{{ old('alamat_tujuan_jalan') }}"
                        placeholder="Nama Jalan / Dusun / Kampung"
                        class="w-full px-4 py-3 mb-4 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <input type="number" name="alamat_tujuan_rt" value="{{ old('alamat_tujuan_rt') }}"
                            placeholder="RT"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                        <input type="number" name="alamat_tujuan_rw" value="{{ old('alamat_tujuan_rw') }}"
                            placeholder="RW"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                        <input type="text" name="alamat_tujuan_desa" value="{{ old('alamat_tujuan_desa') }}"
                            placeholder="Desa / Kelurahan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl md:col-span-2 focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <input type="text" name="alamat_tujuan_kecamatan" value="{{ old('alamat_tujuan_kecamatan') }}"
                            placeholder="Kecamatan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                        <input type="text" name="alamat_tujuan_kabupaten" value="{{ old('alamat_tujuan_kabupaten') }}"
                            placeholder="Kab / Kota"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                        <input type="text" name="alamat_tujuan_provinsi" value="{{ old('alamat_tujuan_provinsi') }}"
                            placeholder="Provinsi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>

                    <div class="w-full md:w-1/3">
                        <input type="number" name="alamat_tujuan_kodepos" value="{{ old('alamat_tujuan_kodepos') }}"
                            placeholder="Kode Pos"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Alasan Pindah</label>
                        <input type="text" name="alasan_pindah" value="{{ old('alasan_pindah') }}"
                            placeholder="Contoh: Mengikuti Orang Tua / Pekerjaan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Rencana Tanggal Pindah</label>
                        <input type="date" name="tanggal_pindah" value="{{ old('tanggal_pindah') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <!-- FORM PENGIKUT DINAMIS -->
                <div class="mb-10">
                    <div class="flex justify-between items-center mb-4 border-b-2 border-gray-100 pb-2">
                        <label class="text-lg font-semibold text-[#1a5e35]">Anggota Keluarga yang Ikut Pindah</label>
                        <button type="button" onclick="addPengikut()"
                            class="bg-[#1a5e35] hover:bg-[#11442b] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                            <i class="fas fa-user-plus"></i> Tambah Anggota
                        </button>
                    </div>
                    <div id="pengikut-container" class="space-y-4">
                        <!-- Form dinamis akan muncul disini -->
                    </div>
                    <p id="empty-pengikut-msg"
                        class="text-sm text-gray-500 italic text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        Tidak ada anggota keluarga tambahan. Klik "Tambah Anggota" jika pindah bersama keluarga.
                    </p>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Dokumen Pendukung
                </h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KTP Pemohon</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB. Format: PDF, JPG, PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-ktp" name="file_ktp" class="hidden"
                            onchange="updateFileName(this, 'name-ktp')" required>
                        <label for="file-ktp"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-ktp" class="text-sm text-gray-500 italic truncate">Belum ada file dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Kartu Keluarga (KK)</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB. Format: PDF, JPG, PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-kk" name="file_kk" class="hidden"
                            onchange="updateFileName(this, 'name-kk')" required>
                        <label for="file-kk"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-kk" class="text-sm text-gray-500 italic truncate">Belum ada file dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] hover:bg-[#f0fdf4] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Dokumen Pendukung Lainnya
                        (Opsional)</label>
                    <span class="block text-xs text-gray-500 mb-4">Misal SKCK Lama dll. Maksimal 5MB. Format: PDF, JPG,
                        PNG.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-lain" name="file_lain" class="hidden"
                            onchange="updateFileName(this, 'name-lain')">
                        <label for="file-lain"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-lain" class="text-sm text-gray-500 italic truncate">Belum ada file dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent"
                        class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                        required>
                    <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan data dan alamat tujuan
                        kepindahan yang dikirimkan adalah benar, dan siap diproses ke Kantor Desa Buttu Sawe.</label>
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
        // Logika Javascript untuk Add/Remove Form Pengikut
        let pengikutIndex = 0;

        function addPengikut() {
            pengikutIndex++;
            const container = document.getElementById('pengikut-container');
            const emptyMsg = document.getElementById('empty-pengikut-msg');

            if (emptyMsg) emptyMsg.style.display = 'none';

            const row = document.createElement('div');
            row.className = 'p-5 border border-gray-200 rounded-xl bg-gray-50 relative';
            row.id = `pengikut-${pengikutIndex}`;

            row.innerHTML = `
                <button type="button" onclick="removePengikut(${pengikutIndex})" class="absolute top-3 right-3 text-red-500 hover:text-red-700 bg-red-100 hover:bg-red-200 w-8 h-8 flex justify-center items-center rounded-lg transition-colors" title="Hapus Anggota">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider border-b border-gray-200 pb-2">Data Anggota #${pengikutIndex}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="pengikut_nama[]" placeholder="Sesuai KTP/KK" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">NIK / No. KTP</label>
                        <input type="number" name="pengikut_nik[]" placeholder="16 Digit NIK" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="pengikut_jk[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="pengikut_tgl_lahir[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Status Perkawinan</label>
                        <select name="pengikut_status[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-700">Keterangan (Hub)</label>
                        <input type="text" name="pengikut_ket[]" placeholder="Contoh: Istri, Anak" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                    </div>
                </div>
            `;
            container.appendChild(row);
        }

        function removePengikut(id) {
            document.getElementById(`pengikut-${id}`).remove();
            const container = document.getElementById('pengikut-container');
            if (container.children.length === 0) {
                document.getElementById('empty-pengikut-msg').style.display = 'block';
            }
        }

        // Script Sidebar & File Upload
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