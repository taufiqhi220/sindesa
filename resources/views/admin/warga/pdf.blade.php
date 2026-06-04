<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Rekap Data Warga - SINDESA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a5e35;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #1a5e35;
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .header p {
            margin: 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #1a5e35;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }

        .bg-success {
            background-color: #10b981;
        }

        .bg-danger {
            background-color: #ef4444;
        }

        .bg-dark {
            background-color: #4b5563;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>REKAPITULASI DATA WARGA</h1>
        <p>Sinergi Layanan Digital Desa (SINDESA) - Desa Buttu Sawe</p>
        <p><small>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="18%">NIK</th>
                <th width="20%">Nama Lengkap</th>
                <th class="text-center" width="5%">L/P</th>
                <th width="25%">Alamat / Dusun</th>
                <th width="12%">RT/RW</th>
                <th class="text-center" width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wargas as $index => $warga)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    {{-- Cetak NIK langsung, di PDF tidak akan jadi format aneh --}}
                    <td>{{ $warga->nik ?? '-' }}</td>
                    <td><strong>{{ $warga->name }}</strong></td>
                    <td class="text-center">
                        {{ ($warga->jenis_kelamin == 'Perempuan' || $warga->jenis_kelamin == 'P') ? 'P' : 'L' }}</td>

                    {{-- Menampilkan alamat dan rt_rw yang benar sesuai database --}}
                    <td>{{ $warga->alamat_lengkap ?? '-' }}</td>
                    <td>{{ $warga->rt_rw ?? '-' }}</td>

                    <td class="text-center">
                        @if($warga->status == 'active')
                            <span class="badge bg-success">AKTIF</span>
                        @elseif($warga->status == 'suspended')
                            <span class="badge bg-dark">DIBLOKIR</span>
                        @else
                            <span class="badge bg-danger">NON-AKTIF</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data warga terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>