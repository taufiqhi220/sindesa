<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Tambah Pejabat Kades - SINDESA</title>
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

        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('admin.data-kades') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#1a5e35] mb-4 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kepala Desa
                </a>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-[#cfa03f]/10 text-[#cfa03f] rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah Pejabat Baru</h2>
                        <p class="text-gray-500 text-sm">Tambahkan data Kepala Desa untuk keperluan pengesahan dokumen.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.kades.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf


                {{-- INFORMASI DASAR --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-id-card text-[#cfa03f]"></i> Profil Pejabat
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                                                <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap (Sesuai Gelar)
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Drs. H. Budi Santoso, M.Si" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik') }}"
                                placeholder="Masukkan 16 digit NIK" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">NIP (Nomor Induk Pegawai)</label>
                            <input type="number" name="nip" value="{{ old('nip') }}"
                                placeholder="Masukkan NIP (Opsional)"
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Status Jabatan <span
                                    class="text-red-500">*</span></label>
                            <select name="status" required id="statusSelect"
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none font-semibold cursor-pointer">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}
                                    class="text-emerald-600">🟢 Aktif Menjabat</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}
                                    class="text-gray-600">⚪ Purna Tugas (Arsip)</option>
                            </select>
                            <p class="text-[10px] text-gray-500 mt-2"><i class="fas fa-info-circle text-amber-500"></i>
                                Memilih "Aktif" otomatis menonaktifkan Kades saat ini.</p>
                        </div>
                    </div>
                </div>

                {{-- UPLOAD TANDA TANGAN --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-signature text-[#cfa03f]"></i> Tanda Tangan Elektronik
                    </h3>

                    <div class="bg-blue-50/50 border border-blue-100 p-5 rounded-xl">
                        <label class="block mb-2 text-sm font-semibold text-gray-800">Unggah Spesimen TTD <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-4">Gunakan gambar dengan format PNG (berlatar belakang
                            transparan) agar hasil cetak surat terlihat rapi.</p>

                        <div class="flex items-center justify-center w-full">
                            <label for="ttd_path"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#cfa03f] border-dashed rounded-xl cursor-pointer bg-white hover:bg-[#cfa03f]/5 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-[#cfa03f] mb-2"></i>
                                    <p class="mb-1 text-sm text-gray-600" id="fileName"><span
                                            class="font-bold text-[#1a5e35]">Klik untuk mengunggah</span> spesimen</p>
                                    <p class="text-xs text-gray-400">PNG Transparan (Maks. 2MB)</p>
                                </div>
                                <input id="ttd_path" name="ttd_path" type="file" accept="image/png" class="hidden"
                                    required onchange="showFileName(this)" />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- KONTAK & KREDENSIAL --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-lock text-[#cfa03f]"></i> Akses Sistem SINDESA
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Email Akun <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="kades@desabuttusawe.id" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Handphone / WA</label>
                            <input type="number" name="phone" value="{{ old('phone') }}" placeholder="Opsional"
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div
                            class="md:col-span-2 mt-2 p-5 bg-amber-50/50 border border-amber-200/60 rounded-xl grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Kata Sandi (Password)
                                    <span class="text-red-500">*</span></label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" required
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Ulangi Kata Sandi <span
                                        class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation"
                                    placeholder="Ulangi password di atas" required
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8 pb-10">
                    <button type="submit"
                        class="px-10 py-4 bg-[#1a5e35] hover:bg-[#11442b] text-white rounded-xl font-bold transition-all shadow-[0_4px_15px_rgba(26,94,53,0.25)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(26,94,53,0.35)] flex items-center gap-2">
                        <i class="fas fa-save"></i> SIMPAN PEJABAT
                    </button>
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

        function showFileName(input) {
            const fileNameDisplay = document.getElementById('fileName');
            if (input.files && input.files.length > 0) {
                fileNameDisplay.innerHTML = `<span class="font-bold text-[#1a5e35]"><i class="fas fa-check-circle"></i> ${input.files[0].name}</span>`;
            } else {
                fileNameDisplay.innerHTML = `<span class="font-bold text-[#1a5e35]">Klik untuk mengunggah</span> spesimen`;
            }
        }
    </script>

    @include('partials.sweetalert')
</body>

</html>


