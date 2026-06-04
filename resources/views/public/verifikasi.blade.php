<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Verifikasi Dokumen - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-b from-[#11442b] to-[#1a5e35] p-6 text-center">
            <!-- Sesuaikan path logo dengan yang ada di project kamu -->
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA" class="h-10 mx-auto mb-2">
            <h1 class="text-white font-bold text-lg">Sinergi Layanan Digital Desa</h1>
            <p class="text-white/80 text-xs">Pemerintah Desa Buttu Sawe</p>
        </div>

        <div class="p-6">
            @if($surat)
                <!-- TAMPILAN JIKA SURAT VALID -->
                <div class="flex flex-col items-center mb-6">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-5xl text-emerald-500 shadow-sm rounded-full bg-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 text-center">DOKUMEN VALID</h2>
                    <p class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-3 py-1 rounded-full mt-2">
                        Tercatat di Database Resmi
                    </p>
                </div>

                <div class="space-y-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Nomor Surat</p>
                        <p class="text-sm font-bold text-gray-800 font-mono">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Jenis Surat</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ str_replace('_', ' ', strtoupper($surat->jenis_surat)) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Nama Pemohon</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $surat->user->name ?? 'Tidak diketahui' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tanggal Diterbitkan</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $surat->updated_at->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Penandatangan</p>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-signature text-xl text-[#cfa03f]"></i>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $kades->name ?? 'Kepala Desa' }}</p>
                                <p class="text-[11px] font-semibold text-gray-600 uppercase mb-0.5">Kepala Desa Buttu Sawe</p>
                                <p class="text-[10px] text-gray-500">Tanda Tangan Elektronik
                                    {{ $surat->metode_ttd === 'digital' ? 'Disetujui' : '(Manual)' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- TAMPILAN JIKA SURAT PALSU / TIDAK DITEMUKAN -->
                <div class="flex flex-col items-center mb-6 py-4">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 relative">
                        <i class="fas fa-times-circle text-5xl text-red-500 bg-white rounded-full"></i>
                        <i
                            class="fas fa-exclamation-triangle text-xl text-yellow-500 absolute -bottom-1 -right-1 bg-white rounded-full"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 text-center">TIDAK VALID</h2>
                    <p class="text-xs text-red-600 font-semibold bg-red-50 px-3 py-1 rounded-full mt-2">
                        Dokumen Tidak Ditemukan
                    </p>
                </div>

                <div class="bg-red-50 p-4 rounded-xl border border-red-100 text-center">
                    <p class="text-sm text-red-800 leading-relaxed">
                        Maaf, dokumen dengan nomor <br><strong class="font-mono">{{ $nomorSurat ?? 'Kosong' }}</strong><br>
                        tidak ditemukan di dalam database kami.
                    </p>
                    <p class="text-xs text-red-600 mt-3 font-medium">
                        Harap berhati-hati terhadap indikasi pemalsuan dokumen.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-gray-100 p-4 text-center border-t border-gray-200">
            <p class="text-[10px] text-gray-400 font-medium">© {{ date('Y') }} Pemerintah Desa Buttu Sawe. All rights
                reserved.</p>
        </div>
    </div>

</body>

</html>