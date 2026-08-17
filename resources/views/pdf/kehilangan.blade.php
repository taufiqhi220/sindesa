<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Kehilangan</title>
    <style>
        @page {
            margin: 1cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            color: black;
        }

        /* KOP SURAT PRESISI TENGAH */
        .kop-surat {
            width: 100%;
            border-bottom: 4px double black;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .kop-surat td {
            vertical-align: middle;
        }

        .header-text {
            text-align: center;
        }

        .header-text h2 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 10.5pt;
            font-style: italic;
            white-space: nowrap;
        }

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-surat p {
            margin: 0;
            font-size: 12pt;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 30px;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 4px 0;
        }

        .col-label {
            width: 35%;
        }

        .col-titik {
            width: 3%;
        }

        .teks-paragraf {
            text-align: justify;
            margin-top: 15px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        /* AREA TANDA TANGAN */
        .ttd-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    @php
    $ttdRenderSrc = null;
    if (isset($kades)) {
        if (!empty($kades->ttd_base64)) {
            $ttdRenderSrc = $kades->ttd_base64;
        } elseif (!empty($kades->ttd_path)) {
            $cleanTtdPath = ltrim(str_replace(['storage/app/public/', 'public/storage/', 'storage/'], '', $kades->ttd_path), '/');
            $ttdBaseFilename = basename($cleanTtdPath);
            $ttdCandidates = [
                storage_path('app/public/' . $cleanTtdPath),
                storage_path('app/public/ttd/' . $ttdBaseFilename),
                storage_path('app/public/ttd_kades/' . $ttdBaseFilename),
                public_path('storage/' . $cleanTtdPath),
                public_path('storage/ttd/' . $ttdBaseFilename),
                public_path('storage/ttd_kades/' . $ttdBaseFilename),
                '/home/sindesa/sindesa-app/storage/app/public/' . $cleanTtdPath,
                '/home/sindesa/sindesa-app/storage/app/public/ttd_kades/' . $ttdBaseFilename,
                '/home/sindesa/sindesa-app/storage/app/public/ttd/' . $ttdBaseFilename,
            ];
            foreach ($ttdCandidates as $tPath) {
                if (file_exists($tPath) && is_file($tPath)) {
                    $tMime = mime_content_type($tPath) ?: 'image/png';
                    $ttdRenderSrc = 'data:' . $tMime . ';base64,' . base64_encode(file_get_contents($tPath));
                    break;
                }
            }
        }
    }

    // Smart Fallback: Jika ttdRenderSrc masih null, cari file ttd kades apapun yang ada di storage
    if (empty($ttdRenderSrc)) {
        $scanDirs = [
            storage_path('app/public/ttd_kades'),
            storage_path('app/public/ttd'),
            public_path('storage/ttd_kades'),
            public_path('storage/ttd'),
            '/home/sindesa/sindesa-app/storage/app/public/ttd_kades',
            '/home/sindesa/sindesa-app/storage/app/public/ttd',
        ];
        foreach ($scanDirs as $sDir) {
            if (is_dir($sDir)) {
                $sFiles = @scandir($sDir);
                if ($sFiles) {
                    foreach ($sFiles as $sf) {
                        if ($sf !== '.' && $sf !== '..' && !str_starts_with($sf, '.')) {
                            $sfPath = $sDir . '/' . $sf;
                            if (is_file($sfPath)) {
                                $sMime = mime_content_type($sfPath) ?: 'image/png';
                                $ttdRenderSrc = 'data:' . $sMime . ';base64,' . base64_encode(file_get_contents($sfPath));
                                break 2;
                            }
                        }
                    }
                }
            }
        }
    }
@endphp

@php
        $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.surat', ['token' => $surat->token_verifikasi ?? 'data-lama']);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($qrData));

        \Carbon\Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

        $tanggalLahir = isset($data['tanggal_lahir_pelapor'])
            ? \Carbon\Carbon::parse($data['tanggal_lahir_pelapor'])->locale('id')->translatedFormat('d F Y')
            : '-';

        $waktuHilang = isset($data['waktu_hilang'])
            ? \Carbon\Carbon::parse($data['waktu_hilang'])->locale('id')->translatedFormat('d F Y')
            : '-';
    @endphp

    <table class="kop-surat">
        <tr>
            <td style="width: 12%; text-align: left;">
                @if(!empty($pengaturan->logo_path))
                    <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" style="width: 75px; height: auto;">
                @endif
            </td>
            <td style="width: 76%;" class="header-text">
                <h2>{{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}</h2>
                <h2>{{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}</h2>
                <h2>{{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253' }}
                </p>
            </td>
            <td style="width: 12%;"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN KEHILANGAN</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <p style="margin-bottom: 15px;">Yang bertanda tangan dibawah ini Kepala
        {{ ucwords(strtolower($pengaturan->nama_desa ?? 'Desa Buttu Sawe')) }} menerangkan bahwa :
    </p>

    <!-- IDENTITAS PELAPOR -->
    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_pelapor'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_pelapor'] ?? '-' }}, {{ $tanggalLahir }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_pelapor'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">N I K</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_pelapor'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_pelapor'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_pelapor'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A l a m a t</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.4;">{{ $data['alamat_pelapor'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="teks-paragraf">
        Berdasarkan Keterangan / Laporan dari yang bersangkutan tersebut diatas, benar telah kehilangan
        <strong>{{ $data['rincian_hilang'] ?? '-' }}</strong>. Telah hilang pada tanggal {{ $waktuHilang }} di
        {{ $data['lokasi_hilang'] ?? '-' }}.
    </div>

    <div class="teks-paragraf">
        Demikian Surat Keterangan Kehilangan ini kami berikan kepada yang bersangkutan untuk dipergunakan sebagaimana
        mestinya.
    </div>

    <table class="ttd-table" style="width: 100%; margin-top: 20px; page-break-inside: avoid;">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">
                <p style="margin: 0;">Kamali,
                    {{ $surat->updated_at ? \Carbon\Carbon::parse($surat->updated_at)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                </p>
                <p style="margin: 2px 0 0 0;">KEPALA {{ strtoupper($pengaturan->nama_desa ?? 'DESA BUTTU SAWE') }}</p>

                @if($surat->metode_ttd == 'digital')
                    <div style="margin: 10px 0 5px 0;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="80"
                            style="display: block; margin: 0 auto;">
                    </div>
                    <p style="font-size: 10pt; color: #777; margin: 0; font-style: italic;">Ditandatangani secara elektronik
                    </p>
                    <div style="margin-top: 15px;"></div>
                @elseif($surat->metode_ttd == 'manual')
                    <div style="height: 80px;"></div>
                @elseif(!empty($ttdRenderSrc))
                    <div style="margin: 8px 0;">
                        <img src="{{ $ttdRenderSrc }}" width="115" style="display: block; margin: 0 auto;">
                    </div>
                @else
                    <div style="height: 80px;"></div>
                @endif

                <p style="margin: 0;"><strong><u>{{ $kades->name ?? 'Bapak Kepala Desa' }}</u></strong></p>
                @if($kades && $kades->nip)
                    <p style="margin: 0; font-size: 10pt;">NIP. {{ $kades->nip }}</p>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>
