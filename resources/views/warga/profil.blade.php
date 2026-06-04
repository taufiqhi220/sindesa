<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Profil Saya - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Menyembunyikan scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-8 lg:ml-0 overflow-x-hidden">
        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Profil</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola data diri dan keamanan akun Anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl mb-6 text-emerald-700 shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 text-red-700 shadow-sm">
                <div class="flex items-center mb-2 font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Gagal
                    Menyimpan:</div>
                <ul class="list-disc list-inside text-sm ml-2">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warga.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
                <div class="relative group">
                    <div class="relative inline-block group shrink-0">
                        <div
                            class="w-28 h-28 bg-gradient-to-br from-[#cfa03f] to-[#b88e32] rounded-full flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-md overflow-hidden relative z-10">
                            @if(Auth::user()->foto_profil)
                                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                                    class="w-full h-full object-cover" id="preview-avatar">
                            @else
                                <span id="initial-avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                <img src="" class="w-full h-full object-cover hidden" id="preview-avatar">
                            @endif
                        </div>

                        <label for="foto_upload"
                            class="absolute bottom-0 right-0 bg-[#1a5e35] text-white w-10 h-10 flex items-center justify-center rounded-full cursor-pointer hover:bg-[#2e7d32] hover:scale-110 transition-all shadow-[0_3px_8px_rgba(0,0,0,0.2)] border-2 border-white z-20"
                            title="Ganti Foto Profil">
                            <i class="fas fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="foto_upload" name="foto_profil" class="hidden" accept="image/*"
                            onchange="previewImage(event)">
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    <div class="mt-3 flex items-center justify-center md:justify-start gap-2">
                        <span
                            class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-200">Warga
                            Desa</span>
                        <span
                            class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium border border-gray-200">Terdaftar
                            {{ Auth::user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-[#1a5e35] border-b pb-3 mb-6"><i
                        class="fas fa-id-card text-[#cfa03f] mr-2"></i> Data Kependudukan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2 text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">NIK</label>
                        <input type="number" name="nik" value="{{ old('nik', $user->nik) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">No. KK</label>
                        <input type="number" name="no_kk" value="{{ old('no_kk', $user->no_kk) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-gray-600"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            <option value="Laki-Laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Agama</label>
                        <select name="agama"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            <option value="Islam" {{ old('agama', $user->agama) == 'Islam' ? 'selected' : '' }}>Islam
                            </option>
                            <option value="Kristen" {{ old('agama', $user->agama) == 'Kristen' ? 'selected' : '' }}>
                                Kristen</option>
                            <option value="Katolik" {{ old('agama', $user->agama) == 'Katolik' ? 'selected' : '' }}>
                                Katolik</option>
                            <option value="Hindu" {{ old('agama', $user->agama) == 'Hindu' ? 'selected' : '' }}>Hindu
                            </option>
                            <option value="Buddha" {{ old('agama', $user->agama) == 'Buddha' ? 'selected' : '' }}>Buddha
                            </option>
                            <option value="Konghucu" {{ old('agama', $user->agama) == 'Konghucu' ? 'selected' : '' }}>
                                Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Status Perkawinan</label>
                        <select name="status_perkawinan"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            <option value="Belum Kawin" {{ old('status_perkawinan', $user->status_perkawinan) == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="Kawin" {{ old('status_perkawinan', $user->status_perkawinan) == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                            <option value="Cerai Hidup" {{ old('status_perkawinan', $user->status_perkawinan) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="Cerai Mati" {{ old('status_perkawinan', $user->status_perkawinan) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2 text-gray-700">Kewarganegaraan</label>
                        <select name="kewarganegaraan"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            <option value="WNI" {{ old('kewarganegaraan', $user->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('kewarganegaraan', $user->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-[#1a5e35] border-b pb-3 mb-6"><i
                        class="fas fa-map-marker-alt text-[#cfa03f] mr-2"></i> Alamat Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2 text-gray-700">Alamat Lengkap</label>
                        <input type="text" name="alamat_lengkap"
                            value="{{ old('alamat_lengkap', $user->alamat_lengkap) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2 text-gray-700">RT / RW</label>
                        <input type="text" name="rt_rw" value="{{ old('rt_rw', $user->rt_rw) }}"
                            class="w-full md:w-1/2 p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Provinsi</label>
                        <select id="provinsi" name="provinsi"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->code }}" {{ old('provinsi', $user->provinsi) == $province->code ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kota / Kabupaten</label>
                        <select id="kota" name="kota"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            @if($cities)
                                @foreach($cities as $city) <option value="{{ $city->code }}" {{ old('kota', $user->kota) == $city->code ? 'selected' : '' }}>{{ $city->name }}</option> @endforeach
                            @else <option value="">Pilih Provinsi Dahulu</option> @endif
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kecamatan</label>
                        <select id="kecamatan" name="kecamatan"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            @if($districts)
                                @foreach($districts as $district) <option value="{{ $district->code }}" {{ old('kecamatan', $user->kecamatan) == $district->code ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            @else <option value="">Pilih Kota Dahulu</option> @endif
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Kelurahan / Desa</label>
                        <select id="kelurahan_desa" name="kelurahan_desa"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                            @if($villages)
                                @foreach($villages as $village) <option value="{{ $village->code }}" {{ old('kelurahan_desa', $user->kelurahan_desa) == $village->code ? 'selected' : '' }}>
                                    {{ $village->name }}
                                </option> @endforeach
                            @else <option value="">Pilih Kecamatan Dahulu</option> @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-[#1a5e35] border-b pb-3 mb-6"><i
                        class="fas fa-lock text-[#cfa03f] mr-2"></i> Info Kontak & Keamanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">No. Telepon / WA</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Email Aktif</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all"
                            required>
                    </div>
                    <div class="md:col-span-2 mt-4 bg-amber-50 p-4 rounded-xl border border-amber-100">
                        <p class="text-xs text-amber-800 font-medium"><i class="fas fa-info-circle mr-1"></i> Kosongkan
                            kolom password di bawah ini jika Anda tidak ingin mengubahnya.</p>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Kosongkan jika tidak diubah"
                            class="w-full p-3 border border-gray-200 rounded-xl bg-gray-50 outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pb-10">
                <button type="submit"
                    class="px-10 py-4 bg-[#cfa03f] hover:bg-[#b88e32] text-white rounded-xl font-bold text-lg transition-all shadow-[0_4px_15px_rgba(207,160,63,0.25)] hover:-translate-y-1">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </main>

    <script>
        function toggleMenu(menuId, element) {
            const submenu = document.getElementById(menuId);
            const icon = element.querySelector('.fa-chevron-down');
            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Preview Image Script
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview-avatar');
                const initial = document.getElementById('initial-avatar');
                preview.src = reader.result;
                preview.classList.remove('hidden');
                if (initial) initial.classList.add('hidden');
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Script Dropdown Wilayah Laravolt
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
            }

            provinceSelect.addEventListener('change', function () {
                const provinceCode = this.value;
                citySelect.innerHTML = '<option value="">Memuat...</option>';
                districtSelect.innerHTML = '<option value="">Pilih Kota Dahulu...</option>';
                villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu...</option>';

                if (provinceCode) {
                    fetch(`/data/cities?province_code=${provinceCode}`)
                        .then(response => response.json())
                        .then(data => populateSelect(citySelect, data, 'Pilih Kota/Kabupaten...'));
                } else {
                    citySelect.innerHTML = '<option value="">Pilih Provinsi Dahulu...</option>';
                }
            });

            citySelect.addEventListener('change', function () {
                const cityCode = this.value;
                districtSelect.innerHTML = '<option value="">Memuat...</option>';
                villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu...</option>';

                if (cityCode) {
                    fetch(`/data/districts?city_code=${cityCode}`)
                        .then(response => response.json())
                        .then(data => populateSelect(districtSelect, data, 'Pilih Kecamatan...'));
                } else {
                    districtSelect.innerHTML = '<option value="">Pilih Kota Dahulu...</option>';
                }
            });

            districtSelect.addEventListener('change', function () {
                const districtCode = this.value;
                villageSelect.innerHTML = '<option value="">Memuat...</option>';

                if (districtCode) {
                    fetch(`/data/villages?district_code=${districtCode}`)
                        .then(response => response.json())
                        .then(data => populateSelect(villageSelect, data, 'Pilih Kelurahan/Desa...'));
                } else {
                    villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu...</option>';
                }
            });
        });
    </script>
    @include('partials.sweetalert')
</body>

</html>