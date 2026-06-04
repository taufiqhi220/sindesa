<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Tidak Mampu</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.25;
            color: black;
        }

        .page-break {
            page-break-before: always;
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

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 12.5pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-surat p {
            margin: 0;
            font-size: 11pt;
        }

        /* TABEL IDENTITAS */
        .tabel-identitas {
            width: 100%;
            margin-left: 30px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 1.5px 0;
        }

        .col-label {
            width: 35%;
        }

        .col-titik {
            width: 3%;
        }

        .teks-paragraf {
            text-align: justify;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .indent-paragraf {
            text-indent: 40px;
        }

        /* AREA TANDA TANGAN */
        .ttd-table {
            width: 100%;
            margin-top: 25px;
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
        $tglLahirKk = isset($data['tanggal_lahir_kk']) ? \Carbon\Carbon::parse($data['tanggal_lahir_kk'])->translatedFormat('d F Y') : '-';
        $tglSurat = $surat->updated_at ? $surat->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');

        $namaKecStr = ucwords(strtolower(str_ireplace('kecamatan ', '', $pengaturan->header_2 ?? 'Duampanua')));
        $namaDesaStr = ucwords(strtolower(str_ireplace('desa ', '', $pengaturan->nama_desa ?? 'Buttu Sawe')));

        $dusunName = '.........';
        $alamatLengkap = $data['alamat'] ?? ($data['alamat_kk'] ?? '');
        if (preg_match('/Dusun\s+([^,]+)/i', $alamatLengkap, $matches)) {
            $dusunName = trim($matches[1]);
        } else {
            if (isset($data['alamat_kk']) && preg_match('/Dusun\s+([^,]+)/i', $data['alamat_kk'], $matches)) {
                $dusunName = trim($matches[1]);
            }
        }
        $dusunKop = strtoupper($dusunName);
        $dusunText = ucwords(strtolower($dusunName));
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
                <h2 style="font-size: 16pt;">DESA {{ strtoupper($namaDesaStr) }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang Tlp. 0421…………………………………….……………KodePos 91253' }}
                </p>
            </td>
            <td class="logo-container"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT KETERANGAN TIDAK MAMPU</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <div class="teks-paragraf indent-paragraf">
        Yang bertanda tangan dibawah ini Kepala Desa {{ $namaDesaStr }} Kecamatan {{ $namaKecStr }} Kabupaten Pinrang
        menerangkan bahwa :
    </div>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir'] ?? '-' }}, {{ $tglLahir }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.2;">{{ $data['alamat'] ?? '-' }}<br>Kecamatan {{ $namaKecStr }}</td>
        </tr>
    </table>

    <div class="teks-paragraf indent-paragraf">
        Adalah Benar Penduduk Desa {{ $namaDesaStr }} Kecamatan {{ $namaKecStr }} Kabupaten Pinrang, dan terdaftar pada
        kartu keluarga dengan nomor {{ $data['no_kk'] ?? '-' }} dengan identitas Kepala Keluarga sebagai berikut :
    </div>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_kepala_keluarga'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_kepala_keluarga'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_kk'] ?? '-' }}, {{ $tglLahirKk }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.2;">{{ $data['alamat_kk'] ?? '-' }}<br>Kec. {{ $namaKecStr }}</td>
        </tr>
    </table>

    <div class="teks-paragraf indent-paragraf">
        Sepengetahuan kami bahwa benar Kepala keluarga yang bersangkutan tergolong tidak mampu.
    </div>

    <div class="teks-paragraf indent-paragraf">
        Adapun Surat Keterangan ini diberikan untuk keperluan
        <strong>{{ strtoupper($data['keperluan'] ?? '-') }}</strong>.
    </div>

    <div class="teks-paragraf indent-paragraf">
        Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
    </div>

    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="width: 60%; font-size: 10pt;">
                Reg. No &nbsp;: ....................................<br>
                Tanggal : ....................................
            </td>
            <td style="width: 40%; text-align: center;"></td>
        </tr>
    </table>

    <table class="ttd-table" style="width: 100%; margin-top: 15px; page-break-inside: avoid;">
        <tr>
            <td style="width: 45%; text-align: center; vertical-align: bottom;">
                <p style="margin: 0;">Mengetahui :</p>
                <p style="margin: 2px 0 0 0;">CAMAT {{ strtoupper($namaKecStr) }}</p>

                <div style="height: 80px;"></div>

                <p style="margin: 0;">.......................................................</p>
                <p style="margin-top: 2px; text-align: left; padding-left: 20%;">Pembina :<br>N I P
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%; text-align: center; vertical-align: top;">
                <p style="margin: 0;">Kamali, {{ $tglSurat }}</p>
                <p style="margin: 2px 0 0 0;">KEPALA DESA {{ strtoupper($namaDesaStr) }}</p>

                @if($surat->metode_ttd == 'digital')
                    <div style="margin: 10px 0 5px 0;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="75"
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

                <p style="margin: 0;"><strong><u>{{ $kades->name ?? 'NAMA KEPALA DESA' }}</u></strong></p>
            </td>
        </tr>
    </table>


    <div class="page-break"></div>

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
                <h2 style="font-size: 14pt;">DESA {{ strtoupper($namaDesaStr) }} DUSUN {{ $dusunKop }}</h2>
                <p>{{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang Tlp. 0421…………………………………….……………KodePos 91253' }}
                </p>
            </td>
            <td class="logo-container"></td>
        </tr>
    </table>

    <div class="judul-surat">
        <h4>SURAT PENGANTAR TIDAK MAMPU</h4>
        <p>Nomor : ....... / ....... / DBS / DP / ....... / 2026</p>
    </div>

    <div class="teks-paragraf indent-paragraf">
        Yang bertanda tangan dibawah ini Kepala Dusun {{ $dusunText }} Kecamatan {{ $namaKecStr }} Kabupaten Pinrang
        menerangkan bahwa :
    </div>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir'] ?? '-' }}, {{ $tglLahir }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.2;">{{ $data['alamat'] ?? '-' }}<br>Kecamatan {{ $namaKecStr }}</td>
        </tr>
    </table>

    <div class="teks-paragraf indent-paragraf">
        Adalah Benar Penduduk Desa {{ $namaDesaStr }} Kecamatan {{ $namaKecStr }} Kabupaten Pinrang, dan terdaftar pada
        kartu keluarga dengan nomor {{ $data['no_kk'] ?? '-' }} dengan identitas Kepala Keluarga sebagai berikut :
    </div>

    <table class="tabel-identitas">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_kepala_keluarga'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_kepala_keluarga'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat dan Tanggal Lahir</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_kk'] ?? '-' }}, {{ $tglLahirKk }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Agama</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_kk'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.2;">{{ $data['alamat_kk'] ?? '-' }}<br>Kec. {{ $namaKecStr }}</td>
        </tr>
    </table>

    <div class="teks-paragraf indent-paragraf">
        Berdasarkan Sepengetahuan kami bahwa benar Kepala keluarga yang bersangkutan tergolong tidak mampu.
    </div>

    <div class="teks-paragraf indent-paragraf">
        Adapun Surat Pengantar ini diberikan untuk mendapatkan Surat Keterangan Tidak Mampu dari Pemerintah Desa
        {{ $namaDesaStr }}.
    </div>

    <div class="teks-paragraf indent-paragraf">
        Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
    </div>

    <table class="ttd-table" style="margin-top: 30px;">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center; vertical-align: bottom;">
                <p style="margin-bottom: 0;">&nbsp;</p>
                <p style="margin-top: 2px; margin-bottom: 0;">KEPALA DUSUN {{ $dusunKop }}</p>
                <div style="height: 80px;"></div>
                <p style="margin-top: 0;">
                    <strong><u>(.......................................................)</u></strong>
                </p>
            </td>
        </tr>
    </table>

</body>

</html>
