<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengajuan Akta Lahir - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CSS kecil untuk mempercantik scrollbar di dalam menu navigasi sidebar */
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Pengajuan Surat Pengantar Akta Lahir</h2>
            <p class="text-gray-500">Silakan lengkapi formulir di bawah ini dengan data yang valid sesuai dokumen.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] max-w-4xl mx-auto p-6 md:p-10">
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6"><i class="fas fa-check-circle"></i>
                    {{ session('success') }}</div>
            @endif
            

            <form action="{{ route('warga.form.akta-lahir.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas
                    Anak/Pemohon</h3>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                    <input type="text" name="nama_anak" placeholder="Masukkan Nama Lengkap"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir_anak" placeholder="Kota/Kabupaten"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_anak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin_anak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none"
                            required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                        <select name="agama_anak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none appearance-none"
                            required>
                            <option value="" disabled selected>-- Pilih Agama --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen Protestan">Kristen Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan_anak" value="Indonesia"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Anak Ke-</label>
                        <input type="number" name="anak_ke" placeholder="Contoh: 1" min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Alamat Lengkap</label>
                    <textarea name="alamat_anak" rows="3" placeholder="Contoh: Dusun Kamali..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                        required></textarea>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Data Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap Ayah</label>
                        <input type="text" name="nama_ayah"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Ayah</label>
                        <input type="number" name="nik_ayah"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap Ibu</label>
                        <input type="text" name="nama_ibu"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Ibu</label>
                        <input type="number" name="nik_ibu"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] outline-none">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Dokumen Pendukung
                </h3>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35]">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Foto Copy KK</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB (PDF/JPG/PNG).</span>
                    <div class="flex items-center gap-4">
                        <input type="file" id="file-kk" name="file_kk" class="hidden"
                            onchange="updateFileName(this, 'name-kk')" required>
                        <label for="file-kk"
                            class="px-6 py-2 border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer hover:bg-[#1a5e35] hover:text-white transition-all"><i
                                class="fas fa-cloud-upload-alt"></i> Pilih File</label>
                        <span id="name-kk" class="text-sm text-gray-500 italic">Belum ada file dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35]">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Surat Pernyataan 2 Orang
                        Saksi</label>
                    <span class="block text-xs text-gray-500 mb-4">Wajib. Maksimal 5MB.</span>
                    <div class="flex items-center gap-4">
                        <input type="file" id="file-saksi" name="file_saksi" class="hidden"
                            onchange="updateFileName(this, 'name-saksi')" required>
                        <label for="file-saksi"
                            class="px-6 py-2 border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer hover:bg-[#1a5e35] hover:text-white transition-all"><i
                                class="fas fa-cloud-upload-alt"></i> Pilih File</label>
                        <span id="name-saksi" class="text-sm text-gray-500 italic">Belum ada file dipilih</span>
                    </div>
                </div>

                <div
                    class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35]">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Dokumen Lain-lain (Opsional)</label>
                    <div class="flex items-center gap-4">
                        <input type="file" id="file-lain" name="file_lain" class="hidden"
                            onchange="updateFileName(this, 'name-lain')">
                        <label for="file-lain"
                            class="px-6 py-2 border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer hover:bg-[#1a5e35] hover:text-white transition-all"><i
                                class="fas fa-cloud-upload-alt"></i> Pilih File</label>
                        <span id="name-lain" class="text-sm text-gray-500 italic">Belum ada file dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" class="mt-1 w-4 h-4" required>
                    <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan data yang saya
                        kirimkan adalah benar.</label>
                </div>

                <button type="submit"
                    class="bg-[#cfa03f] text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-[#b88e32] w-full sm:w-auto"><i
                        class="fas fa-paper-plane"></i> Kirim Pengajuan</button>
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