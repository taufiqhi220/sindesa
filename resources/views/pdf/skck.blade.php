<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Pengantar SKCK</title>
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
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 10.5pt;
            font-style: italic;
            white-space: nowrap;
        }

        /* BAGIAN TUJUAN SURAT */
        .tabel-tujuan {
            width: 100%;
            margin-bottom: 20px;
        }

        .tabel-tujuan td {
            vertical-align: top;
            padding: 2px 0;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 20px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 3px 0;
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
            text-indent: 30px;
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
        $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.surat', ['token' => $surat->token_verifikasi ?? 'data-lama']);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->margin(0)->generate($qrData));

        \Carbon\Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

        $tglLahir = isset($data['tanggal_lahir']) ? \Carbon\Carbon::parse($data['tanggal_lahir'])->translatedFormat('d F Y') : '-';
        $tglSurat = $surat->updated_at ? $surat->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');
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

    <table class="tabel-tujuan">
        <tr>
            <td style="width: 60%;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 25%;">Nomor</td>
                        <td style="width: 5%;">:</td>
                        <td>{{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td>Pengantar Mendapatkan SKCK</td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: left;">
                Kamali, {{ $tglSurat }}<br><br>
                K e p a d a,<br>
                Yth. KAPOLSEK DUAMPANUA<br>
                Di,-<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M a s s i l a
            </td>
        </tr>
    </table>

    <div style="text-indent: 30px; margin-bottom: 15px;">
        Yang bertanda tangan dibawah ini Kepala {{ ucwords(strtolower($pengaturan->nama_desa ?? 'Desa Buttu Sawe')) }}
        Kecamatan {{ ucwords(strtolower($pengaturan->header_2 ?? 'Duampanua')) }} menerangkan bahwa :
    </div>

    <!-- IDENTITAS PEMOHON -->
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
            <td class="col-label">N I K</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Kewarganegaraan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['kewarganegaraan'] ?? 'Indonesia' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A l a m a t</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.4;">{{ $data['alamat_dusun'] ?? '-' }}, RT
                {{ str_pad($data['rt'] ?? '0', 3, '0', STR_PAD_LEFT) }} RW
                {{ str_pad($data['rw'] ?? '0', 3, '0', STR_PAD_LEFT) }}<br>Desa Buttu Sawe Kecamatan Duampanua.
            </td>
        </tr>
    </table>

    <div class="teks-paragraf">
        Selama tinggal di {{ $data['alamat_dusun'] ?? '-' }} Desa Buttu Sawe yang bersangkutan tidak pernah terlibat
        dalam Perkara Kepolisian dan Tidak Pernah memasuki salah satu Parpol terlarang dan tetap berkelakuan Baik
        sebagaimana permohonan yang bersangkutan untuk <strong>{{ $data['keperluan'] ?? '-' }}</strong>.
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
                @elseif($surat->metode_ttd == 'konvensional' && isset($kades) && $kades->ttd_path)
                    <div style="margin: 10px 0;">
                        <img src="{{ public_path('storage/' . $kades->ttd_path) }}" width="100"
                            style="display: block; margin: 0 auto;">
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
