<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Pengaturan Sistem - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input:checked+.slider {
            background-color: #ef4444;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">
        {{-- Header Mobile --}}
        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1a5e35]">Pengaturan Sistem</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola identitas desa, preferensi aplikasi, dan profil administrator
                secara menyeluruh dari panel ini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">

                {{-- FORM PROFIL ADMIN --}}
                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
                    <div class="border-b-2 border-gray-50 pb-4 mb-6">
                        <div class="flex items-center gap-3 text-lg font-bold text-[#cfa03f] mb-1">
                            <i class="fas fa-user-shield"></i> Profil Administrator
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Perbarui informasi kredensial Anda. Data ini
                            digunakan untuk keperluan masuk (login) ke dalam sistem panel SINDESA.</p>
                    </div>

                    <form action="{{ route('admin.pengaturan.profil') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="flex flex-col md:flex-row gap-8">

                            {{-- Foto Profil Centered --}}
                            <div class="w-full md:w-1/3 flex flex-col items-center justify-start pt-2">
                                <div
                                    class="w-32 h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden mb-4 shadow-inner">
                                    @if(Auth::user()->foto_profil)
                                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-4xl text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="w-full flex flex-col items-center text-center">
                                    <input type="file" name="foto_profil" accept="image/*"
                                        class="block w-full max-w-[200px] text-xs text-gray-500 file:mr-0 file:mb-2 file:block file:w-full file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                    <p class="text-[10px] text-gray-400 mt-2">Format JPG/PNG, rasio 1:1.</p>
                                </div>
                            </div>

                            <div class="w-full md:w-2/3 space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#1a5e35]/20 focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email /
                                        Username</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#1a5e35]/20 focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password Baru
                                        <span class="normal-case font-normal text-gray-400">(Biarkan kosong jika tidak
                                            ingin mengubah)</span></label>
                                    <input type="password" name="password" placeholder="••••••••"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-[#1a5e35]/20 focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="bg-[#cfa03f] text-white px-6 py-3 rounded-xl font-bold hover:bg-yellow-600 transition-all text-sm shadow-md flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                {{-- FORM IDENTITAS DESA --}}
                <div
                    class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8">
                    <div class="border-b-2 border-gray-50 pb-4 mb-6">
                        <div class="flex items-center gap-3 text-lg font-bold text-[#1a5e35] mb-1">
                            <i class="fas fa-university"></i> Profil & Identitas Desa
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Informasi yang Anda masukkan di sini akan
                            secara otomatis menjadi identitas utama pada kop cetakan surat (PDF) dan antarmuka aplikasi
                            warga.</p>
                    </div>

                    <form action="{{ route('admin.pengaturan.desa') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Desa</label>
                                <input type="text" name="nama_desa" value="{{ $pengaturan->nama_desa ?? 'Buttu Sawe' }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kode Desa /
                                    Pos</label>
                                <input type="text" name="kode_desa" value="91254"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kecamatan</label>
                                <input type="text" name="kecamatan"
                                    value="{{ str_replace('KECAMATAN ', '', $pengaturan->header_2 ?? 'Duampanua') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kabupaten /
                                    Kota</label>
                                <input type="text" name="kabupaten"
                                    value="{{ str_replace('PEMERINTAH KABUPATEN ', '', $pengaturan->header_1 ?? 'Pinrang') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1a5e35] outline-none text-sm bg-gray-50">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap Kantor
                                Desa</label>
                            <textarea name="alamat" rows="2"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#1a5e35] outline-none text-sm bg-gray-50">{{ $pengaturan->alamat ?? 'Jl. Poros Pinrang - Polman Km. 25, Kode Pos 91254' }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Alamat ini akan muncul di baris paling bawah pada
                                kop surat.</p>
                        </div>

                        {{-- Menampilkan Preview Logo Instansi --}}
                        <div class="mb-8 border border-gray-100 p-5 rounded-xl bg-gray-50/50">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-4">Logo Instansi /
                                Pemkab</label>
                            <div class="flex items-center gap-5">
                                <div
                                    class="w-20 h-20 bg-white border border-gray-200 rounded-xl flex items-center justify-center overflow-hidden shrink-0 shadow-sm p-2">
                                    @if(!empty($pengaturan->logo_path))
                                        <img src="{{ asset('storage/' . $pengaturan->logo_path) }}" alt="Logo Desa"
                                            class="w-full h-full object-contain">
                                    @else
                                        <i class="fas fa-image text-3xl text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/*"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#1a5e35] file:text-white hover:file:bg-[#2e7d32] cursor-pointer transition-all">
                                    <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">
                                        Logo ini dicetak di sisi kiri atas pada dokumen surat.<br>
                                        Kosongkan jika tidak ada perubahan. Format: PNG Transparan disarankan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="bg-[#1a5e35] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#2e7d32] shadow-md transition-all text-sm flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Identitas Desa
                        </button>
                    </form>
                </div>
            </div>

            {{-- SIDEBAR PREFERENSI & BACKUP --}}
            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <div class="text-lg font-bold text-[#cfa03f] mb-2 border-b-2 border-gray-50 pb-3">
                        <i class="fas fa-sliders-h mr-2"></i> Preferensi Sistem
                    </div>

                    <div class="mt-4 mb-5 flex flex-col bg-red-50 p-4 rounded-xl border border-red-100">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-red-800 text-sm">Mode Perbaikan (Maintenance)</h4>
                            <label class="switch shrink-0 ml-2">
                                <input type="checkbox" onchange="toggleMaintenance(this.checked)" {{ $isMaintenance ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p class="text-[11px] text-red-600 leading-relaxed">Aktifkan untuk memblokir sementara akses
                            warga dan menampilkan pesan pemeliharaan. Hanya Administrator yang dapat menggunakan sistem.
                        </p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Aplikasi</label>
                        <input type="text" value="SINDESA"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none text-sm bg-gray-50 cursor-not-allowed text-gray-500"
                            readonly>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6">
                    <div class="text-lg font-bold text-blue-600 mb-2 border-b-2 border-gray-50 pb-3">
                        <i class="fas fa-database mr-2"></i> Pencadangan Data
                    </div>
                    <p class="text-xs text-gray-500 mt-4 mb-5 leading-relaxed">Unduh seluruh rekaman database (data
                        warga, operator, kades, log aktivitas, dan antrean surat) dalam format <b>.sql</b> secara
                        berkala untuk meminimalisasi risiko kehilangan data sistem.</p>
                    <a href="{{ route('admin.pengaturan.backup') }}"
                        class="w-full bg-blue-50 text-blue-600 border border-blue-200 px-4 py-3 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm text-sm">
                        <i class="fas fa-download"></i> Unduh Database (.sql)
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script type="module">
        window.toggleSidebar = () => {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        };

        window.toggleMaintenance = (isActive) => {
            fetch("{{ route('admin.pengaturan.maintenance') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ is_maintenance: isActive })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', isActive ? 'Mode perbaikan sistem diaktifkan!' : 'Mode perbaikan dimatikan!', 'success');
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan sistem.', 'error');
                }
            }).catch(err => {
                Swal.fire('Gagal!', 'Tidak dapat terhubung ke server.', 'error');
            });
        };
    </script>
    @include('partials.sweetalert')
</body>

</html>