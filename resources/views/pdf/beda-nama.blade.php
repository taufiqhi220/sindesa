<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Beda Nama</title>
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

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
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

        /* ISI SURAT */
        .isi-surat {
            text-align: justify;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 20px;
            margin-bottom: 10px;
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
            margin-top: 40px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    @php
        $data = is_string($surat->data_tambahan) ? json_decode($surat->data_tambahan, true) : (array) $surat->data_tambahan;
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
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang Desa Buttu Sawe Tlp. 081319025233 Kode Pos 91253' }}
                </p>
            </td>
            <td class="logo-container"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN BEDA NAMA</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <div class="isi-surat">
        <p>Yang bertanda tangan dibawah ini Kepala Desa Buttu Sawe Kecamatan Duampanua Kab. Pinrang menerangkan bahwa:
        </p>

        <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Data Di KTP & KK:</p>
        <table class="tabel-identitas">
            <tr>
                <td class="col-label">Nama</td>
                <td class="col-titik">:</td>
                <td><strong>{{ strtoupper($data['nama_dok1'] ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td class="col-label">Tempat & Tanggal Lahir</td>
                <td class="col-titik">:</td>
                <td>{{ $data['tempat_lahir_dok1'] ?? '-' }},
                    {{ isset($data['tanggal_lahir_dok1']) ? \Carbon\Carbon::parse($data['tanggal_lahir_dok1'])->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="col-label">N I K</td>
                <td class="col-titik">:</td>
                <td>{{ $data['nik_dok1'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Jenis Kelamin</td>
                <td class="col-titik">:</td>
                <td>{{ $data['jenis_kelamin_dok1'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Alamat</td>
                <td class="col-titik">:</td>
                <td>{{ $data['alamat_dok1'] ?? '-' }}</td>
            </tr>
        </table>

        <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px; margin-top: 15px;">Data Di
            {{ strtoupper($data['nama_dokumen2'] ?? 'DOKUMEN LAIN') }}:
        </p>
        <table class="tabel-identitas">
            <tr>
                <td class="col-label">Nama</td>
                <td class="col-titik">:</td>
                <td><strong>{{ strtoupper($data['nama_dok2'] ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td class="col-label">Tempat & Tanggal Lahir</td>
                <td class="col-titik">:</td>
                <td>{{ $data['tempat_lahir_dok2'] ?? '-' }},
                    {{ isset($data['tanggal_lahir_dok2']) ? \Carbon\Carbon::parse($data['tanggal_lahir_dok2'])->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="col-label">Nomor {{ strtoupper($data['nama_dokumen2'] ?? 'Identitas') }}</td>
                <td class="col-titik">:</td>
                <td>{{ $data['nomor_dok2'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Jenis Kelamin</td>
                <td class="col-titik">:</td>
                <td>{{ $data['jenis_kelamin_dok2'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Alamat</td>
                <td class="col-titik">:</td>
                <td>{{ $data['alamat_dok2'] ?? '-' }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">
            Yang tersebut namanya di atas benar adalah orang yang sama namun terdapat ketidaksesuaian/perbedaan
            <strong>{{ $data['data_berbeda'] ?? 'data' }}</strong> antara data di KTP & KK dengan di
            <strong>{{ $data['nama_dokumen2'] ?? 'Dokumen Lainnya' }}</strong>. Dan data yang benar adalah data yang
            tercantum di <strong>{{ strtoupper($data['acuan_kebenaran'] ?? 'KTP dan KK') }}</strong>.
        </p>

        <p>Demikian Surat Keterangan ini dibuat dengan sebenar-benarnya dan diberikan kepada yang bersangkutan untuk
            dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-table">
        <tr>
            <!-- Kolom kiri dikosongkan -->
            <td style="width: 50%;"></td>

            <td style="width: 50%; text-align: center;">
                <p style="margin-bottom: 0;">Kamali,
                    {{ $surat->updated_at ? $surat->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <p style="margin-top: 2px; margin-bottom: 0;">KEPALA
                    {{ strtoupper($pengaturan->nama_desa ?? 'DESA BUTTU SAWE') }}</p>

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
