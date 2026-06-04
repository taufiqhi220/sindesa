<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Review Keterangan Pindah - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    @php
        $dataTambahan = is_array($surat->data_tambahan) ? $surat->data_tambahan : (json_decode($surat->data_tambahan, true) ?? []);
    @endphp

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg">
                {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Operator' }}</h4>
                <p class="text-[10px] opacity-70">Petugas Verifikator</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
            <a href="{{ route('operator.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.dashboard') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center justify-between p-3 font-medium rounded-lg transition-all mb-2 {{ request()->routeIs('operator.verifikasi*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-inbox w-5 text-center"></i> Verifikasi Masuk
                </div>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('operator.menunggu-ttd') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.menunggu-ttd') ? 'bg-[#cfa03f] text-white' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>

            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.ditolak*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-times-circle w-5 text-center"></i> Surat Ditolak
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('operator.pengaturan-surat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-surat') ? 'bg-[#cfa03f] text-white' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-contract w-5 text-center"></i> Pengaturan Surat
            </a>

            <a href="{{ route('operator.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-akun') ? 'bg-[#cfa03f] text-white' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer font-medium">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('operator.verifikasi') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-[#1a5e35]">Review Detail Keterangan Pindah</h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">ID Pengajuan:
                    #{{ strtoupper(str_replace('_', '-', $surat->jenis_surat)) }}-{{ str_pad($surat->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-10">

            <!-- IDENTITAS PEMOHON -->
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-user"></i> Identitas Pemohon
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIK Pemohon</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['nik'] ?? $surat->user->nik ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor KK</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['no_kk'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['nama'] ?? $surat->user->name ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tempat Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['tempat_lahir'] ?? $surat->user->tempat_lahir ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            @php
                                $tanggalLahir = $dataTambahan['tanggal_lahir'] ?? $surat->user->tanggal_lahir ?? null;
                            @endphp
                            {{ $tanggalLahir ? \Carbon\Carbon::parse($tanggalLahir)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jenis Kelamin</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['jenis_kelamin'] ?? $surat->user->jenis_kelamin ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agama</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['agama'] ?? $surat->user->agama ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status Perkawinan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['status_perkawinan'] ?? $surat->user->status_perkawinan ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kewarganegaraan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['kewarganegaraan'] ?? $surat->user->kewarganegaraan ?? 'Indonesia' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pekerjaan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['pekerjaan'] ?? $surat->user->pekerjaan ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pendidikan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['pendidikan'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL KEPINDAHAN -->
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-lg font-bold text-[#cfa03f] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-map-marked-alt"></i> Detail Rute Kepindahan
                </h3>

                <div class="bg-red-50 border border-red-100 rounded-2xl p-6 mb-6">
                    <label class="block text-sm font-bold text-red-700 mb-4"><i class="fas fa-sign-out-alt"></i> Alamat Asal</label>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dusun / Jalan</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_asal_dusun'] ?? ($dataTambahan['alamat_asal']['dusun'] ?? '-') }}
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">RT</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_asal_rt'] ?? ($dataTambahan['alamat_asal']['rt'] ?? '-') }}
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">RW</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_asal_rw'] ?? ($dataTambahan['alamat_asal']['rw'] ?? '-') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                    <label class="block text-sm font-bold text-blue-700 mb-4"><i class="fas fa-sign-in-alt"></i> Alamat Tujuan Pindah</label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Jalan / Kampung / Dusun</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_jalan'] ?? ($dataTambahan['alamat_tujuan']['jalan'] ?? '-') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">RT</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_rt'] ?? ($dataTambahan['alamat_tujuan']['rt'] ?? '-') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">RW</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_rw'] ?? ($dataTambahan['alamat_tujuan']['rw'] ?? '-') }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Desa / Kelurahan</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_desa'] ?? ($dataTambahan['alamat_tujuan']['desa'] ?? '-') }}
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Kecamatan</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_kecamatan'] ?? ($dataTambahan['alamat_tujuan']['kecamatan'] ?? '-') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Kab / Kota</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_kabupaten'] ?? ($dataTambahan['alamat_tujuan']['kabupaten'] ?? '-') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Provinsi</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_provinsi'] ?? ($dataTambahan['alamat_tujuan']['provinsi'] ?? '-') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Kode Pos</label>
                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-800 font-semibold">
                                {{ $dataTambahan['alamat_tujuan_kodepos'] ?? ($dataTambahan['alamat_tujuan']['kode_pos'] ?? '-') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KETERANGAN LAINNYA & PENGIKUT -->
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-users"></i> Keterangan Lainnya & Pengikut
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alasan Pindah</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['alasan_pindah'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Rencana Tanggal Pindah</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ !empty($dataTambahan['tanggal_pindah']) ? \Carbon\Carbon::parse($dataTambahan['tanggal_pindah'])->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 mt-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Daftar Anggota Keluarga yang Ikut Pindah</label>
                        @php
                            // Ekstrak data pengikut dari JSON bertingkat (anggota_keluarga) atau gunakan versi sejajar jika versi lama
                            $oldAnggota = $dataTambahan['anggota_keluarga'] ?? [];
                            $p_nama = $dataTambahan['pengikut_nama'] ?? array_column($oldAnggota, 'nama');
                            $p_nik = $dataTambahan['pengikut_nik'] ?? array_column($oldAnggota, 'nik');
                            $p_jk = $dataTambahan['pengikut_jk'] ?? array_column($oldAnggota, 'jenis_kelamin');
                            $p_tgl = $dataTambahan['pengikut_tgl_lahir'] ?? array_column($oldAnggota, 'tanggal_lahir');
                            $p_status = $dataTambahan['pengikut_status'] ?? array_column($oldAnggota, 'status_perkawinan');
                            $p_ket = $dataTambahan['pengikut_ket'] ?? array_column($oldAnggota, 'keterangan');
                            
                            $countPengikut = is_array($p_nama) ? count($p_nama) : 0;
                        @endphp

                        @if($countPengikut > 0)
                            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                                <table class="w-full text-left text-sm whitespace-nowrap">
                                    <thead class="bg-gray-100 text-gray-500 uppercase tracking-wider text-xs">
                                        <tr>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200 w-10 text-center">No</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">Nama Lengkap</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">NIK</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">L/P</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">Tanggal Lahir</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">Status</th>
                                            <th class="px-4 py-3 font-bold border-b border-gray-200">Hubungan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @for($i = 0; $i < $countPengikut; $i++)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-semibold text-gray-500 text-center">{{ $i + 1 }}</td>
                                                <td class="px-4 py-3 font-bold text-gray-800">{{ $p_nama[$i] ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $p_nik[$i] ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-600 font-bold">{{ substr($p_jk[$i] ?? '-', 0, 1) }}</td>
                                                <td class="px-4 py-3 text-gray-600">{{ !empty($p_tgl[$i]) ? \Carbon\Carbon::parse($p_tgl[$i])->format('d/m/Y') : '-' }}</td>
                                                <td class="px-4 py-3 text-gray-600">{{ $p_status[$i] ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-600"><span class="bg-gray-100 px-2 py-1 rounded-md text-xs font-semibold">{{ $p_ket[$i] ?? '-' }}</span></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="px-4 py-6 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center">
                                <p class="text-sm text-gray-500 italic">Tidak ada anggota keluarga yang ikut pindah (Pindah Sendiri).</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- DOKUMEN PENDUKUNG -->
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-paperclip"></i> Dokumen Pendukung
                </h3>
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">KTP Pemohon</label>
                            <span class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_ktp']) ? 'Berkas Tersedia' : 'Berkas Tidak Ditemukan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_ktp']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_ktp']) }}" target="_blank" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-red-500 italic font-semibold">Tidak ada file</span>
                        @endif
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kartu Keluarga (KK)</label>
                            <span class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_kk']) ? 'Berkas Tersedia' : 'Berkas Tidak Ditemukan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_kk']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_kk']) }}" target="_blank" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-red-500 italic font-semibold">Tidak ada file</span>
                        @endif
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Dokumen Pendukung Lain (Opsional)</label>
                            <span class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_lain']) ? 'Berkas Tersedia' : 'Berkas Tidak Dilampirkan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_lain']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_lain']) }}" target="_blank" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-gray-500 italic font-semibold">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- FORM VERIFIKASI -->
            <form action="{{ route('operator.verifikasi.update', $surat->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-100 shadow-inner mt-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Surat Resmi</label>
                            <input type="text" name="nomor_surat" id="nomor_surat"
                                placeholder="Contoh: 474.1/025/DBS/IV/2026"
                                value="{{ old('nomor_surat', $surat->nomor_surat) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm font-mono uppercase">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Verifikator / Alasan Penolakan</label>
                            <textarea name="pesan_penolakan" id="pesan_penolakan"
                                placeholder="Wajib diisi jika permohonan ditolak..." rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all text-sm">{{ old('pesan_penolakan', $surat->pesan_penolakan) }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-4">
                        <button type="submit" name="action" value="tolak" onclick="return validasi('tolak')"
                            class="px-8 py-3 bg-white border-2 border-red-500 text-red-500 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm uppercase text-xs tracking-wider">
                            <i class="fas fa-times-circle text-sm"></i> Tolak Pengajuan
                        </button>

                        <button type="submit" name="action" value="setujui" onclick="return validasi('setujui')"
                            class="px-8 py-3 bg-[#1a5e35] text-white rounded-xl font-bold hover:bg-[#2e7d32] transition-all flex items-center justify-center gap-2 shadow-lg uppercase text-xs tracking-wider">
                            <i class="fas fa-check-circle text-sm"></i> Verifikasi & Teruskan
                        </button>
                    </div>
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
        function validasi(type) {
            const nomor = document.getElementById('nomor_surat');
            const pesan = document.getElementById('pesan_penolakan');

            if (type === 'setujui') {
                if (nomor.value.trim() === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nomor Surat Kosong',
                        text: 'Silakan isi nomor surat terlebih dahulu untuk melanjutkan verifikasi.',
                        confirmButtonColor: '#1a5e35', 
                    });
                    nomor.focus();
                    return false;
                }
            } else if (type === 'tolak') {
                if (pesan.value.trim() === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Alasan Belum Diisi',
                        text: 'Wajib memberikan alasan penolakan agar warga dapat memahami kekurangan berkasnya.',
                        confirmButtonColor: '#d33', 
                    });
                    pesan.focus();
                    return false;
                }
            }
            return true;
        }
    </script>
</body>

</html>