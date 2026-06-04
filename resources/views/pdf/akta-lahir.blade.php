<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Pengantar Akta Kelahiran</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            margin: 0.5cm 1cm;
            color: black;
        }

        /* KOP SURAT PRESISI */
        .header-table {
            width: 100%;
            border-bottom: 3px double black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: middle;
            text-align: center;
        }

        .logo-container {
            width: 80px;
        }

        .logo {
            width: 75px;
            height: auto;
        }

        .header-text h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-text h3 {
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

        /* BAGIAN INFO NOMOR & TANGGAL */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
        }

        .info-left {
            width: 60%;
        }

        .info-right {
            width: 40%;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 20px;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .col-label {
            width: 30%;
        }

        .col-titik {
            width: 3%;
        }

        /* AREA TANDA TANGAN & QR */
        .ttd-table {
            width: 100%;
            margin-top: 30px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    @php
        $data = $surat->data_tambahan;
        // Penanganan QR Code
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('verifikasi.surat', ['token' => $surat->token_verifikasi ?? 'data-lama']);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(85)->margin(0)->generate($qrData));
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
                <h2>{{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Alamat tidak diatur' }}</p>
            </td>
            <td class="logo-container"></td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-left">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 20%;">Nomor</td>
                        <td style="width: 5%;">:</td>
                        <td>{{ $surat->nomor_surat ?? '.......' }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><strong>Surat Pengantar Untuk Pengurusan<br>AKTE Kelahiran</strong></td>
                    </tr>
                </table>
            </td>
            <td class="info-right">
                {{ ucwords(strtolower($pengaturan->nama_desa ?? 'Buttu Sawe')) }},
                {{ $surat->updated_at->translatedFormat('d F Y') }}<br>
                Kepada,<br>
                Yth. <strong>Kepala Dinas Kependudukan dan Catatan Sipil</strong><br>
                Di,-<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Kab. Pinrang</strong>
            </td>
        </tr>
    </table>

    <div class="clear"></div>

    <p style="margin-top: 10px;">Yang bertanda tangan dibawah ini :</p>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">- N a m a</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_anak'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">- Tempat / Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_anak'] ?? '-' }},
                {{ isset($data['tanggal_lahir_anak']) ? \Carbon\Carbon::parse($data['tanggal_lahir_anak'])->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">- Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_anak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">- Kewarganegaraan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['kewarganegaraan_anak'] ?? 'Indonesia' }}</td>
        </tr>
        <tr>
            <td class="col-label">- A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_anak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">- Alamat</td>
            <td class="col-titik">:</td>
            <td>{{ $data['alamat_anak'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">- Nama Lengkap Ayah</td>
            <td class="col-titik">:</td>
            <td>{{ strtoupper($data['nama_ayah'] ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">- Nama Lengkap Ibu</td>
            <td class="col-titik">:</td>
            <td>{{ strtoupper($data['nama_ibu'] ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">- Anak Ke</td>
            <td class="col-titik">:</td>
            <td>{{ $data['anak_ke'] ?? '-' }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Untuk mendapatkan Akte Kelahiran Pada Kantor Saudara dan Sebagai bahan kelengkapan
        Administrasi terlampir sebagai berikut :</p>
    <div style="margin-left: 25px;">
        1. Foto Copy Kartu Keluarga<br>
        2. Surat Pernyataan saksi yang ditanda tangani dari 2 (dua) Orang Saksi yang diketahui oleh Kepala Desa.<br>
        3. Keterangan lain-lain yang erat kaitannya dan identitas diri pemohon
    </div>

    <table class="ttd-table">
        <tr>
            <!-- Kolom kiri dikosongkan -->
            <td style="width: 50%;"></td>

            <!-- Kolom kanan untuk Tanda Tangan -->
            <td style="width: 50%; text-align: center;">
                <p style="margin-bottom: 0;">Kamali,
                    {{ $surat->updated_at ? \Carbon\Carbon::parse($surat->updated_at)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                </p>
                <p style="margin-top: 2px; margin-bottom: 0;">KEPALA
                    {{ strtoupper($pengaturan->nama_desa ?? 'DESA BUTTU SAWE') }}
                </p>

                @if($surat->metode_ttd == 'digital')
                    <div style="margin-top: 10px; margin-bottom: 5px;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="85">
                    </div>
                    <p style="font-size: 8pt; color: gray; margin-bottom: 5px;"><i>Ditandatangani secara elektronik</i></p>
                @elseif($surat->metode_ttd == 'konvensional')
                    @if($kades && $kades->ttd_path)
                        <img src="{{ public_path('storage/' . $kades->ttd_path) }}" width="110"
                            style="margin-top: 10px; margin-bottom: 10px;">
                    @else
                        <div style="height: 80px;"></div>
                    @endif
                @else
                    <div style="height: 80px;"></div>
                @endif

                <p><strong><u>{{ $kades->name ?? 'NAMA KEPALA DESA' }}</u></strong></p>
                @if($kades && $kades->nip)
                    <p style="margin: 0; font-size: 10pt;">NIP. {{ $kades->nip }}</p>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>
