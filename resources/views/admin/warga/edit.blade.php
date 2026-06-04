<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Edit Data Warga - SINDESA</title>
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

        select:disabled {
            background-color: #f3f4f6;
            opacity: 0.6;
            cursor: not-allowed;
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

        <div class="max-w-5xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('admin.data-warga') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#1a5e35] mb-4 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Data Warga
                </a>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Verifikasi & Edit Warga</h2>
                        <p class="text-gray-500 text-sm">Periksa data warga dan aktifkan akun agar dapat menggunakan
                            layanan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.warga.update', $warga->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')


                {{-- BANNER STATUS AKUN --}}
                @php
                    $status = old('status', $warga->status);
                @endphp
                <div class="p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border mb-6 {{ $status == 'active' ? 'bg-emerald-50 border-emerald-200' : ($status == 'suspended' ? 'bg-gray-100 border-gray-300' : 'bg-red-50 border-red-200') }}"
                    id="statusBanner">
                    <div>
                        <h3 class="text-lg font-bold flex items-center gap-2 {{ $status == 'active' ? 'text-emerald-800' : ($status == 'suspended' ? 'text-gray-800' : 'text-red-800') }}"
                            id="statusTitle">
                            @if($status == 'active')
                                <i class="fas fa-check-circle"></i> Status Akun: AKTIF
                            @elseif($status == 'suspended')
                                <i class="fas fa-ban"></i> Status Akun: DITANGGUHKAN
                            @else
                                <i class="fas fa-exclamation-circle"></i> Status Akun: NON-AKTIF (Baru Mendaftar)
                            @endif
                        </h3>
                        <p class="text-sm mt-1 {{ $status == 'active' ? 'text-emerald-600' : ($status == 'suspended' ? 'text-gray-600' : 'text-red-600') }}"
                            id="statusDesc">
                            @if($status == 'active')
                                Warga ini sudah diverifikasi dan dapat mengakses layanan SINDESA secara penuh.
                            @elseif($status == 'suspended')
                                Akun warga ini sedang diblokir/ditangguhkan dan tidak bisa mengakses layanan.
                            @else
                                Data warga belum diverifikasi. Silakan periksa KTP di bawah, lalu ubah status menjadi Aktif.
                            @endif
                        </p>
                    </div>
                    <div class="shrink-0 bg-white p-1.5 rounded-xl shadow-sm border border-gray-100">
                        <select name="status" id="statusSelect" onchange="updateStatusUI(this)"
                            class="py-2.5 px-4 outline-none focus:ring-0 text-sm font-bold cursor-pointer rounded-lg bg-transparent">
                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>🔴 NON AKTIF</option>
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>🟢 AKTIF / TERVERIFIKASI
                            </option>
                            <option value="suspended" {{ $status == 'suspended' ? 'selected' : '' }}>⚫ DIBLOKIR</option>
                        </select>
                    </div>
                </div>

                {{-- DOKUMEN VERIFIKASI KTP (BARU DITAMBAHKAN) --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100 mb-6">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-id-badge text-[#cfa03f]"></i> Dokumen Verifikasi (Foto KTP)
                    </h3>

                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        @if($warga->foto_ktp)
                            <div class="w-full md:w-1/2 lg:w-1/3 shrink-0 relative group">
                                <div class="rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm relative">
                                    <img src="{{ asset('storage/' . $warga->foto_ktp) }}" alt="KTP {{ $warga->name }}"
                                        class="w-full h-auto object-cover max-h-56 transition-transform duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300 pointer-events-none">
                                        <span
                                            class="text-white font-bold bg-black/50 px-3 py-1.5 rounded-lg flex items-center gap-2 text-sm">
                                            <i class="fas fa-search-plus"></i> Lihat Penuh
                                        </span>
                                    </div>
                                    {{-- Link agar KTP bisa diklik dan dibuka di modal --}}
                                    <button type="button" onclick="openKtpModal('{{ asset('storage/' . $warga->foto_ktp) }}')"
                                        class="absolute inset-0 z-10 w-full h-full cursor-pointer opacity-0"></button>
                                </div>
                            </div>
                            <div class="flex-1 bg-blue-50/80 p-5 rounded-xl border border-blue-100">
                                <h4 class="font-bold text-blue-800 mb-2 flex items-center gap-2">
                                    <i class="fas fa-check-circle text-blue-500"></i> KTP Tersedia
                                </h4>
                                <p class="text-sm text-blue-700/80 leading-relaxed mb-4">
                                    Silakan periksa dan cocokkan NIK, Nama, dan alamat pada gambar KTP di samping dengan
                                    form Data Kependudukan di bawah. Pastikan data valid sebelum mengaktifkan akun warga
                                    ini.
                                </p>
                                <a href="{{ asset('storage/' . $warga->foto_ktp) }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-white text-blue-600 border border-blue-200 px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 hover:text-white transition-colors shadow-sm">
                                    <i class="fas fa-external-link-alt"></i> Buka Gambar KTP
                                </a>
                            </div>
                        @else
                            <div
                                class="w-full bg-red-50 p-6 rounded-xl border border-red-100 flex flex-col md:flex-row items-center gap-4 text-red-700">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-image text-xl text-red-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold mb-1">Foto KTP Tidak Ditemukan</h4>
                                    <p class="text-sm text-red-600/80">Warga ini belum mengunggah foto KTP atau mendaftar
                                        sebelum fitur upload KTP diwajibkan. Anda mungkin perlu melakukan verifikasi manual
                                        secara tatap muka.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- DATA KEPENDUDUKAN --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100 mb-6">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-id-card text-[#cfa03f]"></i> Data Kependudukan Utama
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $warga->name) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Induk Kependudukan (NIK)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik', $warga->nik) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Kartu Keluarga (KK)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="no_kk" value="{{ old('no_kk', $warga->no_kk) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Agama <span
                                    class="text-red-500">*</span></label>
                            <select name="agama" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="Islam" {{ old('agama', $warga->agama) == 'Islam' ? 'selected' : '' }}>Islam
                                </option>
                                <option value="Kristen" {{ old('agama', $warga->agama) == 'Kristen' ? 'selected' : '' }}>
                                    Kristen Protestan</option>
                                <option value="Katolik" {{ old('agama', $warga->agama) == 'Katolik' ? 'selected' : '' }}>
                                    Katolik</option>
                                <option value="Hindu" {{ old('agama', $warga->agama) == 'Hindu' ? 'selected' : '' }}>Hindu
                                </option>
                                <option value="Buddha" {{ old('agama', $warga->agama) == 'Buddha' ? 'selected' : '' }}>
                                    Buddha</option>
                                <option value="Konghucu" {{ old('agama', $warga->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="Laki-Laki" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Tempat Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $warga->tempat_lahir) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $warga->tanggal_lahir) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm text-gray-700">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Status Perkawinan <span
                                    class="text-red-500">*</span></label>
                            <select name="status_perkawinan" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="Belum Kawin" {{ old('status_perkawinan', $warga->status_perkawinan) == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('status_perkawinan', $warga->status_perkawinan) == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ old('status_perkawinan', $warga->status_perkawinan) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_perkawinan', $warga->status_perkawinan) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Pekerjaan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $warga->pekerjaan) }}"
                                required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Kewarganegaraan <span
                                    class="text-red-500">*</span></label>
                            <select name="kewarganegaraan" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="WNI" {{ old('kewarganegaraan', $warga->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>Warga Negara Indonesia (WNI)</option>
                                <option value="WNA" {{ old('kewarganegaraan', $warga->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>Warga Negara Asing (WNA)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ALAMAT DOMISILI --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100 mb-6">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[#cfa03f]"></i> Alamat Domisili Wilayah
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Lengkap / Jalan / Dusun
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="alamat_lengkap"
                                value="{{ old('alamat_lengkap', $warga->alamat_lengkap) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">RT / RW <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="rt_rw" value="{{ old('rt_rw', $warga->rt_rw) }}" required
                                class="w-full md:w-1/2 py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div
                            class="md:col-span-2 mt-4 p-6 bg-gray-50 border border-gray-200 rounded-xl grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $provinces = \Illuminate\Support\Facades\DB::table('indonesia_provinces')->get();
                            @endphp
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Provinsi <span
                                        class="text-red-500">*</span></label>
                                <select id="provinsi" name="provinsi" required
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm appearance-none cursor-pointer">
                                    <option value="">Pilih Provinsi...</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->code }}" {{ old('provinsi', $warga->provinsi) == $province->code ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Kota / Kabupaten <span
                                        class="text-red-500">*</span></label>
                                <select id="kota" name="kota" required disabled
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm appearance-none">
                                    <option value="">Pilih Provinsi Dahulu...</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Kecamatan <span
                                        class="text-red-500">*</span></label>
                                <select id="kecamatan" name="kecamatan" required disabled
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm appearance-none">
                                    <option value="">Pilih Kota Dahulu...</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Kelurahan / Desa <span
                                        class="text-red-500">*</span></label>
                                <select id="kelurahan_desa" name="kelurahan_desa" required disabled
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm appearance-none">
                                    <option value="">Pilih Kecamatan Dahulu...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KONTAK & KREDENSIAL --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-lock text-[#cfa03f]"></i> Informasi Kontak & Kredensial
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Telepon / WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $warga->phone) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Email Aktif <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $warga->email) }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2 mt-4 p-6 bg-gray-50 border border-gray-200 border-dashed rounded-xl">
                            <label class="block mb-2 text-sm font-bold text-gray-800">Ganti Kata Sandi <span
                                    class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                            <p class="text-xs text-gray-500 mb-4">Biarkan kolom ini kosong jika Anda tidak ingin mereset
                                password warga ini.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="password" name="password" placeholder="Ketik Sandi Baru"
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                                <input type="password" name="password_confirmation" placeholder="Ketik Ulang Sandi Baru"
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8 pb-10">
                    <button type="submit"
                        class="px-10 py-4 bg-[#1a5e35] hover:bg-[#11442b] text-white rounded-xl font-bold transition-all shadow-[0_4px_15px_rgba(26,94,53,0.25)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(26,94,53,0.35)] flex items-center gap-2">
                        <i class="fas fa-save"></i> PERBARUI DATA WARGA
                    </button>
                </div>

            </form>
        </div>
    </main>

    {{-- Modal View KTP --}}
    <div id="ktpModal" class="fixed inset-0 bg-black/90 z-[1001] hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="relative w-full h-full overflow-hidden flex flex-col items-center justify-center">
            
            <div class="absolute top-0 left-0 w-full p-4 flex justify-between items-center z-50 bg-gradient-to-b from-black/60 to-transparent">
                <span class="text-white/80 text-sm font-medium bg-black/50 px-4 py-1.5 rounded-full backdrop-blur-sm pointer-events-none shadow-lg">
                    <i class="fas fa-hand-pointer mr-1"></i> Klik untuk Zoom &bull; Tahan & Geser
                </span>
                <button onclick="closeKtpModal()" class="text-white/70 hover:text-red-500 hover:bg-white/10 w-10 h-10 rounded-full flex items-center justify-center transition-all focus:outline-none backdrop-blur-sm">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="ktpModalContainer" class="w-full h-full flex items-center justify-center cursor-grab select-none" style="touch-action: none;">
                <img id="ktpModalImg" src="" alt="KTP Penuh" class="max-h-[85vh] max-w-[90vw] object-contain shadow-2xl rounded-sm" draggable="false">
            </div>
        </div>
    </div>

    <script>
        // Logic Pan & Zoom KTP
        let currentZoom = 1;
        let isDragging = false;
        let didMove = false;
        let startX, startY, initialTranslateX, initialTranslateY;
        let translateX = 0, translateY = 0;
        
        const ktpModal = document.getElementById('ktpModal');
        const container = document.getElementById('ktpModalContainer');
        const img = document.getElementById('ktpModalImg');

        function openKtpModal(imgUrl) {
            img.src = imgUrl;
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
            updateTransform(false);
            
            ktpModal.classList.remove('hidden');
            // Trigger reflow for fade in
            void ktpModal.offsetWidth;
            ktpModal.classList.remove('opacity-0');
            document.body.style.overflow = 'hidden';
        }

        function closeKtpModal() {
            ktpModal.classList.add('opacity-0');
            setTimeout(() => {
                ktpModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        function updateTransform(withTransition = true) {
            img.style.transition = withTransition ? 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)' : 'none';
            img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
        }

        container.addEventListener('mousedown', (e) => {
            if (e.target !== img) {
                closeKtpModal();
                return;
            }

            isDragging = true;
            didMove = false;
            startX = e.clientX;
            startY = e.clientY;
            initialTranslateX = translateX;
            initialTranslateY = translateY;
            container.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            
            if (Math.abs(dx) > 3 || Math.abs(dy) > 3) {
                didMove = true;
            }
            
            if (didMove) {
                translateX = initialTranslateX + dx;
                translateY = initialTranslateY + dy;
                updateTransform(false); // No transition while dragging
            }
        });

        window.addEventListener('mouseup', (e) => {
            if (!isDragging) return;
            isDragging = false;
            container.style.cursor = 'grab';
            
            if (!didMove) {
                // It was a click! Toggle Zoom
                if (currentZoom === 1) {
                    currentZoom = 2; // Zoom in
                } else {
                    currentZoom = 1; // Reset
                    translateX = 0;
                    translateY = 0;
                }
                updateTransform(true); // Smooth transition on click
            }
        });
        
        // Mouse wheel zoom
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            currentZoom += e.deltaY > 0 ? -0.2 : 0.2;
            if (currentZoom < 0.5) currentZoom = 0.5;
            if (currentZoom > 4) currentZoom = 4;
            updateTransform(true);
        }, { passive: false });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function updateStatusUI(select) {
            const banner = document.getElementById('statusBanner');
            const title = document.getElementById('statusTitle');
            const desc = document.getElementById('statusDesc');

            if (select.value === 'active') {
                banner.className = 'p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border mb-6 bg-emerald-50 border-emerald-200';
                title.className = 'text-lg font-bold flex items-center gap-2 text-emerald-800';
                title.innerHTML = '<i class="fas fa-check-circle"></i> Status Akun: AKTIF';
                desc.className = 'text-sm mt-1 text-emerald-600';
                desc.innerHTML = 'Warga ini sudah diverifikasi dan dapat mengakses layanan SINDESA secara penuh.';
            } else if (select.value === 'suspended') {
                banner.className = 'p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border mb-6 bg-gray-100 border-gray-300';
                title.className = 'text-lg font-bold flex items-center gap-2 text-gray-800';
                title.innerHTML = '<i class="fas fa-ban"></i> Status Akun: DITANGGUHKAN';
                desc.className = 'text-sm mt-1 text-gray-600';
                desc.innerHTML = 'Akun warga ini sedang diblokir/ditangguhkan dan tidak bisa mengakses layanan.';
            } else {
                banner.className = 'p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-5 transition-colors border mb-6 bg-red-50 border-red-200';
                title.className = 'text-lg font-bold flex items-center gap-2 text-red-800';
                title.innerHTML = '<i class="fas fa-exclamation-circle"></i> Status Akun: NON-AKTIF';
                desc.className = 'text-sm mt-1 text-red-600';
                desc.innerHTML = 'Data warga belum diverifikasi. Silakan periksa KTP di bawah, lalu ubah status menjadi Aktif.';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('provinsi');
            const citySelect = document.getElementById('kota');
            const districtSelect = document.getElementById('kecamatan');
            const villageSelect = document.getElementById('kelurahan_desa');

            const oldProvinsi = "{{ old('provinsi', $warga->provinsi ?? '') }}";
            const oldKota = "{{ old('kota', $warga->kota ?? '') }}";
            const oldKecamatan = "{{ old('kecamatan', $warga->kecamatan ?? '') }}";
            const oldDesa = "{{ old('kelurahan_desa', $warga->kelurahan_desa ?? '') }}";
            const baseUrl = "{{ url('/') }}";

            function populateSelect(selectElement, data, placeholder, selectedValue = null) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    const isSelected = (item.code == selectedValue) ? 'selected' : '';
                    selectElement.innerHTML += `<option value="${item.code}" ${isSelected}>${item.name}</option>`;
                });
                selectElement.disabled = false;
                selectElement.classList.remove('cursor-not-allowed', 'bg-gray-100', 'border-red-400', 'text-red-500');
                selectElement.classList.add('cursor-pointer', 'bg-white');
            }

            function resetSelect(selectElement, placeholder, isError = false) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                selectElement.disabled = true;
                selectElement.classList.add('cursor-not-allowed');
                selectElement.classList.remove('cursor-pointer', 'bg-white');

                if (isError) {
                    selectElement.classList.add('bg-red-50', 'border-red-400', 'text-red-500');
                    selectElement.classList.remove('bg-gray-100');
                } else {
                    selectElement.classList.add('bg-gray-100');
                    selectElement.classList.remove('bg-red-50', 'border-red-400', 'text-red-500');
                }
            }

            function loadCities(provinceCode, selectedCity = null, callback = null) {
                resetSelect(citySelect, 'Memuat Kota...');
                fetch(`${baseUrl}/data/cities?province_code=${provinceCode}`)
                    .then(res => {
                        if (!res.ok) throw new Error('API Gagal');
                        return res.json();
                    })
                    .then(data => {
                        populateSelect(citySelect, data, 'Pilih Kota/Kabupaten...', selectedCity);
                        if (callback) callback();
                    })
                    .catch(err => resetSelect(citySelect, '⚠ Error: Rute API tidak ditemukan', true));
            }

            function loadDistricts(cityCode, selectedDistrict = null, callback = null) {
                resetSelect(districtSelect, 'Memuat Kecamatan...');
                fetch(`${baseUrl}/data/districts?city_code=${cityCode}`)
                    .then(res => {
                        if (!res.ok) throw new Error('API Gagal');
                        return res.json();
                    })
                    .then(data => {
                        populateSelect(districtSelect, data, 'Pilih Kecamatan...', selectedDistrict);
                        if (callback) callback();
                    })
                    .catch(err => resetSelect(districtSelect, '⚠ Error: Rute API tidak ditemukan', true));
            }

            function loadVillages(districtCode, selectedVillage = null) {
                resetSelect(villageSelect, 'Memuat Kelurahan/Desa...');
                fetch(`${baseUrl}/data/villages?district_code=${districtCode}`)
                    .then(res => {
                        if (!res.ok) throw new Error('API Gagal');
                        return res.json();
                    })
                    .then(data => {
                        populateSelect(villageSelect, data, 'Pilih Kelurahan/Desa...', selectedVillage);
                    })
                    .catch(err => resetSelect(villageSelect, '⚠ Error: Rute API tidak ditemukan', true));
            }

            if (oldProvinsi) {
                provinceSelect.value = oldProvinsi;
                loadCities(oldProvinsi, oldKota, function () {
                    if (oldKota) {
                        loadDistricts(oldKota, oldKecamatan, function () {
                            if (oldKecamatan) {
                                loadVillages(oldKecamatan, oldDesa);
                            }
                        });
                    }
                });
            }

            provinceSelect.addEventListener('change', function () {
                const provinceCode = this.value;
                resetSelect(citySelect, 'Memuat Kota...');
                resetSelect(districtSelect, 'Pilih Kota Dahulu...');
                resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');
                if (provinceCode) loadCities(provinceCode);
                else resetSelect(citySelect, 'Pilih Provinsi Dahulu...');
            });

            citySelect.addEventListener('change', function () {
                const cityCode = this.value;
                resetSelect(districtSelect, 'Memuat Kecamatan...');
                resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');
                if (cityCode) loadDistricts(cityCode);
                else resetSelect(districtSelect, 'Pilih Kota Dahulu...');
            });

            districtSelect.addEventListener('change', function () {
                const districtCode = this.value;
                resetSelect(villageSelect, 'Memuat Kelurahan/Desa...');
                if (districtCode) loadVillages(districtCode);
                else resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');
            });
        });
    </script>

    @include('partials.sweetalert')
</body>

</html>

