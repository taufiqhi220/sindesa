<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Tanda Tangan Ket. Penghasilan - SINDESA</title>
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

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
                        class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'H', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'H. Burhanuddin' }}</h4>
                <p class="text-[10px] opacity-70">Kepala Desa</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center justify-between p-3 bg-[#cfa03f] rounded-lg text-white font-medium mb-2 shadow-md">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-signature w-5 text-center"></i> Perlu Tanda Tangan
                </div>
                @if(isset($unreadCountKades) && $unreadCountKades > 0) <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $unreadCountKades }}</span> @endif
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">
        @php
            $dataPengajuan = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
        @endphp

        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('kades.perlu-ttd') }}"
                class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-[#1a5e35]">Detail Surat Menunggu Tanda Tangan</h2>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">
                    Keterangan Penghasilan - {{ $surat->nomor_surat ?? 'NOMOR BELUM DISET' }}
                </p>
            </div>
        </div>

        <div
            class="bg-emerald-50 border border-emerald-500 text-emerald-800 p-4 rounded-xl mb-8 flex items-start gap-4 shadow-sm">
            <i class="fas fa-shield-alt text-2xl text-emerald-600 mt-0.5"></i>
            <div>
                <strong class="block mb-1 text-emerald-700">Siap Ditandatangani</strong>
                <p class="text-sm">Surat ini telah diverifikasi oleh Operator pada
                    {{ $surat->updated_at->translatedFormat('d F Y, H:i') }} WIB. Identitas dan rincian penghasilan
                    beserta tanggungan telah diperiksa sesuai dengan berkas yang dilampirkan.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-10">

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-user-tie"></i> Identitas Orang Tua / Wali (Pemohon)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NIK
                            Pemohon</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-bold">
                            {{ $dataPengajuan['nik'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama
                            Lengkap</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-bold">
                            {{ $dataPengajuan['nama'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tempat
                            Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataPengajuan['tempat_lahir'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal
                            Lahir</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ !empty($dataPengajuan['tanggal_lahir']) ? \Carbon\Carbon::parse($dataPengajuan['tanggal_lahir'])->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jenis
                            Kelamin</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataPengajuan['jenis_kelamin'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agama</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataPengajuan['agama'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pekerjaan</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold">
                            {{ $dataPengajuan['pekerjaan'] ?? '-' }}
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat
                            Lengkap</label>
                        <div
                            class="px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-800 font-semibold leading-relaxed">
                            {{ $dataPengajuan['alamat'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-emerald-600 border-b-2 border-emerald-50 pb-3 mb-6">
                    <i class="fas fa-money-bill-wave"></i> Rincian Penghasilan & Tanggungan
                </h3>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-emerald-50 p-6 rounded-xl border border-emerald-100">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Jumlah
                            Penghasilan / Bulan</label>
                        <div class="text-emerald-900 font-bold text-xl">
                            {{ $dataPengajuan['jumlah_penghasilan'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Jumlah
                            Tanggungan Keluarga</label>
                        <div class="text-emerald-900 font-bold text-lg mt-1">
                            {{ $dataPengajuan['jumlah_tanggungan'] ?? '0' }} Orang
                        </div>
                    </div>
                    <div class="md:col-span-2 pt-4 border-t border-emerald-200/60">
                        <label class="block text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Nama
                            Anak / Anggota Keluarga yang Ditanggung (Tujuan Surat)</label>
                        <div class="text-emerald-900 font-semibold leading-relaxed">
                            {{ $dataPengajuan['nama_tanggungan'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3
                    class="flex items-center gap-2 text-lg font-bold text-[#1a5e35] border-b-2 border-gray-50 pb-3 mb-6">
                    <i class="fas fa-paperclip"></i> Dokumen Lampiran (Telah Diverifikasi)
                </h3>
                <div class="space-y-4">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">KTP
                                & KK Orang Tua</label>
                            <span
                                class="text-sm text-gray-800 font-medium">{{ !empty($dataPengajuan['file_kk']) ? 'Berkas Tersedia' : 'Berkas Tidak Ditemukan' }}</span>
                        </div>
                        @if(!empty($dataPengajuan['file_kk']))
                            <a href="{{ asset('storage/' . $dataPengajuan['file_kk']) }}" target="_blank"
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
                            <label
                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bukti /
                                KTP Anak (Opsional)</label>
                            <span
                                class="text-sm text-gray-800 font-medium">{{ !empty($dataPengajuan['file_anak']) ? 'Berkas Tersedia' : 'Berkas Tidak Dilampirkan' }}</span>
                        </div>
                        @if(!empty($dataPengajuan['file_anak']))
                            <a href="{{ asset('storage/' . $dataPengajuan['file_anak']) }}" target="_blank"
                                class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-white border border-[#1a5e35] text-[#1a5e35] rounded-full font-semibold text-sm hover:bg-[#1a5e35] hover:text-white transition-all shadow-sm">
                                <i class="fas fa-cloud-download-alt"></i> Lihat File
                            </a>
                        @else
                            <span class="text-xs text-gray-500 italic font-semibold">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('kades.surat.proses', $surat->id) }}" method="POST" id="formTolak">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <div class="bg-gray-50 rounded-2xl p-6 md:p-8 border border-gray-200 shadow-inner mt-12">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan / Alasan Penolakan</label>
                        <textarea name="keterangan" id="catatanPenolakan"
                            placeholder="Wajib diisi jika berkas ini ditolak dan dikembalikan ke operator..." rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all text-sm"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-4">
                        <button type="submit" onclick="return validasiTolak()"
                            class="px-8 py-3 bg-white border-2 border-red-500 text-red-500 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm text-sm">
                            <i class="fas fa-times-circle"></i> Tolak Dokumen
                        </button>
                        <button type="button" onclick="openPrintModal()"
                            class="px-8 py-3 bg-[#cfa03f] text-white rounded-xl font-bold hover:bg-[#b88e32] transition-all flex items-center justify-center gap-2 shadow-lg shadow-yellow-900/20 text-sm">
                            <i class="fas fa-file-signature"></i> Setujui & Tanda Tangani
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <div id="printModal"
        class="fixed inset-0 bg-black/60 z-[1050] hidden flex-col items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <form action="{{ route('kades.surat.proses', $surat->id) }}" method="POST"
            class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300"
            id="printModalContent">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="selesai">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-[#1a5e35] flex items-center gap-2"><i class="fas fa-pen-fancy"></i>
                    Opsi Penandatanganan</h3>
                <button type="button" onclick="closePrintModal()"
                    class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500 mb-6">Pilih metode penandatanganan sebelum menyetujui surat ini.</p>
                <div class="space-y-4">
                    <!-- Opsi 1: QR Code -->
                    <label
                        class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                        <input type="radio" name="ttd_method" value="digital" class="sr-only" checked>
                        <div class="flex items-center gap-4 w-full">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <i class="fas fa-qrcode text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-sm">Tanda Tangan Elektronik (QR Code)</p>
                                <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyematkan QR Code validasi
                                    langsung ke dokumen PDF.</p>
                            </div>
                            <div
                                class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                                <div
                                    class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Opsi 2: TTD Konvensional -->
                    <label
                        class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                        <input type="radio" name="ttd_method" value="konvensional" class="sr-only">
                        <div class="flex items-center gap-4 w-full">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-signature text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-sm">TTD Konvensional (Gambar)</p>
                                <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyematkan gambar scan tanda
                                    tangan Kades ke dokumen PDF.</p>
                            </div>
                            <div
                                class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                                <div
                                    class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Opsi 3: TTD Basah (Manual) -->
                    <label
                        class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                        <input type="radio" name="ttd_method" value="manual" class="sr-only">
                        <div class="flex items-center gap-4 w-full">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-pen-nib text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-sm">TTD Basah (Kosong)</p>
                                <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyiapkan ruang kosong pada
                                    dokumen untuk ditandatangani pulpen.</p>
                            </div>
                            <div
                                class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                                <div
                                    class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/50">
                <button type="button" onclick="closePrintModal()"
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors text-sm shadow-sm">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#cfa03f] text-white rounded-xl font-bold hover:bg-[#b88e32] transition-colors flex items-center gap-2 shadow-md text-sm">
                    <i class="fas fa-check-circle"></i> Proses Sekarang
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function validasiTolak() {
            const pesan = document.getElementById('catatanPenolakan');
            if (pesan.value.trim() === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Alasan Belum Diisi',
                    text: 'Wajib memberikan alasan penolakan agar operator/warga mengetahui kekurangan berkas.',
                    confirmButtonColor: '#d33',
                });
                pesan.focus();
                return false;
            }
            return true;
        }

        function openPrintModal() {
            document.getElementById('catatanPenolakan').removeAttribute('required');
            const modal = document.getElementById('printModal');
            const content = document.getElementById('printModalContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closePrintModal() {
            document.getElementById('catatanPenolakan').setAttribute('required', 'required');
            const modal = document.getElementById('printModal');
            const content = document.getElementById('printModalContent');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }
    </script>
</body>

</html>