<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Usaha</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: black;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 4px double black;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .kop-surat td {
            vertical-align: middle;
        }

        .header-text {
            text-align: center;
        }

        .header-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 20px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .col-label {
            width: 35%;
        }

        .col-titik {
            width: 3%;
        }

        /* DETAIL USAHA */
        .detail-usaha {
            margin-top: 15px;
            margin-left: 30px;
        }

        .detail-usaha table td {
            padding: 2px 0;
        }

        /* AREA TANDA TANGAN */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
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
@endphp

@php
        $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.surat', ['token' => $surat->token_verifikasi ?? 'data-lama']);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->margin(0)->generate($qrData));

        \Carbon\Carbon::setLocale('id');
        $tglSurat = $surat->updated_at ? $surat->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');
        $tglLahir = isset($data['tanggal_lahir']) ? \Carbon\Carbon::parse($data['tanggal_lahir'])->translatedFormat('d F Y') : '-';

        // Membersihkan redudansi kata "Kecamatan"[cite: 7]
        $namaKecStr = ucwords(strtolower(str_ireplace('kecamatan ', '', $pengaturan->header_2 ?? 'Duampanua')));
        $namaDesaStr = ucwords(strtolower(str_ireplace('desa ', '', $pengaturan->nama_desa ?? 'Buttu Sawe')));
    @endphp

    <table class="kop-surat">
        <tr>
            <td style="width: 12%; text-align: left;">
                @if(!empty($pengaturan->logo_path))
                    <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" style="width: 70px;">
                @endif
            </td>
            <td style="width: 76%;" class="header-text">
                <h2>{{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}</h2>
                <h2>KECAMATAN {{ strtoupper($namaKecStr) }}</h2>
                <h2>DESA {{ strtoupper($namaDesaStr) }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421... Kode Pos 91253' }}
                </p>
            </td>
            <td style="width: 12%;"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN USAHA</h4>
        <p style="margin: 0; font-size: 11pt;">Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <p style="margin-bottom: 10px;">Yang bertanda tangan dibawah ini menerangkan bahwa :</p>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir'] ?? '-' }}, {{ $tglLahir }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">No. KK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['no_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td>{{ $data['alamat_dusun'] ?? '-' }}, RT {{ $data['rt'] ?? '000' }} / RW {{ $data['rw'] ?? '000' }}<br>
                Desa {{ $namaDesaStr }} Kecamatan {{ $namaKecStr }}</td>
        </tr>
    </table>

    <p style="margin-top: 15px;">Yang tersebut namanya diatas benar-benar mempunyai usaha sebagai berikut :</p>

    <div class="detail-usaha">
        <table style="width: 100%;">
            <tr>
                <td style="width: 5%; vertical-align: middle;">-</td>
                <td style="width: 25%;">Usaha</td>
                <td style="width: 3%;">:</td>
                <td><strong>{{ strtoupper($data['jenis_usaha'] ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td style="vertical-align: middle;">-</td>
                <td>Usaha Sampingan</td>
                <td>:</td>
                <td>{{ $data['usaha_sampingan'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="vertical-align: middle;">-</td>
                <td>Bertempat di</td>
                <td>:</td>
                <td>{{ strtoupper($data['alamat_usaha'] ?? '-') }}.</td>
            </tr>
        </table>
    </div>

    <p style="margin-top: 15px;">Demikian Surat Keterangan Usaha ini kami berikan kepada yang bersangkutan untuk
        dipergunakan sebagaimana mestinya.</p>

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
                @elseif(($surat->metode_ttd == 'konvensional' || empty($surat->metode_ttd)) && !empty($ttdRenderSrc))
                    <div style="margin: 8px 0;">
                        <img src="{{ $ttdRenderSrc }}" width="105" style="display: block; margin: 0 auto;">
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
