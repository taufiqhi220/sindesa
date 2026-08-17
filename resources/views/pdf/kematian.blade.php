<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Kematian</title>
    <style>
        @page {
            margin: 1cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            /* Dikecilkan sedikit dari 12pt agar muat 1 halaman karena datanya banyak */
            line-height: 1.25;
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
            font-size: 14pt;
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
            font-size: 10pt;
            font-style: italic;
            white-space: nowrap;
        }

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 13pt;
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
            margin-left: 20px;
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 3px 0;
        }

        .col-label {
            width: 40%;
            /* Diperlebar agar teks "Umur pada saat Kematian" tidak turun baris */
        }

        .col-titik {
            width: 3%;
        }

        .teks-paragraf {
            text-align: justify;
            margin-top: 15px;
            margin-bottom: 15px;
            text-indent: 20px;
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
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(75)->margin(0)->generate($qrData));

        \Carbon\Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

        $tglLahirAlm = isset($data['tanggal_lahir_almarhum'])
            ? \Carbon\Carbon::parse($data['tanggal_lahir_almarhum'])->locale('id')->translatedFormat('d F Y')
            : '-';

        $tglKematian = isset($data['tanggal_kematian'])
            ? \Carbon\Carbon::parse($data['tanggal_kematian'])->locale('id')->translatedFormat('l, d F Y')
            : '-';
    @endphp

    <table class="kop-surat">
        <tr>
            <td style="width: 12%; text-align: left;">
                @if(!empty($pengaturan->logo_path))
                    <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" style="width: 70px; height: auto;">
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
        <h4>SURAT KETERANGAN KEMATIAN</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <p style="margin-bottom: 10px;">Yang bertanda tangan dibawah ini menerangkan bahwa :</p>

    <!-- IDENTITAS ALMARHUM -->
    <table class="tabel-identitas">
        <tr>
            <td class="col-label">N a m a</td>
            <td class="col-titik">:</td>
            <td><strong>{{ strtoupper($data['nama_almarhum'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td>{{ $data['jenis_kelamin_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">A l a m a t</td>
            <td class="col-titik">:</td>
            <td style="line-height: 1.4;">{{ $data['alamat_almarhum'] ?? '-' }}<br>Kecamatan Duampanua Kabupaten Pinrang
            </td>
        </tr>
        <tr>
            <td class="col-label">Dilahirkan Tanggal</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_lahir_almarhum'] ?? '-' }}, {{ $tglLahirAlm }}</td>
        </tr>
        <tr>
            <td class="col-label">Tanggal Kematian</td>
            <td class="col-titik">:</td>
            <td>{{ $tglKematian }}</td>
        </tr>
        <tr>
            <td class="col-label">Umur pada saat Kematian</td>
            <td class="col-titik">:</td>
            <td>{{ $data['umur_kematian'] ?? '-' }} Tahun</td>
        </tr>
        <tr>
            <td class="col-label">Kewarganegaraan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['kewarganegaraan_almarhum'] ?? 'Indonesia' }}</td>
        </tr>
        <tr>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td>{{ $data['agama_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Status Perkawinan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['status_perkawinan_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td>{{ $data['pekerjaan_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tempat Kematian</td>
            <td class="col-titik">:</td>
            <td>{{ $data['tempat_kematian'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Desa</td>
            <td class="col-titik">:</td>
            <td>{{ ucwords(strtolower(str_replace('DESA ', '', $pengaturan->nama_desa ?? 'Buttu Sawe'))) }}</td>
        </tr>
        <tr>
            <td class="col-label">Kecamatan</td>
            <td class="col-titik">:</td>
            <td>{{ ucwords(strtolower(str_replace('KECAMATAN ', '', $pengaturan->header_2 ?? 'Duampanua'))) }}</td>
        </tr>
        <tr>
            <td class="col-label">Kabupaten</td>
            <td class="col-titik">:</td>
            <td>Pinrang</td>
        </tr>
        <tr>
            <td class="col-label">Sebab Kematian</td>
            <td class="col-titik">:</td>
            <td>{{ $data['sebab_kematian'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">No. Kartu Keluarga</td>
            <td class="col-titik">:</td>
            <td>{{ $data['kk_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">No. K T P</td>
            <td class="col-titik">:</td>
            <td>{{ $data['nik_almarhum'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Nama yang Melapor</td>
            <td class="col-titik">:</td>
            <td>{{ strtoupper($data['nama_pelapor'] ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Hub. Dengan yang Mati</td>
            <td class="col-titik">:</td>
            <td>{{ $data['hubungan_pelapor'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="teks-paragraf">
        Keterangan ini dibuat atas dasar yang sebenarnya. (Laporan Dari
        {{ ucwords(strtolower($data['hubungan_pelapor'] ?? '-')) }})
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
