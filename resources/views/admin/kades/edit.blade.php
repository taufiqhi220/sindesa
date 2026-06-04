<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Edit Pejabat Kades - SINDESA</title>
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
                        class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Data Pejabat</h2>
                        <p class="text-gray-500 text-sm">Perbarui informasi profil dan spesimen Kepala Desa.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.kades.update', $kades->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')


                {{-- BANNER STATUS AKUN (Mirip Data Operator) --}}
                @php
                    $status = old('status', $kades->status);
                @endphp
                <div class="p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border {{ $status == 'active' ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-100 border-gray-300' }}"
                    id="statusBanner">
                    <div>
                        <h3 class="text-lg font-bold flex items-center gap-2 {{ $status == 'active' ? 'text-emerald-800' : 'text-gray-800' }}"
                            id="statusTitle">
                            @if($status == 'active')
                                <i class="fas fa-check-circle"></i> Status Jabatan: AKTIF MENJABAT
                            @else
                                <i class="fas fa-archive"></i> Status Jabatan: PURNA TUGAS
                            @endif
                        </h3>
                        <p class="text-sm mt-1 {{ $status == 'active' ? 'text-emerald-600' : 'text-gray-600' }}"
                            id="statusDesc">
                            @if($status == 'active')
                                Tanda Tangan Elektronik Kades ini <span class="font-bold">digunakan</span> untuk mengesahkan
                                dokumen warga.
                            @else
                                Riwayat Kades ini hanya diarsipkan dan <span class="font-bold">tidak bisa lagi</span>
                                mengesahkan dokumen.
                            @endif
                        </p>
                    </div>
                    <div class="shrink-0 bg-white p-1.5 rounded-xl shadow-sm border border-gray-100">
                        <select name="status" id="statusSelect" onchange="updateStatusUI(this)"
                            class="py-2.5 px-4 outline-none focus:ring-0 text-sm font-bold cursor-pointer rounded-lg bg-transparent">
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>🟢 AKTIF MENJABAT</option>
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>⚪ PURNA TUGAS</option>
                        </select>
                    </div>
                </div>

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
                            <input type="text" name="name" value="{{ old('name', $kades->name) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                                                <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">NIK (Kependudukan) <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik', $kades->nik) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">NIP (Pegawai)</label>
                            <input type="number" name="nip" value="{{ old('nip', $kades->nip) }}"
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>
                    </div>
                </div>

                {{-- UPLOAD TANDA TANGAN (Kondisi TTD Sebelumnya Ada/Tidak) --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-signature text-[#cfa03f]"></i> Tanda Tangan Elektronik
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                        {{-- Preview Spesimen Lama --}}
                        <div
                            class="p-5 border-2 border-gray-100 rounded-xl bg-gray-50 text-center flex flex-col items-center justify-center h-48 relative overflow-hidden">
                            <p
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 absolute top-4 left-0 w-full">
                                Spesimen Saat Ini</p>
                            @if($kades->ttd_path)
                                <img src="{{ asset('storage/' . $kades->ttd_path) }}" alt="Spesimen TTD"
                                    class="max-h-24 object-contain mt-6 mix-blend-multiply">
                            @else
                                <div class="mt-4 text-red-400">
                                    <i class="fas fa-times-circle text-3xl mb-2"></i>
                                    <p class="text-sm">Belum ada spesimen</p>
                                </div>
                            @endif
                        </div>

                        {{-- Form Upload Baru --}}
                        <div
                            class="bg-blue-50/50 border border-blue-100 p-5 rounded-xl h-48 flex flex-col justify-center">
                            <label class="block mb-2 text-sm font-semibold text-gray-800">Unggah Spesimen Baru <span
                                    class="text-xs text-gray-500 font-normal">(Opsional)</span></label>

                            <label for="ttd_path"
                                class="flex flex-col items-center justify-center w-full flex-1 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-3 pb-4">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-blue-500 mb-2"></i>
                                    <p class="mb-1 text-xs text-gray-600" id="fileName"><span
                                            class="font-bold text-blue-600">Pilih berkas PNG</span> baru</p>
                                </div>
                                <input id="ttd_path" name="ttd_path" type="file" accept="image/png" class="hidden"
                                    onchange="showFileName(this)" />
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
                            <input type="email" name="email" value="{{ old('email', $kades->email) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Handphone / WA</label>
                            <input type="number" name="phone" value="{{ old('phone', $kades->phone) }}"
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2 mt-2 p-6 bg-gray-50 border border-gray-200 border-dashed rounded-xl">
                            <label class="block mb-2 text-sm font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-key text-[#cfa03f]"></i> Reset Kata Sandi <span
                                    class="text-xs text-gray-500 font-normal ml-2">(Opsional)</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-4">Biarkan kedua kolom di bawah ini tetap kosong jika
                                Anda tidak ingin mengubah password pejabat saat ini.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <input type="password" name="password"
                                        placeholder="Ketik Sandi Baru (Min. 8 karakter)"
                                        class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                                </div>
                                <div>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi Sandi Baru"
                                        class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8 pb-10">
                    <button type="submit"
                        class="px-10 py-4 bg-[#1a5e35] hover:bg-[#11442b] text-white rounded-xl font-bold transition-all shadow-[0_4px_15px_rgba(26,94,53,0.25)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(26,94,53,0.35)] flex items-center gap-2">
                        <i class="fas fa-save"></i> PERBARUI DATA PEJABAT
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

        // Script ubah file name TTD
        function showFileName(input) {
            const fileNameDisplay = document.getElementById('fileName');
            if (input.files && input.files.length > 0) {
                fileNameDisplay.innerHTML = `<span class="font-bold text-blue-600"><i class="fas fa-check-circle"></i> ${input.files[0].name}</span>`;
            } else {
                fileNameDisplay.innerHTML = `<span class="font-bold text-blue-600">Pilih berkas PNG</span> baru`;
            }
        }

        // Script banner status realtime
        function updateStatusUI(select) {
            const banner = document.getElementById('statusBanner');
            const title = document.getElementById('statusTitle');
            const desc = document.getElementById('statusDesc');

            if (select.value === 'active') {
                banner.className = 'p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border bg-emerald-50 border-emerald-200';
                title.className = 'text-lg font-bold flex items-center gap-2 text-emerald-800';
                title.innerHTML = '<i class="fas fa-check-circle"></i> Status Jabatan: AKTIF MENJABAT';
                desc.className = 'text-sm mt-1 text-emerald-600';
                desc.innerHTML = 'Tanda Tangan Elektronik Kades ini <span class="font-bold">digunakan</span> untuk mengesahkan dokumen warga.';
            } else {
                banner.className = 'p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border bg-gray-100 border-gray-300';
                title.className = 'text-lg font-bold flex items-center gap-2 text-gray-800';
                title.innerHTML = '<i class="fas fa-archive"></i> Status Jabatan: PURNA TUGAS';
                desc.className = 'text-sm mt-1 text-gray-600';
                desc.innerHTML = 'Riwayat Kades ini hanya diarsipkan dan <span class="font-bold">tidak bisa lagi</span> mengesahkan dokumen.';
            }
        }
    </script>

    @include('partials.sweetalert')
</body>

</html>


