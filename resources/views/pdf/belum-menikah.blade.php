<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Belum Menikah</title>
    <style>
        @page {
            margin: 1cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.2;
            color: black;
        }

        /* KOP SURAT FLEKSIBEL */
        .header-table {
            width: 100%;
            border-bottom: 3px solid black;
            border-bottom-style: double;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-container {
            width: 15%;
            text-align: left;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .header-text {
            width: 70%;
            text-align: center;
        }

        .header-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .header-text h3 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .header-text p {
            margin: 0;
            padding: 0;
            font-size: 10pt;
            line-height: 1.2;
            font-style: italic;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .judul-surat p {
            margin: 0;
            font-size: 12pt;
        }

        .tabel-identitas {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .col-label {
            width: 32%;
        }

        .col-titik {
            width: 3%;
        }

        .teks-paragraf {
            text-align: justify;
            text-indent: 30px;
            margin-bottom: 10px;
            line-height: 1.25;
        }
    </style>
</head>

<body>

    @php
        $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.surat', ['token' => $surat->token_verifikasi ?? 'data-lama']);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($qrData));
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if(!empty($pengaturan->logo_path))
                    <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" class="logo">
                @endif
            </td>
            <td class="header-text">
                <h2>{{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}</h2>
                <h3>{{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}</h3>
                <h2 style="font-size: 16pt;">{{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang Tlp. 0421…………………………………….……………KodePos 91253' }}
                </p>
            </td>
            <td class="logo-container"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN BELUM NIKAH</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '474.1/002/DBS/IV/2026' }}</p>
    </div>

    <p style="margin-bottom: 8px;">Yang bertanda tangan dibawah ini menerangkan bahwa :</p>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">N a m a</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_pemohon'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Tempat Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_pemohon'] ?? '-' }},
                {{ isset($data['tanggal_lahir_pemohon']) ? \Carbon\Carbon::parse($data['tanggal_lahir_pemohon'])->locale('id')->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">N I K</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_pemohon'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_pemohon'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_pemohon'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_pemohon'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td>{{ $data['alamat_pemohon'] ?? '-' }}</td>
        </tr>
    </table>

    <p style="font-weight: bold; margin: 10px 0 5px 0;">ANAK DARI</p>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama Bapak</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_bapak'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Tempat Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_bapak'] ?? '-' }},
                {{ isset($data['tanggal_lahir_bapak']) ? \Carbon\Carbon::parse($data['tanggal_lahir_bapak'])->locale('id')->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">N I K</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_bapak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_bapak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_bapak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td>{{ $data['alamat_bapak'] ?? '-' }}</td>
        </tr>
    </table>

    <table class="tabel-identitas" style="margin-top: 15px;">
        <tr>
            <td class="col-label">Nama Ibu</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_ibu'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Tempat Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_ibu'] ?? '-' }},
                {{ isset($data['tanggal_lahir_ibu']) ? \Carbon\Carbon::parse($data['tanggal_lahir_ibu'])->locale('id')->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">N I K</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_ibu'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_ibu'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_ibu'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td>{{ $data['alamat_ibu'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="teks-paragraf" style="margin-top: 15px;">
        Yang tersebut namanya diatas adalah benar warga kami dari Dusun Kampung Baru Desa Buttu Sawe Kec. Duampanua dan
        benar yang bersangkutan berstatus belum nikah, sampai pada saat diterbirtkannya surat keterangan ini.
    </div>

    <div class="teks-paragraf" style="text-indent: 0;">
        Demikian surat keterangan ini kami berikan untuk dipergunakan seperlunya.
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
