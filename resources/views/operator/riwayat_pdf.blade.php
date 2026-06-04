<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi SINDESA</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
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
            width: 75px;
            height: auto;
        }

        .logo-kanan {
            width: 90px;
            height: auto;
        }

        .judul-kop {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .judul-desa {
            font-size: 20px;
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

        /* CSS Garis */
        .garis-tebal {
            border-bottom: 3px solid black;
            margin-top: 5px;
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
            padding: 6px;
            text-align: left;
        }

        table.data th {
            background-color: #e2e8f0;
            text-align: center;
        }

        h3 {
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            background: #f8fafc;
            display: inline-block;
            padding: 4px 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    @php
        $pengaturan = \App\Models\PengaturanSurat::first();

        // 1. Definisikan array untuk menampung surat berdasarkan kategori
        $suratDikategorikan = [
            'Kependudukan' => collect(),
            'Sosial & Ekonomi' => collect(),
            'Perizinan' => collect(),
            'Keterangan Umum' => collect()
        ];

        // 2. Klasifikasikan jenis surat (sama dengan logika di view riwayat)
        $kependudukan = ['pengantar_akta_lahir', 'pengantar_ktp', 'pengantar_kk', 'keterangan_kematian', 'keterangan_pindah', 'keterangan_domisili'];
        $sosial_ekonomi = ['keterangan_tidak_mampu', 'keterangan_penghasilan', 'keterangan_janda_duda'];
        $perizinan = ['keterangan_usaha', 'izin_keramaian', 'keterangan_kehilangan'];

        // 3. Masukkan data ke kategori masing-masing. (asumsi data asli ada di $surats)
        // Jika Controller sudah mengirim $surats (kumpulan semua surat), kita gunakan itu.
        // Jika hanya ada $rekap, kita perlu flatten dulu (menggabungkan semua isinya).
        $semuaSurat = collect();
        if (isset($surats)) {
            $semuaSurat = $surats;
        } elseif (isset($rekap)) {
            foreach ($rekap as $items) {
                $semuaSurat = $semuaSurat->merge($items);
            }
        }

        foreach ($semuaSurat as $surat) {
            if (in_array($surat->jenis_surat, $kependudukan)) {
                $suratDikategorikan['Kependudukan']->push($surat);
            } elseif (in_array($surat->jenis_surat, $sosial_ekonomi)) {
                $suratDikategorikan['Sosial & Ekonomi']->push($surat);
            } elseif (in_array($surat->jenis_surat, $perizinan)) {
                $suratDikategorikan['Perizinan']->push($surat);
            } else {
                $suratDikategorikan['Keterangan Umum']->push($surat);
            }
        }
    @endphp

    <table class="kop-surat">
        <tr>
            <td width="15%">
                @if($pengaturan && $pengaturan->logo_path)
                    <img src="{{ public_path('storage/' . $pengaturan->logo_path) }}" class="logo" alt="Logo Daerah">
                @else
                    <img src="{{ public_path('image/logo-pinrang.png') }}" class="logo" alt="Logo Default">
                @endif
            </td>

            <td width="70%">
                <p class="judul-kop">{{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}</p>
                <p class="judul-kop">{{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}</p>
                <p class="judul-desa">{{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}</p>
                <p class="alamat-kop">
                    {{ $pengaturan->alamat ?? 'Jalan Poros Kamali Rajang, Desa Buttu Sawe Tlp. 0421.......................Kode Pos 91253' }}
                </p>
            </td>

            <td width="15%">
                <img src="{{ public_path('image/SINDESA_BLACK_TRANSPARNT.png') }}" class="logo-kanan"
                    alt="Logo SINDESA">
            </td>
        </tr>
    </table>

    <div class="garis-tebal"></div>
    <div class="garis-tipis"></div>

    <div style="text-align: center; margin: 20px 0;">
        <h4 style="margin:0; text-decoration: underline; font-size: 14px;">LAPORAN REKAPITULASI LAYANAN SURAT</h4>
        <p style="margin:5px 0 0 0;">Periode: {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
        </p>
    </div>

    @if($semuaSurat->isEmpty())
        <h4 style="text-align: center; margin-top: 50px; color: #555; font-style: italic;">
            - Tidak ada layanan surat pada periode ini -
        </h4>
    @else
        @foreach($suratDikategorikan as $kategori => $items)
            @if($items->count() > 0)
                <h3>KATEGORI: {{ $kategori }}</h3>
                <table class="data">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Tanggal Selesai</th>
                            <th width="35%">Nama Warga</th>
                            <th width="40%">Jenis Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $surat)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td style="text-align: center;">{{ $surat->updated_at->format('d/m/Y') }}</td>
                                <td>{{ $surat->user->name }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

</body>

</html>