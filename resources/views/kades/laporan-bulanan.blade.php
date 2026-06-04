<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Laporan Rekapitulasi SINDESA</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            color: black;
        }

        /* CSS Kop Surat */
        .kop-surat {
            width: 100%;
            margin-bottom: 5px;
        }

        .kop-surat td {
            text-align: center;
            vertical-align: middle;
        }

        .logo {
            width: 95px;
            /* Diperbesar dari 75px */
            height: auto;
        }

        .logo-kanan {
            width: 115px;
            /* Diperbesar dari 90px */
            height: auto;
        }

        .judul-kop {
            font-size: 18px;
            /* Diperbesar */
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .judul-desa {
            font-size: 24px;
            /* Diperbesar dari 20px */
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .alamat-kop {
            font-size: 11px;
            margin-top: 5px;
        }

        /* CSS Garis Tetap */
        .garis-tebal {
            border-bottom: 3px solid black;
            margin-top: 8px;
        }

        .garis-tipis {
            border-bottom: 1px solid black;
            margin-top: 2px;
            margin-bottom: 20px;
        }

        /* CSS Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table.data th,
        table.data td {
            border: 1px solid black;
            padding: 6px 8px;
            text-align: left;
        }

        table.data th {
            background-color: #e2e8f0;
            text-align: center;
        }

        .ttd-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <table class="kop-surat">
        <tr>
            <td width="20%"> @if($pengaturan && $pengaturan->logo_path)
                <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" class="logo" alt="Logo Daerah">
            @else
                    <img src="{{ public_path('image/logo-pinrang.png') }}" class="logo" alt="Logo Default">
                @endif
            </td>

            <td width="60%">
                <p class="judul-kop">{{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}</p>
                <p class="judul-kop">{{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}</p>
                <p class="judul-desa">{{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}</p>
                <p class="alamat-kop">
                    {{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253' }}
                </p>
            </td>

            <td width="20%"> <img src="{{ public_path('image/SINDESA_BLACK_TRANSPARNT.png') }}" class="logo-kanan"
                    alt="Logo SINDESA">
            </td>
        </tr>
    </table>

    <div class="garis-tebal"></div>
    <div class="garis-tipis"></div>

    <div style="text-align: center; margin: 20px 0;">
        <h4 style="margin:0; text-decoration: underline; font-size: 14px;">LAPORAN REKAPITULASI LAYANAN SURAT</h4>
        <p style="margin:5px 0 0 0;">Bulan: {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</p>
    </div>

    @if($suratSelesai->isEmpty())
        <h4 style="text-align: center; margin-top: 50px; color: #555; font-style: italic;">
            - Tidak ada layanan surat pada periode bulan ini -
        </h4>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal Selesai</th>
                    <th width="25%">Nomor Surat</th>
                    <th width="25%">Nama Pemohon</th>
                    <th width="30%">Jenis Surat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suratSelesai as $index => $surat)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $surat->updated_at->format('d/m/Y') }}</td>
                        <td style="text-align: center;">{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>{{ $surat->user->name ?? 'Warga' }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="ttd-table">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;">
                Kamali, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Kepala Desa Buttu Sawe<br><br><br><br><br>
                <strong><u>{{ $kades->name ?? 'NAMA KEPALA DESA' }}</u></strong>
            </td>
        </tr>
    </table>

</body>

</html>