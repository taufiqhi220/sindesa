<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Riwayat Tanda Tangan - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5 shrink-0">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
                        class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'H', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'H. Burhanuddin' }}</h4>
                <p class="text-[10px] opacity-70">Kepala Desa</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.dashboard') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.perlu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-signature w-5 text-center"></i> Perlu Tanda Tangan
                </div>
                @if(isset($unreadCountKades) && $unreadCountKades > 0) <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">{{ $unreadCountKades }}</span> @endif
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 lg:ml-[280px] overflow-x-hidden min-h-screen">

        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Riwayat Tanda Tangan</h2>
            <p class="text-gray-500 text-sm">Daftar seluruh dokumen yang telah selesai Anda tandatangani dan
                diterbitkan.</p>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-center">
            <span class="text-sm font-semibold text-gray-600 mr-2"><i class="fas fa-info-circle mr-1"></i> Kategori
                Layanan:</span>
            <span
                class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-md text-xs font-bold">Kependudukan
                (Biru)</span>
            <span
                class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-md text-xs font-bold">Sosial
                & Ekonomi (Hijau)</span>
            <span
                class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-md text-xs font-bold">Perizinan
                (Oranye)</span>
            <span
                class="bg-purple-50 text-purple-600 border border-purple-100 px-3 py-1 rounded-md text-xs font-bold">Keterangan
                Umum (Ungu)</span>
        </div>

        <div
            class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100 flex flex-col">

            <div
                class="p-6 border-b border-[#e0e0e0] flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white">
                <div class="text-xl font-semibold text-gray-800">Arsip Dokumen</div>

                <div class="flex flex-wrap sm:flex-row items-center gap-4 w-full lg:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 hidden sm:block">Tampilkan</span>
                        <select id="itemsPerPage"
                            class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-[#1a5e35]">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 border-l border-gray-200 pl-4">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                        <select id="filterBulan"
                            onchange="window.location.href='{{ route('kades.riwayat') }}?bulan=' + this.value"
                            class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-[#1a5e35] bg-white">
                            <option value="">Semua Waktu</option>
                            @for ($i = 0; $i < 6; $i++)
                                @php $date = \Carbon\Carbon::now()->startOfMonth()->subMonths($i); @endphp
                                <option value="{{ $date->format('Y-m') }}" {{ request('bulan') == $date->format('Y-m') ? 'selected' : '' }}>
                                    {{ $date->translatedFormat('F Y') }}
                                </option>
                            @endfor
                        </select>

                        <select id="orientasiLaporan" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-[#1a5e35] bg-white ml-2">
                            <option value="landscape">Landscape</option>
                            <option value="portrait">Portrait</option>
                        </select>
                        <button onclick="cetakLaporanBulan()"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 ml-2 shadow-sm">
                            <i class="fas fa-file-pdf"></i> Cetak Rekap
                        </button>
                    </div>

                    <div class="relative w-full sm:w-auto mt-2 sm:mt-0">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari arsip..."
                            class="w-full sm:w-[200px] focus:w-[250px] pl-11 pr-4 py-2 border border-gray-300 rounded-full outline-none focus:border-[#1a5e35] focus:ring-1 focus:ring-[#1a5e35] transition-all text-sm">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 select-none">
                        <tr>
                            <th
                                class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[5%] text-center">
                                No</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[20%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="tanggal" onclick="sortData('tanggal', this)">
                                <div class="flex items-center">Tanggal Selesai <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[25%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="pemohon" onclick="sortData('pemohon', this)">
                                <div class="flex items-center">Nama Pemohon <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[25%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="jenis" onclick="sortData('jenis', this)">
                                <div class="flex items-center">Jenis Layanan <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th
                                class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[15%] text-center">
                                Metode TTD</th>
                            <th
                                class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[10%] text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-[0.95rem] text-gray-800">
                        @forelse($riwayatSurat as $index => $surat)
                            @php
                                $adminduk = ['pengantar_akta_lahir', 'pengantar_ktp', 'pengantar_kk', 'keterangan_kematian', 'keterangan_pindah'];
                                $sosial = ['keterangan_tidak_mampu', 'keterangan_penghasilan'];
                                $perizinan = ['keterangan_usaha', 'izin_keramaian', 'keterangan_kehilangan'];

                                if (in_array($surat->jenis_surat, $adminduk)) {
                                    $colorClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                                } elseif (in_array($surat->jenis_surat, $sosial)) {
                                    $colorClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                } elseif (in_array($surat->jenis_surat, $perizinan)) {
                                    $colorClass = 'bg-orange-50 text-orange-600 border border-orange-100';
                                } else {
                                    $colorClass = 'bg-purple-50 text-purple-600 border border-purple-100';
                                }

                                $namaSuratLengkap = [
                                    'pengantar_akta_lahir' => 'Surat Pengantar Akta Lahir',
                                    'pengantar_ktp' => 'Surat Pengantar KTP',
                                    'pengantar_kk' => 'Surat Pengantar Kartu Keluarga (KK)',
                                    'keterangan_kematian' => 'Surat Keterangan Kematian',
                                    'keterangan_pindah' => 'Surat Keterangan Pindah Domisili',
                                    'keterangan_usaha' => 'Surat Keterangan Usaha (SKU)',
                                    'izin_keramaian' => 'Surat Izin Keramaian',
                                    'keterangan_kehilangan' => 'Surat Keterangan Kehilangan',
                                    'keterangan_tidak_mampu' => 'Surat Keterangan Tidak Mampu (SKTM)',
                                    'keterangan_penghasilan' => 'Surat Keterangan Penghasilan',
                                    'keterangan_beda_nama' => 'Surat Keterangan Beda Nama Identitas',
                                    'keterangan_belum_menikah' => 'Surat Keterangan Belum Menikah',
                                    'keterangan_janda_duda' => 'Surat Keterangan Status Janda/Duda',
                                    'pengantar_skck' => 'Surat Pengantar SKCK',
                                    'keterangan_domisili' => 'Surat Keterangan Domisili'
                                ];
                            @endphp

                            <tr
                                class="data-row hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-5 py-4 index-cell text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 col-tanggal" data-value="{{ $surat->updated_at->timestamp }}">
                                    <div class="font-semibold text-gray-700">
                                        {{ $surat->updated_at->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-400">{{ $surat->updated_at->format('H:i') }} WITA
                                    </div>
                                </td>
                                <td class="px-5 py-4 col-pemohon">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 flex justify-center items-center font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($surat->user->name ?? 'W', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $surat->user->name ?? 'Warga' }}</div>
                                            <div class="text-[11px] text-gray-400 font-mono italic">
                                                {{ $surat->user->nik ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 col-jenis">
                                    <span
                                        class="{{ $colorClass }} px-3 py-1.5 rounded-md text-xs font-bold inline-flex max-w-[220px] whitespace-normal leading-snug">
                                        {{ $namaSuratLengkap[$surat->jenis_surat] ?? ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $metode = strtolower(trim($surat->metode_ttd));
                                    @endphp

                                    @if($metode === 'digital')
                                        <span
                                            class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5"
                                            title="Tanda Tangan QR Code">
                                            <i class="fas fa-qrcode"></i> QR Code
                                        </span>
                                    @elseif($metode === 'konvensional' || $metode === 'png' || $metode === 'gambar')
                                        <span
                                            class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5"
                                            title="Tanda Tangan Gambar (PNG)">
                                            <i class="fas fa-signature"></i> Gambar
                                        </span>
                                    @else
                                        <span
                                            class="bg-gray-50 text-gray-600 border border-gray-200 px-3 py-1.5 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5"
                                            title="Kosong / Tanda Tangan Basah">
                                            <i class="fas fa-pen-alt"></i> TTD Basah
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('kades.surat.detail', ['type' => $surat->jenis_surat, 'id' => $surat->id]) }}"
                                            class="w-9 h-9 bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 hover:text-gray-800 rounded-lg flex justify-center items-center transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('kades.surat.cetak', $surat->id) }}" target="_blank"
                                            class="w-9 h-9 bg-white border border-[#1a5e35] text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white rounded-lg flex justify-center items-center transition-all shadow-sm"
                                            title="Unduh PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyServerState">
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-folder-open text-4xl mb-3 block opacity-20"></i>
                                    Belum ada riwayat arsip yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="p-4 border-t border-[#e0e0e0] flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 rounded-b-2xl">
                <div class="text-sm text-gray-600 font-bold tracking-wide" id="pageInfo">Menampilkan 0 data</div>

                <div id="paginationControls" class="flex gap-1"></div>
            </div>

        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        /* --- SCRIPT PENCARIAN & PAGINATION --- */
        let originalRows = [];
        let filteredRows = [];
        let currentPage = 1;
        let itemsPerPage = 5;
        let currentSortColumn = 'tanggal';
        let isAscending = false;

        document.addEventListener('DOMContentLoaded', function () {
            originalRows = Array.from(document.querySelectorAll('#tableBody tr.data-row'));
            filteredRows = [...originalRows];

            const itemsPerPageSelect = document.getElementById('itemsPerPage');
            const searchInput = document.getElementById('searchInput');

            // Baris kosong buatan JS
            let jsEmptyRow = document.createElement('tr');
            jsEmptyRow.id = 'jsEmptyRow';
            jsEmptyRow.innerHTML = '<td colspan="6" class="px-5 py-8 text-center text-gray-500"><i class="fas fa-search text-3xl mb-2 text-gray-300 block"></i>Data tidak ditemukan.</td>';
            jsEmptyRow.style.display = 'none';
            document.getElementById('tableBody').appendChild(jsEmptyRow);

            if (originalRows.length > 0) {
                applySort();
            }

            itemsPerPageSelect.addEventListener('change', function () {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                filteredRows = originalRows.filter(row => row.textContent.toLowerCase().includes(query));
                applySort();
            });
        });

        function sortData(column, thElement) {
            if (originalRows.length === 0) return;

            if (currentSortColumn === column) {
                isAscending = !isAscending;
            } else {
                currentSortColumn = column;
                isAscending = true;
            }

            document.querySelectorAll('th[data-sort]').forEach(th => {
                th.classList.remove('active-header');
                const icon = th.querySelector('.sort-icon');
                if (icon) icon.className = 'fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon';
            });

            if (thElement) {
                thElement.classList.add('active-header');
                const activeIcon = thElement.querySelector('.sort-icon');
                if (activeIcon) {
                    activeIcon.classList.remove('text-gray-400');
                    activeIcon.classList.add('text-[#1a5e35]');
                    if (isAscending) activeIcon.classList.add('rotate-180');
                }
            }

            applySort();
        }

        function applySort() {
            if (originalRows.length === 0) return;

            filteredRows.sort((a, b) => {
                let valA, valB;
                if (currentSortColumn === 'tanggal') {
                    valA = parseInt(a.querySelector('.col-tanggal').dataset.value) || 0;
                    valB = parseInt(b.querySelector('.col-tanggal').dataset.value) || 0;
                } else if (currentSortColumn === 'pemohon') {
                    valA = a.querySelector('.col-pemohon').textContent.trim().toLowerCase();
                    valB = b.querySelector('.col-pemohon').textContent.trim().toLowerCase();
                } else if (currentSortColumn === 'jenis') {
                    valA = a.querySelector('.col-jenis').textContent.trim().toLowerCase();
                    valB = b.querySelector('.col-jenis').textContent.trim().toLowerCase();
                }

                if (valA < valB) return isAscending ? -1 : 1;
                if (valA > valB) return isAscending ? 1 : -1;
                return 0;
            });
            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            if (originalRows.length === 0) return;

            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            const tableBody = document.getElementById('tableBody');

            originalRows.forEach(row => { row.style.display = 'none'; });

            let counter = start + 1;
            filteredRows.forEach((row, index) => {
                if (index >= start && index < end) {
                    row.style.display = '';
                    row.querySelector('.index-cell').textContent = counter++;
                    tableBody.appendChild(row);
                }
            });

            const jsEmptyRow = document.getElementById('jsEmptyRow');
            if (totalItems === 0) {
                jsEmptyRow.style.display = '';
                tableBody.appendChild(jsEmptyRow);
            } else {
                jsEmptyRow.style.display = 'none';
            }

            let actualEnd = start + itemsPerPage;
            if (actualEnd > totalItems) actualEnd = totalItems;
            updatePaginationControls(totalItems, totalPages, start, actualEnd);
        }

        function updatePaginationControls(totalItems, totalPages, start, end) {
            const paginationControls = document.getElementById('paginationControls');
            const pageInfo = document.getElementById('pageInfo');

            if (totalItems === 0) {
                pageInfo.textContent = "Menampilkan 0 data pencarian";
            } else {
                pageInfo.textContent = `Menampilkan ${start + 1} HINGGA ${end} DARI ${totalItems} DATA (Halaman Ini)`;
            }

            paginationControls.innerHTML = '';
            if (totalPages > 1) {
                let prevBtn = document.createElement('button');
                prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                prevBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-sm transition-all ${currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-[#1a5e35] hover:bg-[#1a5e35]/10'}`;
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => { currentPage--; renderTable(); };
                paginationControls.appendChild(prevBtn);

                for (let i = 1; i <= totalPages; i++) {
                    let pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all ${currentPage === i ? 'bg-[#1a5e35] text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'}`;
                    pageBtn.onclick = () => { currentPage = i; renderTable(); };
                    paginationControls.appendChild(pageBtn);
                }

                let nextBtn = document.createElement('button');
                nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                nextBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-sm transition-all ${currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-[#1a5e35] hover:bg-[#1a5e35]/10'}`;
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.onclick = () => { currentPage++; renderTable(); };
                paginationControls.appendChild(nextBtn);
            }
        }

        function cetakLaporanBulan() {
            const bulan = document.getElementById('filterBulan').value;
            const orientasi = document.getElementById('orientasiLaporan').value;
            window.open(`{{ route('kades.riwayat.laporan') }}?bulan=${bulan}&orientasi=${orientasi}`, '_blank');
        }
    </script>
</body>

</html>