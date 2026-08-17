<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Pindah</title>
    <style>
        @page {
            margin: 1cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.25;
            color: black;
        }

        /* KOP SURAT PRESISI TENGAH */
        .kop-surat {
            width: 100%;
            border-bottom: 4px double black;
            padding-bottom: 10px;
            margin-bottom: 15px;
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
            white-space: nowrap;
        }

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
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
            border-collapse: collapse;
        }

        .tabel-identitas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .col-num {
            width: 5%;
            text-align: right;
            padding-right: 5px !important;
        }

        .col-label {
            width: 30%;
        }

        .col-titik {
            width: 2%;
        }

        .col-value {
            width: 63%;
        }

        /* TABEL PENGIKUT */
        .tabel-pengikut {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10pt;
            text-align: center;
        }

        .tabel-pengikut th,
        .tabel-pengikut td {
            border: 1px solid black;
            padding: 4px;
        }

        /* AREA TANDA TANGAN */
        .ttd-table {
            width: 100%;
            margin-top: 20px;
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
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->margin(0)->generate($qrData));

        \Carbon\Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

        $tglLahir = isset($data['tanggal_lahir']) ? \Carbon\Carbon::parse($data['tanggal_lahir'])->translatedFormat('d F Y') : '-';
        $tglPindah = isset($data['tanggal_pindah']) ? \Carbon\Carbon::parse($data['tanggal_pindah'])->translatedFormat('d F Y') : '-';
        $tglSurat = $surat->updated_at ? $surat->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');

        // Mencegah nama ganda "Kecamatan Kecamatan"
        $namaDesaStr = ucwords(strtolower(str_ireplace('desa ', '', $pengaturan->nama_desa ?? 'Buttu Sawe')));
        $namaKecStr = ucwords(strtolower(str_ireplace('kecamatan ', '', $pengaturan->header_2 ?? 'Duampanua')));

        // Mengurai Data Anggota Keluarga Baru (Sistem Array Dynamic)
        $pengikut = [];
        $oldAnggota = $data['anggota_keluarga'] ?? [];
        $p_nama = $data['pengikut_nama'] ?? array_column($oldAnggota, 'nama');
        $p_nik = $data['pengikut_nik'] ?? array_column($oldAnggota, 'nik');
        $p_jk = $data['pengikut_jk'] ?? array_column($oldAnggota, 'jenis_kelamin');
        $p_tgl = $data['pengikut_tgl_lahir'] ?? array_column($oldAnggota, 'tanggal_lahir');
        $p_status = $data['pengikut_status'] ?? array_column($oldAnggota, 'status_perkawinan');
        $p_ket = $data['pengikut_ket'] ?? array_column($oldAnggota, 'keterangan');

        if (is_array($p_nama) && count($p_nama) > 0) {
            for ($i = 0; $i < count($p_nama); $i++) {
                $jk = strtolower(trim($p_jk[$i] ?? ''));
                $tglLahirPengikut = !empty($p_tgl[$i]) ? \Carbon\Carbon::parse($p_tgl[$i])->translatedFormat('d F Y') : '-';

                $pengikut[] = [
                    'nama' => $p_nama[$i] ?? '-',
                    'nik' => $p_nik[$i] ?? '-',
                    'jk_L' => ($jk === 'laki-laki' || $jk === 'l') ? 'L' : '',
                    'jk_P' => ($jk === 'perempuan' || $jk === 'p') ? 'P' : '',
                    'tgl_lahir' => $tglLahirPengikut,
                    'status' => $p_status[$i] ?? '-',
                    'ket' => $p_ket[$i] ?? '-'
                ];
            }
        }
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
        <h4>SURAT KETERANGAN PINDAH</h4>
        <p>Nomor : {{ $surat->nomor_surat ?? '......./DBS/DP/......./2026' }}</p>
    </div>

    <table class="tabel-identitas">
        <tr>
            <td class="col-num">1.</td>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-titik">:</td>
            <td class="col-value"><strong>{{ strtoupper($data['nama'] ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="col-num">2.</td>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['jenis_kelamin'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">3.</td>
            <td class="col-label">Dilahirkan</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['tempat_lahir'] ?? '-' }}, {{ $tglLahir }}</td>
        </tr>
        <tr>
            <td class="col-num">4.</td>
            <td class="col-label">Kewarganegaraan</td>
            <td class="col-titik">:</td>
            <td class="col-value">Indonesia</td>
        </tr>
        <tr>
            <td class="col-num">5.</td>
            <td class="col-label">A g a m a</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['agama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">6.</td>
            <td class="col-label">Status Perkawinan</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['status_perkawinan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">7.</td>
            <td class="col-label">Pekerjaan</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">8.</td>
            <td class="col-label">Pendidikan</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['pendidikan'] ?? '-' }}</td>
        </tr>

        <tr>
            <td class="col-num">9.</td>
            <td class="col-label">Alamat Asal</td>
            <td class="col-titik">:</td>
            <td class="col-value" style="line-height: 1.4;">
                {{ $data['alamat_asal_dusun'] ?? ($data['alamat_asal']['dusun'] ?? '-') }} RT/RW
                {{ str_pad($data['alamat_asal_rt'] ?? ($data['alamat_asal']['rt'] ?? '0'), 3, '0', STR_PAD_LEFT) }}/{{ str_pad($data['alamat_asal_rw'] ?? ($data['alamat_asal']['rw'] ?? '0'), 3, '0', STR_PAD_LEFT) }}<br>
                Desa {{ $namaDesaStr }}, Kecamatan {{ $namaKecStr }},<br>
                Kabupaten Pinrang, Provinsi Sulawesi Selatan.
            </td>
        </tr>
        <tr>
            <td class="col-num">10.</td>
            <td class="col-label">No. dan Tanggal KTP</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['nik'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">11.</td>
            <td class="col-label">No. Kartu Keluarga</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['no_kk'] ?? '-' }}</td>
        </tr>

        <tr>
            <td class="col-num">12.</td>
            <td class="col-label">Pindah Ke</td>
            <td class="col-titik">:</td>
            <td class="col-value">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 35%; padding:0;">Jalan / Alamat</td>
                        <td style="width: 3%; padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_jalan'] ?? ($data['alamat_tujuan']['jalan'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">RT / RW</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ str_pad($data['alamat_tujuan_rt'] ?? ($data['alamat_tujuan']['rt'] ?? '0'), 3, '0', STR_PAD_LEFT) }} /
                            {{ str_pad($data['alamat_tujuan_rw'] ?? ($data['alamat_tujuan']['rw'] ?? '0'), 3, '0', STR_PAD_LEFT) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Desa / Kelurahan</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_desa'] ?? ($data['alamat_tujuan']['desa'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Kecamatan</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_kecamatan'] ?? ($data['alamat_tujuan']['kecamatan'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Kab / Kota</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_kabupaten'] ?? ($data['alamat_tujuan']['kabupaten'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Provinsi</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_provinsi'] ?? ($data['alamat_tujuan']['provinsi'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Kode Pos</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $data['alamat_tujuan_kodepos'] ?? ($data['alamat_tujuan']['kode_pos'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0;">Pada Tanggal</td>
                        <td style="padding:0;">:</td>
                        <td style="padding:0;">{{ $tglPindah }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="col-num">13.</td>
            <td class="col-label">Alasan Pindah</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $data['alasan_pindah'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-num">14.</td>
            <td class="col-label">Pengikut</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ count($pengikut) > 0 ? count($pengikut) . ' Orang' : '-' }}</td>
        </tr>
    </table>

    <table class="tabel-pengikut">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No.</th>
                <th rowspan="2" style="width: 24%;">Nama</th>
                <th colspan="2" style="width: 12%;">Kelamin</th>
                <th rowspan="2" style="width: 14%;">Tanggal Lahir</th>
                <th rowspan="2" style="width: 15%;">Status Perkawinan</th>
                <th rowspan="2" style="width: 16%;">No. KTP / NIK</th>
                <th rowspan="2" style="width: 14%;">Ket</th>
            </tr>
            <tr>
                <th>L</th>
                <th>P</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pengikut) > 0)
                @foreach($pengikut as $index => $orang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left;">{{ $orang['nama'] }}</td>
                        <td>{{ $orang['jk_L'] }}</td>
                        <td>{{ $orang['jk_P'] }}</td>
                        <td>{{ $orang['tgl_lahir'] }}</td>
                        <td>{{ $orang['status'] }}</td>
                        <td>{{ $orang['nik'] }}</td>
                        <td>{{ $orang['ket'] }}</td>
                    </tr>
                @endforeach

                @for($i = count($pengikut) + 1; $i <= 3; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @else
                <tr>
                    <td>1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

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
    <p style="font-size: 9pt; margin-top: -10px;"><i>*) Coret yang tidak sesuai</i></p>

</body>

</html>
