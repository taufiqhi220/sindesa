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

        <div class="max-w-3xl mx-auto text-center mb-10 mt-10">
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Edit Pengajuan Keterangan Pindah</h2>
            <p class="text-gray-500">Perbaiki data atau alamat tujuan pindah pada pengajuan sebelumnya.</p>
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
                        <div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex shrink-0 items-center justify-center text-lg">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-800 text-base mb-1">Surat Ditolak / Perlu Revisi</h4>
                            <p class="text-red-700 text-sm font-medium">Catatan Admin: <span class="font-normal">{{ $surat->pesan_penolakan }}</span></p>
                            <p class="text-xs text-red-500 mt-2 italic">*Silakan perbaiki data atau dokumen di bawah ini sesuai catatan, lalu tekan "Simpan Perubahan".</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('warga.form.pindah.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan; @endphp

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Identitas Pemohon</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">NIK Pemohon</label>
                        <input type="number" name="nik" value="{{ old('nik', $data['nik'] ?? Auth::user()->nik) }}" placeholder="16 Digit NIK" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Nomor Kartu Keluarga (KK)</label>
                        <input type="number" name="no_kk" value="{{ old('no_kk', $data['no_kk'] ?? Auth::user()->no_kk) }}" placeholder="16 Digit Nomor KK" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $data['nama'] ?? Auth::user()->name) }}" placeholder="Nama Lengkap sesuai Dokumen" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $data['tempat_lahir'] ?? Auth::user()->tempat_lahir) }}" placeholder="Kota/Kabupaten" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $data['tanggal_lahir'] ?? Auth::user()->tanggal_lahir) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all" required>
                            @php $jk = old('jenis_kelamin', $data['jenis_kelamin'] ?? Auth::user()->jenis_kelamin); @endphp
                            <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ strcasecmp($jk, 'Laki-laki') == 0 || strcasecmp($jk, 'Laki-Laki') == 0 ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ strcasecmp($jk, 'Perempuan') == 0 ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Agama</label>
                        <select name="agama" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all" required>
                            @php $agm = old('agama', $data['agama'] ?? Auth::user()->agama); @endphp
                            <option value="" disabled>-- Pilih Agama --</option>
                            <option value="Islam" {{ $agm == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ $agm == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
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
                        <select name="status_perkawinan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm appearance-none transition-all" required>
                            @php $sp = old('status_perkawinan', $data['status_perkawinan'] ?? Auth::user()->status_perkawinan); @endphp
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Belum Kawin" {{ $sp == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="Kawin" {{ $sp == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                            <option value="Cerai Hidup" {{ $sp == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="Cerai Mati" {{ $sp == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $data['pekerjaan'] ?? Auth::user()->pekerjaan) }}" placeholder="Contoh: Petani, Wiraswasta" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-800">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan', $data['pendidikan'] ?? '') }}" placeholder="Contoh: Tamat SD Sederajat, SMA, S1" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Keterangan Kepindahan</h3>

                <div class="mb-6 bg-gray-50 p-5 rounded-2xl border border-gray-200">
                    <label class="block mb-2 text-sm font-medium text-[#1a5e35]">Alamat Asal (Desa Buttu Sawe)</label>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <input type="text" name="alamat_asal_dusun" value="{{ old('alamat_asal_dusun', $data['alamat_asal']['dusun'] ?? '') }}" placeholder="Nama Dusun" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm" required>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="alamat_asal_rt" value="{{ old('alamat_asal_rt', $data['alamat_asal']['rt'] ?? '') }}" placeholder="RT" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm" required>
                        </div>
                        <div class="w-1/4">
                            <input type="number" name="alamat_asal_rw" value="{{ old('alamat_asal_rw', $data['alamat_asal']['rw'] ?? '') }}" placeholder="RW" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 bg-white transition-all outline-none text-sm" required>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-[#cfa03f]">Alamat Tujuan Pindah</label>
                    <input type="text" name="alamat_tujuan_jalan" value="{{ old('alamat_tujuan_jalan', $data['alamat_tujuan']['jalan'] ?? '') }}" placeholder="Nama Jalan / Dusun / Kampung" class="w-full px-4 py-3 mb-4 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <input type="number" name="alamat_tujuan_rt" value="{{ old('alamat_tujuan_rt', $data['alamat_tujuan']['rt'] ?? '') }}" placeholder="RT" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                        <input type="number" name="alamat_tujuan_rw" value="{{ old('alamat_tujuan_rw', $data['alamat_tujuan']['rw'] ?? '') }}" placeholder="RW" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                        <input type="text" name="alamat_tujuan_desa" value="{{ old('alamat_tujuan_desa', $data['alamat_tujuan']['desa'] ?? '') }}" placeholder="Desa / Kelurahan" class="w-full px-4 py-3 border border-gray-300 rounded-xl md:col-span-2 focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <input type="text" name="alamat_tujuan_kecamatan" value="{{ old('alamat_tujuan_kecamatan', $data['alamat_tujuan']['kecamatan'] ?? '') }}" placeholder="Kecamatan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                        <input type="text" name="alamat_tujuan_kabupaten" value="{{ old('alamat_tujuan_kabupaten', $data['alamat_tujuan']['kabupaten'] ?? '') }}" placeholder="Kab / Kota" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                        <input type="text" name="alamat_tujuan_provinsi" value="{{ old('alamat_tujuan_provinsi', $data['alamat_tujuan']['provinsi'] ?? '') }}" placeholder="Provinsi" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>

                    <div class="w-full md:w-1/3">
                        <input type="number" name="alamat_tujuan_kodepos" value="{{ old('alamat_tujuan_kodepos', $data['alamat_tujuan']['kode_pos'] ?? '') }}" placeholder="Kode Pos" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Alasan Pindah</label>
                        <input type="text" name="alasan_pindah" value="{{ old('alasan_pindah', $data['alasan_pindah'] ?? '') }}" placeholder="Contoh: Mengikuti Pekerjaan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-800">Rencana Tanggal Pindah</label>
                        <input type="date" name="tanggal_pindah" value="{{ old('tanggal_pindah', $data['tanggal_pindah'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none text-sm transition-all" required>
                    </div>
                </div>

                <!-- FORM PENGIKUT DINAMIS (PRE-POPULATE DATA LAMA) -->
                @php
                    $oldAnggota = old('pengikut_nama') ? [] : ($data['anggota_keluarga'] ?? []);
                    $p_nama = old('pengikut_nama', array_column($oldAnggota, 'nama'));
                    $p_nik = old('pengikut_nik', array_column($oldAnggota, 'nik'));
                    $p_jk = old('pengikut_jk', array_column($oldAnggota, 'jenis_kelamin'));
                    $p_tgl = old('pengikut_tgl_lahir', array_column($oldAnggota, 'tanggal_lahir'));
                    $p_status = old('pengikut_status', array_column($oldAnggota, 'status_perkawinan'));
                    $p_ket = old('pengikut_ket', array_column($oldAnggota, 'keterangan'));
                    $countPengikut = is_array($p_nama) ? count($p_nama) : 0;
                @endphp

                <div class="mb-10">
                    <div class="flex justify-between items-center mb-4 border-b-2 border-gray-100 pb-2">
                        <label class="text-lg font-semibold text-[#1a5e35]">Anggota Keluarga yang Ikut Pindah</label>
                        <button type="button" onclick="addPengikut()" class="bg-[#1a5e35] hover:bg-[#11442b] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                            <i class="fas fa-user-plus"></i> Tambah Anggota
                        </button>
                    </div>
                    
                    <div id="pengikut-container" class="space-y-4">
                        @for($i = 0; $i < $countPengikut; $i++)
                            <div class="p-5 border border-gray-200 rounded-xl bg-gray-50 relative" id="pengikut-{{ $i }}">
                                <button type="button" onclick="removePengikut({{ $i }})" class="absolute top-3 right-3 text-red-500 hover:text-red-700 bg-red-100 hover:bg-red-200 w-8 h-8 flex justify-center items-center rounded-lg transition-colors" title="Hapus Anggota">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider border-b border-gray-200 pb-2">Data Anggota #{{ $i+1 }}</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="pengikut_nama[]" value="{{ $p_nama[$i] }}" placeholder="Sesuai KTP/KK" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">NIK / No. KTP</label>
                                        <input type="number" name="pengikut_nik[]" value="{{ $p_nik[$i] }}" placeholder="16 Digit NIK" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">Jenis Kelamin</label>
                                        <select name="pengikut_jk[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                            <option value="Laki-laki" {{ ($p_jk[$i] ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ ($p_jk[$i] ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">Tanggal Lahir</label>
                                        <input type="date" name="pengikut_tgl_lahir[]" value="{{ $p_tgl[$i] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">Status Perkawinan</label>
                                        <select name="pengikut_status[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                            <option value="Belum Kawin" {{ ($p_status[$i] ?? '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                            <option value="Kawin" {{ ($p_status[$i] ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                            <option value="Cerai Hidup" {{ ($p_status[$i] ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                            <option value="Cerai Mati" {{ ($p_status[$i] ?? '') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-700">Keterangan (Hub)</label>
                                        <input type="text" name="pengikut_ket[]" value="{{ $p_ket[$i] }}" placeholder="Contoh: Istri, Anak" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:border-[#1a5e35]" required>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    
                    <p id="empty-pengikut-msg" class="text-sm text-gray-500 italic text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-300" style="{{ $countPengikut > 0 ? 'display: none;' : '' }}">
                        Tidak ada anggota keluarga tambahan. Klik "Tambah Anggota" jika pindah bersama keluarga.
                    </p>
                </div>

                <h3 class="text-lg font-semibold text-[#1a5e35] border-b-2 border-gray-100 pb-2 mb-6">Upload Berkas Pendukung <span class="text-sm font-normal text-gray-500">(Abaikan jika tidak diubah)</span></h3>

                <div class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload KTP Pemohon</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($data['file_ktp']))
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-lg"></i></div>
                                <div><p class="text-sm font-medium text-gray-800">File Saat Ini</p><p class="text-xs text-gray-500">Telah tersimpan</p></div>
                            </div>
                            <a href="{{ asset('storage/' . $data['file_ktp']) }}" target="_blank" class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors"><i class="fas fa-eye mr-1"></i> Lihat</a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-ktp" name="file_ktp" class="hidden" onchange="updateFileName(this, 'name-ktp')">
                        <label for="file-ktp" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm"><i class="fas fa-cloud-upload-alt"></i> Ganti File</label>
                        <span id="name-ktp" class="text-sm text-gray-500 italic truncate">Tidak ada file baru dipilih</span>
                    </div>
                </div>

                <div class="mb-6 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Kartu Keluarga (KK)</label>
                    <span class="block text-xs text-gray-500 mb-4">Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($data['file_kk']))
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-lg"></i></div>
                                <div><p class="text-sm font-medium text-gray-800">File Saat Ini</p><p class="text-xs text-gray-500">Telah tersimpan</p></div>
                            </div>
                            <a href="{{ asset('storage/' . $data['file_kk']) }}" target="_blank" class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors"><i class="fas fa-eye mr-1"></i> Lihat</a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-kk" name="file_kk" class="hidden" onchange="updateFileName(this, 'name-kk')">
                        <label for="file-kk" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm"><i class="fas fa-cloud-upload-alt"></i> Ganti File</label>
                        <span id="name-kk" class="text-sm text-gray-500 italic truncate">Tidak ada file baru dipilih</span>
                    </div>
                </div>

                <div class="mb-8 border-2 border-dashed border-gray-300 p-6 rounded-2xl bg-gray-50 hover:border-[#1a5e35] transition-colors">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Upload Dokumen Pendukung Lainnya (Opsional)</label>
                    <span class="block text-xs text-gray-500 mb-4">Misal SKCK Lama dll. Maksimal 5MB. Format: PDF, JPG, PNG.</span>

                    @if(isset($data['file_lain']) && $data['file_lain'])
                        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-lg"></i></div>
                                <div><p class="text-sm font-medium text-gray-800">File Saat Ini</p><p class="text-xs text-gray-500">Telah tersimpan</p></div>
                            </div>
                            <a href="{{ asset('storage/' . $data['file_lain']) }}" target="_blank" class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium transition-colors"><i class="fas fa-eye mr-1"></i> Lihat</a>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <input type="file" id="file-lain" name="file_lain" class="hidden" onchange="updateFileName(this, 'name-lain')">
                        <label for="file-lain" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full cursor-pointer font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm"><i class="fas fa-cloud-upload-alt"></i> Ganti File</label>
                        <span id="name-lain" class="text-sm text-gray-500 italic truncate">Tidak ada file baru dipilih</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 mt-10 mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <input type="checkbox" id="consent" oninvalid="this.setCustomValidity('Harap centang kotak persetujuan ini sebelum melanjutkan.')" oninput="this.setCustomValidity('')" name="consent" class="mt-1 w-4 h-4 text-[#1a5e35] bg-white border-gray-300 rounded focus:ring-[#1a5e35]" required>
                    <label for="consent" class="text-sm text-gray-700">Dengan ini saya menyatakan data dan alamat tujuan kepindahan yang diperbarui adalah benar, dan siap diproses ulang.</label>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="bg-[#cfa03f] hover:bg-[#b88e32] text-white px-8 py-3.5 rounded-xl font-semibold transition-all inline-flex items-center justify-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm w-full sm:w-auto"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ route('warga.riwayat') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3.5 rounded-xl font-semibold transition-all inline-flex items-center justify-center gap-2 text-sm w-full sm:w-auto">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Logika Javascript untuk Add/Remove Form Pengikut
        let pengikutIndex = {{ $countPengikut }};

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
                <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider border-b border-gray-200 pb-2">Data Anggota Baru</h4>
                
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