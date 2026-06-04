<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Review Pengantar KK - SINDESA</title>
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

        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('operator.verifikasi') }}"
                class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-[#1a5e35]">Review Detail Pengantar KK</h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">ID Pengajuan:
                    #{{ strtoupper(str_replace('_', '-', $surat->jenis_surat)) }}-{{ str_pad($surat->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-10">

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-folder-open"></i> Informasi Pengajuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tujuan
                            Pengajuan</label>
                        <div class="px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl text-blue-700 font-bold">
                            {{ $surat->keperluan ?? $dataTambahan['tujuan_pengajuan'] ?? 'Pembaharuan / Perubahan Data' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama
                            Kepala Keluarga (Tujuan)</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['nama_kepala_keluarga'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-user"></i> Identitas Pemohon
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIK
                            Pemohon</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->nik ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor KK
                            Lama</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['kk_lama'] ?? $dataTambahan['nomor_kk'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama
                            Lengkap</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->name ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tempat
                            Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->tempat_lahir ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal
                            Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->tanggal_lahir ? \Carbon\Carbon::parse($surat->user->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jenis
                            Kelamin</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->jenis_kelamin ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agama</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->agama ?? 'Islam' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status
                            Perkawinan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->status_perkawinan ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pekerjaan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $surat->user->pekerjaan ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-map-marker-alt"></i> Alamat Sesuai Tujuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat
                            (Dusun/Jalan)</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['alamat'] ?? $dataTambahan['alamat_tujuan'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">RT</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['rt'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">RW</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataTambahan['rw'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-paperclip"></i> Dokumen Pendukung
                </h3>
                <div class="space-y-4">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">KK
                                Lama / Surat Kehilangan</label>
                            <span
                                class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_kk_lama']) ? 'Berkas Tersedia' : 'Berkas Tidak Ditemukan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_kk_lama']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_kk_lama']) }}" target="_blank"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-red-500 italic font-semibold">Tidak ada file</span>
                        @endif
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Buku
                                Nikah / Akta Cerai (Opsional)</label>
                            <span
                                class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_buku_nikah']) ? 'Berkas Tersedia' : 'Berkas Tidak Dilampirkan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_buku_nikah']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_buku_nikah']) }}" target="_blank"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-gray-500 italic font-semibold">-</span>
                        @endif
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label
                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Dokumen
                                Pendukung Lain (Opsional)</label>
                            <span
                                class="text-sm text-gray-800 font-medium">{{ !empty($dataTambahan['file_pendukung']) ? 'Berkas Tersedia' : 'Berkas Tidak Dilampirkan' }}</span>
                        </div>
                        @if(!empty($dataTambahan['file_pendukung']))
                            <a href="{{ asset('storage/' . $dataTambahan['file_pendukung']) }}" target="_blank"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-gray-500 italic font-semibold">-</span>
                        @endif
                    </div>
                </div>
            </div>

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
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Verifikator / Alasan
                                Penolakan</label>
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
                        confirmButtonColor: '#1a5e35', // Warna hijau desa
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
                        confirmButtonColor: '#d33', // Warna merah tolak
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