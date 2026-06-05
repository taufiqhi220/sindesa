<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Registrasi Warga - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script reCAPTCHA v3 Resmi dari Google --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <style>
        select:disabled {
            background-color: #f3f4f6;
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] text-[#333333] m-0 p-0 min-h-screen flex flex-col">

    <nav
        class="flex justify-between items-center px-6 lg:px-[10%] py-4 bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <a href="{{ url('/') }}" class="flex items-center">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-8 md:h-10">
        </a>
        <a href="{{ route('login') }}"
            class="text-[#1a5e35] font-semibold text-sm md:text-base hover:text-[#cfa03f] transition-colors flex items-center gap-2">
            Masuk <i class="fas fa-sign-in-alt"></i>
        </a>
    </nav>

    {{-- Background Hijau dengan padding bottom yang diperbesar --}}
    <div class="bg-gradient-to-r from-[#11442b] to-[#1a5e35] pt-12 pb-32 px-4 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-[#cfa03f]/10 rounded-full blur-3xl"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Pendaftaran Akun Warga</h1>
            <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Silakan lengkapi formulir di bawah ini dengan data yang valid sesuai KTP dan Kartu Keluarga Anda untuk
                mendapatkan akses layanan mandiri Desa Buttu Sawe.
            </p>
        </div>
    </div>

    {{-- Main Container: Diberi z-20 agar mengambang di atas background hijau --}}
    <main class="px-4 -mt-24 pb-20 relative z-20 flex-1">
        <div class="max-w-4xl mx-auto">

            {{-- Tambahkan ID registerForm untuk ditangkap JavaScript --}}
            <form id="registerForm" action="{{ route('register') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- Input Hidden untuk menyimpan token reCAPTCHA --}}
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                {{-- BANNER INFORMASI VERIFIKASI --}}
                <div
                    class="bg-white border-l-4 border-blue-500 p-5 rounded-r-xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] flex items-start gap-4 mb-2">
                    <i class="fas fa-info-circle text-blue-500 text-2xl mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-blue-800 text-sm mb-1">Informasi Status Akun</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Demi keamanan, akun yang baru didaftarkan akan berstatus <b
                                class="text-red-500">NON-AKTIF</b>.
                            Admin Desa akan melakukan verifikasi kecocokan data Anda dengan <b>Foto KTP</b> yang
                            diunggah. Akun baru dapat digunakan setelah disetujui.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 rounded-r-xl shadow-sm mb-6"
                        role="alert">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <i class="fas fa-exclamation-triangle"></i> Gagal Menyimpan Data:
                        </div>
                        <ul class="list-disc list-inside text-sm ml-2 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM DATA KEPENDUDUKAN --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-id-card text-[#cfa03f]"></i> Data Kependudukan Utama
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Sesuai KTP tanpa singkatan" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Induk Kependudukan (NIK)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Kartu Keluarga (KK)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="no_kk" value="{{ old('no_kk') }}" placeholder="16 Digit Nomor KK"
                                required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Agama <span
                                    class="text-red-500">*</span></label>
                            <select name="agama" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="" disabled selected>-- Pilih Agama --</option>
                                <option value="Islam" @if(old('agama') == 'Islam') selected @endif>Islam</option>
                                <option value="Kristen" @if(old('agama') == 'Kristen') selected @endif>Kristen Protestan
                                </option>
                                <option value="Katolik" @if(old('agama') == 'Katolik') selected @endif>Katolik</option>
                                <option value="Hindu" @if(old('agama') == 'Hindu') selected @endif>Hindu</option>
                                <option value="Buddha" @if(old('agama') == 'Buddha') selected @endif>Buddha</option>
                                <option value="Konghucu" @if(old('agama') == 'Konghucu') selected @endif>Konghucu</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki" @if(old('jenis_kelamin') == 'Laki-Laki') selected @endif>
                                    Laki-Laki</option>
                                <option value="Perempuan" @if(old('jenis_kelamin') == 'Perempuan') selected @endif>
                                    Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Tempat Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                placeholder="Kota/Kabupaten Lahir" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm text-gray-700">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Status Perkawinan <span
                                    class="text-red-500">*</span></label>
                            <select name="status_perkawinan" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="Belum Kawin" @if(old('status_perkawinan') == 'Belum Kawin') selected
                                @endif>Belum Kawin</option>
                                <option value="Kawin" @if(old('status_perkawinan') == 'Kawin') selected @endif>Kawin
                                </option>
                                <option value="Cerai Hidup" @if(old('status_perkawinan') == 'Cerai Hidup') selected
                                @endif>Cerai Hidup</option>
                                <option value="Cerai Mati" @if(old('status_perkawinan') == 'Cerai Mati') selected @endif>
                                    Cerai Mati</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Pekerjaan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                placeholder="Contoh: Petani / Wiraswasta" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Kewarganegaraan <span
                                    class="text-red-500">*</span></label>
                            <select name="kewarganegaraan" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm appearance-none">
                                <option value="WNI" selected>Warga Negara Indonesia (WNI)</option>
                                <option value="WNA">Warga Negara Asing (WNA)</option>
                            </select>
                        </div>

                        {{-- UPLOAD FOTO KTP --}}
                        <div class="md:col-span-2 mt-4 p-5 bg-amber-50/50 border border-amber-200/60 rounded-xl">
                            <label class="block mb-2 text-sm font-semibold text-gray-800">Unggah Foto KTP Asli <span
                                    class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">Foto KTP digunakan oleh Admin untuk memverifikasi
                                keabsahan data Anda. Pastikan tulisan terbaca jelas.</p>

                            <div class="flex items-center justify-center w-full">
                                <label for="foto_ktp"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#cfa03f] border-dashed rounded-xl cursor-pointer bg-white hover:bg-[#cfa03f]/5 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-id-badge text-3xl text-[#cfa03f] mb-2"></i>
                                        <p class="mb-1 text-sm text-gray-600" id="fileName"><span
                                                class="font-bold text-[#1a5e35]">Klik untuk mengunggah</span> file
                                            gambar</p>
                                        <p class="text-xs text-gray-400">PNG, JPG atau JPEG (Maksimal 2MB)</p>
                                    </div>
                                    <input id="foto_ktp" name="foto_ktp" type="file"
                                        accept="image/png, image/jpeg, image/jpg" class="hidden"
                                        onchange="showFileName(this)" />
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[#cfa03f]"></i> Alamat Domisili
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Lengkap / Jalan / Dusun
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="alamat_lengkap" value="{{ old('alamat_lengkap') }}"
                                placeholder="Contoh: Dusun Kamali" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">RT / RW <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="rt_rw" value="{{ old('rt_rw') }}" placeholder="Contoh: 001/002"
                                required
                                class="w-full md:w-1/2 py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div
                            class="md:col-span-2 mt-2 p-5 bg-gray-50/50 border border-gray-200/60 rounded-xl grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- MENARIK DATA PROVINSI --}}
                            @php
                                $provinces = \Illuminate\Support\Facades\DB::table('indonesia_provinces')->orderBy('name', 'asc')->get();
                            @endphp

                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-700">Provinsi <span
                                        class="text-red-500">*</span></label>
                                <select id="provinsi" name="provinsi" required
                                    class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-white outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm appearance-none cursor-pointer">
                                    <option value="">Pilih Provinsi...</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->code }}" {{ old('provinsi') == $province->code ? 'selected' : '' }}>{{ $province->name }}</option>
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

                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                    <h3
                        class="text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-lock text-[#cfa03f]"></i> Informasi Kontak & Kredensial
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nomor Telepon / WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="Contoh: 081234567890" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Email Aktif <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="Gunakan email yang bisa diakses (contoh@gmail.com)" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Kata Sandi (Password) <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Minimal 8 Karakter" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Konfirmasi Kata Sandi <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi Kata Sandi" required
                                class="w-full py-3 px-4 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white outline-none focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center mt-10 space-y-6">
                    <button type="submit"
                        class="w-full md:w-auto px-16 py-4 bg-[#cfa03f] hover:bg-[#b88e32] text-white rounded-full font-bold text-lg transition-all shadow-[0_4px_15px_rgba(207,160,63,0.25)] hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(207,160,63,0.35)] flex justify-center items-center gap-2">
                        DAFTAR SEKARANG <i class="fas fa-paper-plane"></i>
                    </button>

                    <p class="text-gray-500 text-sm text-center">Dengan mendaftar, Anda menyetujui kebijakan privasi
                        desa Buttu Sawe.</p>
                </div>

            </form>
        </div>
    </main>

    <footer
        class="bg-gradient-to-br from-[#11442b] to-[#2e7d32] text-white px-[5%] py-16 flex flex-col md:flex-row justify-between gap-8"
        id="kontak">
        <div class="flex-1 min-w-[300px]">
            <div class="text-white mb-4">
                <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo White" class="h-9">
            </div>
            <p class="max-w-md leading-relaxed opacity-90 mt-4">SINDESA adalah komitmen Desa Buttu Sawe untuk mewujudkan
                pemerintahan desa yang maju, transparan, dan melayani melalui transformasi digital.</p>
        </div>
        <div class="flex-1 min-w-[300px]">
            <h3 class="text-xl mb-6 text-[#cfa03f] border-b-2 border-[#cfa03f] inline-block pb-1 font-semibold">Hubungi
                Kami</h3>
            <div class="flex items-start mb-4">
                <i class="fas fa-map-marker-alt text-[#cfa03f] w-8 text-xl text-center mr-4 mt-1"></i>
                <p>Jl. Poros Bungi-Rajang, Buttu Sawe, Kec. Duampanua, Kabupaten Pinrang, Sulawesi Selatan</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fas fa-envelope text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>email: sindesa.buttusawe@gmail.com</p>
            </div>
            <div class="flex items-center mb-4">
                <i class="fab fa-whatsapp text-[#cfa03f] w-8 text-xl text-center mr-4"></i>
                <p>WA: -</p>
            </div>
        </div>
        <div class="flex-1 min-w-[300px]">
            <h3 class="text-xl mb-6 text-[#cfa03f] border-b-2 border-[#cfa03f] inline-block pb-1 font-semibold">Lokasi
                Kami</h3>
            <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/10 bg-white/5">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.0911448253682!2d119.54731177570577!3d-3.566494642371504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d945d6e318aa5a1%3A0xeb2eeba9b59e69c9!2sKANTOR%20DESA%20BUTTUSAWE!5e0!3m2!1sid!2sid!4v1780627282660!5m2!1sid!2sid"
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </footer>
    <div class="text-center py-6 bg-[#0d3320] text-white/70 text-sm">
        © 2026 SINDESA Desa Buttu Sawe. All rights reserved.
    </div>

    <script>
        // Logika Eksekusi reCAPTCHA v3 saat form dikirim
        document.getElementById('registerForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Tahan pengiriman form sementara
            const form = this;

            // Validasi manual foto KTP
            const fotoKtpInput = document.getElementById('foto_ktp');
            const ktpContainer = fotoKtpInput.closest('label');
            
            if (!fotoKtpInput.files || fotoKtpInput.files.length === 0) {
                // Beri efek visual border merah
                ktpContainer.classList.remove('border-[#cfa03f]');
                ktpContainer.classList.add('border-red-500', 'bg-red-50');
                
                // Scroll ke bagian KTP agar terlihat di belakang popup
                ktpContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Foto KTP Diperlukan',
                    text: 'Anda wajib mengunggah Foto KTP Asli sebelum mendaftar!',
                    confirmButtonColor: '#1a5e35',
                    returnFocus: false
                });
                
                return; // Batalkan submit
            }

            // Validasi ukuran file maks 2MB
            if (fotoKtpInput.files[0].size > 2 * 1024 * 1024) {
                ktpContainer.classList.remove('border-[#cfa03f]');
                ktpContainer.classList.add('border-red-500', 'bg-red-50');
                
                // Scroll ke bagian KTP agar terlihat
                ktpContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file Foto KTP maksimal adalah 2MB.',
                    confirmButtonColor: '#1a5e35',
                    returnFocus: false
                });
                return; // Batalkan submit
            }

            // Ubah tombol jadi loading saat submit berjalan
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';
            submitBtn.disabled = true;

            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: 'register' }).then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit(); // Lanjutkan pengiriman form
                }).catch(function(err) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Bermasalah',
                        text: 'Gagal memverifikasi keamanan (reCAPTCHA). Periksa koneksi internet Anda dan coba lagi.',
                        confirmButtonColor: '#1a5e35'
                    });
                });
            });
        });

        // Mengubah teks saat file KTP dipilih
        function showFileName(input) {
            const fileNameDisplay = document.getElementById('fileName');
            if (input.files && input.files.length > 0) {
                fileNameDisplay.innerHTML = `<span class="font-bold text-[#1a5e35]"><i class="fas fa-check-circle"></i> ${input.files[0].name}</span>`;
            } else {
                fileNameDisplay.innerHTML = `<span class="font-bold text-[#1a5e35]">Klik untuk mengunggah</span> file gambar`;
            }
        }

        // Script untuk Select Wilayah (Provinsi, Kota, Kecamatan, Desa)
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('provinsi');
            const citySelect = document.getElementById('kota');
            const districtSelect = document.getElementById('kecamatan');
            const villageSelect = document.getElementById('kelurahan_desa');

            function populateSelect(selectElement, data, placeholder) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    selectElement.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                });
                selectElement.disabled = false;
                selectElement.classList.remove('cursor-not-allowed');
                selectElement.classList.add('cursor-pointer');
            }

            function resetSelect(selectElement, placeholder) {
                selectElement.innerHTML = `<option value="">${placeholder}</option>`;
                selectElement.disabled = true;
                selectElement.classList.add('cursor-not-allowed');
                selectElement.classList.remove('cursor-pointer');
            }

            provinceSelect.addEventListener('change', function () {
                const provinceCode = this.value;
                resetSelect(citySelect, 'Memuat...');
                resetSelect(districtSelect, 'Pilih Kota Dahulu...');
                resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');

                if (provinceCode) {
                    fetch(`/data/cities?province_code=${provinceCode}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network error');
                            return response.json();
                        })
                        .then(data => populateSelect(citySelect, data, 'Pilih Kota/Kabupaten...'))
                        .catch(error => resetSelect(citySelect, 'Gagal memuat data. Periksa API!'));
                } else {
                    resetSelect(citySelect, 'Pilih Provinsi Dahulu...');
                }
            });

            citySelect.addEventListener('change', function () {
                const cityCode = this.value;
                resetSelect(districtSelect, 'Memuat...');
                resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');

                if (cityCode) {
                    fetch(`/data/districts?city_code=${cityCode}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network error');
                            return response.json();
                        })
                        .then(data => populateSelect(districtSelect, data, 'Pilih Kecamatan...'))
                        .catch(error => resetSelect(districtSelect, 'Gagal memuat data. Periksa API!'));
                } else {
                    resetSelect(districtSelect, 'Pilih Kota Dahulu...');
                }
            });

            districtSelect.addEventListener('change', function () {
                const districtCode = this.value;
                resetSelect(villageSelect, 'Memuat...');

                if (districtCode) {
                    fetch(`/data/villages?district_code=${districtCode}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network error');
                            return response.json();
                        })
                        .then(data => populateSelect(villageSelect, data, 'Pilih Kelurahan/Desa...'))
                        .catch(error => resetSelect(villageSelect, 'Gagal memuat data. Periksa API!'));
                } else {
                    resetSelect(villageSelect, 'Pilih Kecamatan Dahulu...');
                }
            });
        });
    </script>
</body>

</html>