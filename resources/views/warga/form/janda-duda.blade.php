<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengajuan Keterangan Janda/Duda - SINDESA</title>
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengajuan Surat Keterangan Janda / Duda</h2>
            <p class="text-gray-500">Silakan lengkapi formulir di bawah ini dengan data yang sebenarnya untuk keperluan
                administrasi.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                    {{ session('success') }}</div>
            @endif
            

            <form action="{{ route('warga.form.janda-duda.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas Pemohon
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK</label>
                        <input type="number" name="nik_pemohon" value="{{ old('nik_pemohon', Auth::user()->nik) }}"
                            placeholder="16 Digit NIK"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                        <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon', Auth::user()->name) }}"
                            placeholder="Sesuai KTP"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir_pemohon"
                            value="{{ old('tempat_lahir_pemohon', Auth::user()->tempat_lahir) }}"
                            placeholder="Kota/Kabupaten"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_pemohon"
                            value="{{ old('tanggal_lahir_pemohon', Auth::user()->tanggal_lahir) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin (Status)</label>
                        <select name="jenis_kelamin_pemohon"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            @php $jk = old('jenis_kelamin_pemohon', Auth::user()->jenis_kelamin); @endphp
                            <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ $jk == 'Laki-Laki' || $jk == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki (Duda)</option>
                            <option value="Perempuan" {{ $jk == 'Perempuan' ? 'selected' : '' }}>Perempuan (Janda)
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Penyebab Status</label>
                        <select name="penyebab_status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all"
                            required>
                            <option value="" disabled selected>-- Pilih Alasan --</option>
                            <option value="Cerai Mati" {{ old('penyebab_status') == 'Cerai Mati' ? 'selected' : '' }}>
                                Cerai Mati (Meninggal)</option>
                            <option value="Cerai Hidup" {{ old('penyebab_status') == 'Cerai Hidup' ? 'selected' : '' }}>
                                Cerai Hidup</option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Pemohon</label>
                    <textarea name="alamat_pemohon" rows="2" placeholder="Contoh: Kamp. Baru Desa Buttu Sawe..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>{{ old('alamat_pemohon', Auth::user()->alamat_lengkap) }}</textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Mantan
                    Pasangan (Suami/Istri)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Mantan Suami / Istri</label>
                        <input type="text" name="nama_mantan" value="{{ old('nama_mantan') }}"
                            placeholder="Nama Lengkap"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tahun Berpisah / Meninggal</label>
                        <input type="number" name="tahun_berpisah" value="{{ old('tahun_berpisah') }}"
                            placeholder="Contoh: 2008" min="1900" max="2099"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Mantan Pasangan
                        (Terakhir)</label>
                    <textarea name="alamat_mantan" rows="2" placeholder="Contoh: Kamp. Baru Desa Buttu Sawe..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all"
                        required>{{ old('alamat_mantan') }}</textarea>
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
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Akta Cerai / Surat
                        Kematian</label>
                    <span class="block text-xs text-gray-500 mb-4">Surat bukti kematian suami/istri atau Akta Cerai
                        pengadilan. Maks 5MB.</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-bukti" name="file_bukti" class="hidden"
                            onchange="updateFileName(this, 'name-bukti')">
                        <label for="file-bukti"
                            class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                            <i class="fas fa-cloud-upload-alt"></i> Pilih File
                        </label>
                        <span id="name-bukti" class="text-sm text-gray-500 italic truncate">Belum ada file
                            dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')"
                        class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]"
                        required>
                    <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan bahwa sejak tahun
                        tersebut saya telah berpisah/ditinggal pasangan, sampai saat ini belum menikah lagi, dan data
                        ini siap diproses ke Kantor Desa.</label>
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