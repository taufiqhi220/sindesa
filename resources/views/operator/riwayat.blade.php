<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Riwayat Surat - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

        <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
            <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
                class="h-10 mx-auto block object-contain">
        </div>

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 shrink-0">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
                        class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Operator' }}</h4>
                <p class="text-[10px] opacity-70">Petugas Verifikator</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto">
            <a href="{{ route('operator.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.dashboard') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.verifikasi*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-inbox w-5 text-center"></i> Verifikasi Masuk
                </div>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('operator.menunggu-ttd') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.menunggu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>

            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.ditolak*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-times-circle w-5 text-center"></i> Surat Ditolak
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('operator.pengaturan-surat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-surat') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-contract w-5 text-center"></i> Pengaturan Surat
            </a>

            <a href="{{ route('operator.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
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
        
        <!-- HEADER MOBILE -->
        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-[#1a5e35]">Arsip & Laporan</h2>
                <p class="text-gray-500 text-sm mt-1">Rekapitulasi dan riwayat surat yang telah selesai.</p>
            </div>
            <a href="{{ route('operator.riwayat.cetak', ['start_date' => $start, 'end_date' => $end]) }}"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all text-sm font-semibold">
                <i class="fas fa-file-pdf"></i> Cetak Laporan (PDF)
            </a>
        </div>

        <!-- FILTER & TABEL -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col border border-gray-100">
            <div class="p-6 border-b border-[#e0e0e0] flex flex-col xl:flex-row justify-between items-center gap-4">
                
                <!-- Filter Tanggal -->
                <form action="{{ route('operator.riwayat') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ $start }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#1a5e35] outline-none">
                        <span class="text-gray-400 text-xs">s/d</span>
                        <input type="date" name="end_date" value="{{ $end }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-[#1a5e35] outline-none">
                    </div>
                    <button type="submit"
                        class="bg-[#1a5e35] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#11442b] transition-all">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </form>

                <!-- Pencarian & Items Per Page -->
                <div class="flex flex-wrap items-center gap-4 w-full xl:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Tampilkan</span>
                        <select id="itemsPerPage" class="border border-gray-300 rounded-lg px-2 py-2 text-xs focus:ring-1 focus:ring-[#1a5e35] outline-none bg-white">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-xs text-gray-500">data</span>
                    </div>

                    <div class="relative w-full sm:w-auto flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="searchInput" placeholder="Cari di tabel..."
                            class="w-full sm:w-[250px] pl-10 pr-4 py-2 border border-gray-300 rounded-full outline-none focus:border-[#1a5e35] text-xs">
                    </div>
                </div>
            </div>

            <!-- Keterangan Kategori -->
            <div class="flex flex-wrap gap-4 px-6 pt-4 pb-2">
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-blue-600"><span class="w-3 h-3 rounded-full bg-blue-100 border border-blue-200"></span> Kependudukan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-600"><span class="w-3 h-3 rounded-full bg-emerald-100 border border-emerald-200"></span> Sosial & Ekonomi</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-orange-600"><span class="w-3 h-3 rounded-full bg-orange-100 border border-orange-200"></span> Perizinan</div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold text-purple-600"><span class="w-3 h-3 rounded-full bg-purple-100 border border-purple-200"></span> Keterangan Umum</div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 select-none">
                        <tr>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[5%] text-center">No</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[20%] cursor-pointer hover:bg-gray-200 transition-colors" data-sort="tanggal" onclick="handleSort('tanggal', this)">
                                <div class="flex items-center">Tanggal Selesai <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[20%]">Nomor Surat</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[25%] cursor-pointer hover:bg-gray-200 transition-colors" data-sort="pemohon" onclick="handleSort('pemohon', this)">
                                <div class="flex items-center">Nama Pemohon <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[20%] cursor-pointer hover:bg-gray-200 transition-colors" data-sort="jenis" onclick="handleSort('jenis', this)">
                                <div class="flex items-center">Jenis Layanan <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[10%] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-[0.95rem] text-gray-800">
                        @forelse($surats as $index => $surat)
                            @php
                                // Klasifikasi Surat
                                $kependudukan = ['pengantar_akta_lahir', 'pengantar_ktp', 'pengantar_kk', 'keterangan_kematian', 'keterangan_pindah', 'keterangan_domisili'];
                                $sosial_ekonomi = ['keterangan_tidak_mampu', 'keterangan_penghasilan', 'keterangan_janda_duda'];
                                $perizinan = ['keterangan_usaha', 'izin_keramaian', 'keterangan_kehilangan'];

                                if (in_array($surat->jenis_surat, $kependudukan)) {
                                    $colorClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                                } elseif (in_array($surat->jenis_surat, $sosial_ekonomi)) {
                                    $colorClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                } elseif (in_array($surat->jenis_surat, $perizinan)) {
                                    $colorClass = 'bg-orange-50 text-orange-600 border border-orange-100';
                                } else {
                                    $colorClass = 'bg-purple-50 text-purple-600 border border-purple-100';
                                }
                            @endphp
                            <tr class="data-row hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-5 py-4 index-cell text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 col-tanggal" data-value="{{ $surat->updated_at->timestamp }}">
                                    <div class="font-semibold text-gray-700">{{ $surat->updated_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $surat->updated_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-mono text-xs font-bold text-gray-600">{{ $surat->nomor_surat ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4 col-pemohon">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#1a5e35]/10 text-[#1a5e35] flex justify-center items-center font-bold text-xs shrink-0">
                                            {{ substr($surat->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="font-bold text-gray-800">{{ $surat->user->name ?? 'Tanpa Nama' }}</div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 col-jenis">
                                    <span class="{{ $colorClass }} px-3 py-1.5 rounded-md text-[11px] font-bold inline-flex leading-snug">
                                        SURAT {{ strtoupper(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('operator.verifikasi.show', $surat->id) }}"
                                            class="w-9 h-9 bg-white border border-gray-200 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg flex justify-center items-center transition-all shadow-sm"
                                            title="Lihat Arsip">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyServerState">
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                    <i class="fas fa-archive text-3xl mb-2 text-gray-300 block"></i>
                                    Belum ada surat yang selesai diproses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#e0e0e0] flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 rounded-b-2xl">
                <div class="text-sm text-gray-600 font-bold tracking-wide" id="pageInfo">Menampilkan 0 data</div>
                <div class="flex gap-1" id="paginationControls"></div>
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

        // Variabel Global
        let originalRows = [];
        let filteredRows = [];
        let currentPage = 1;
        let itemsPerPage = 5; // Default diubah menjadi 5
        let currentSortColumn = 'tanggal';
        let isAscending = false; // Default: Data terbaru di atas

        document.addEventListener('DOMContentLoaded', function () {
            originalRows = Array.from(document.querySelectorAll('#tableBody tr.data-row'));
            filteredRows = [...originalRows];
            
            const itemsPerPageSelect = document.getElementById('itemsPerPage');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');

            // Set default items per page dropdown
            itemsPerPageSelect.value = "5";

            // Baris "Data tidak ditemukan" (untuk search)
            let noResultRow = document.createElement('tr');
            noResultRow.id = 'noResultRow';
            noResultRow.innerHTML = '<td colspan="6" class="px-5 py-12 text-center text-gray-500"><i class="fas fa-search text-3xl mb-2 text-gray-300 block"></i>Arsip tidak ditemukan.</td>';
            noResultRow.style.display = 'none';
            tableBody.appendChild(noResultRow);

            // Trigger sort pertama kali saat halaman dimuat
            const defaultSortHeader = document.querySelector('[data-sort="tanggal"]');
            if(defaultSortHeader && originalRows.length > 0) {
                updateSortVisuals(defaultSortHeader);
                applySortAndFilter();
            }

            // Event Listener Ganti Jumlah Tampil
            itemsPerPageSelect.addEventListener('change', function () {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                applySortAndFilter();
            });

            // Event Listener Pencarian
            searchInput.addEventListener('input', function () {
                currentPage = 1;
                applySortAndFilter();
            });
        });

        function handleSort(column, thElement) {
            if (currentSortColumn === column) {
                isAscending = !isAscending; // Balik arah sort jika kolom sama
            } else {
                currentSortColumn = column;
                isAscending = true; // Default A-Z atau Terlama jika kolom baru
            }
            updateSortVisuals(thElement);
            applySortAndFilter();
        }

        function updateSortVisuals(activeTh) {
            // Reset semua icon
            document.querySelectorAll('th[data-sort]').forEach(th => {
                th.classList.remove('active-header');
                const icon = th.querySelector('.sort-icon');
                if (icon) icon.classList.remove('text-[#1a5e35]', 'rotate-180');
            });

            // Set icon aktif
            activeTh.classList.add('active-header');
            const activeIcon = activeTh.querySelector('.sort-icon');
            if (activeIcon) {
                activeIcon.classList.add('text-[#1a5e35]');
                if (isAscending) {
                    activeIcon.classList.add('rotate-180');
                }
            }
        }

        function applySortAndFilter() {
            if (originalRows.length === 0) return;

            const query = document.getElementById('searchInput').value.toLowerCase();
            
            // 1. FILTERING
            filteredRows = originalRows.filter(row => row.textContent.toLowerCase().includes(query));

            // 2. SORTING
            filteredRows.sort((a, b) => {
                let valA, valB;
                if (currentSortColumn === 'tanggal') {
                    valA = parseInt(a.querySelector('.col-tanggal').dataset.value);
                    valB = parseInt(b.querySelector('.col-tanggal').dataset.value);
                } else {
                    valA = a.querySelector(`.col-${currentSortColumn}`).textContent.trim().toLowerCase();
                    valB = b.querySelector(`.col-${currentSortColumn}`).textContent.trim().toLowerCase();
                }

                if (valA === valB) return 0;
                return isAscending ? (valA < valB ? -1 : 1) : (valA > valB ? -1 : 1);
            });

            // 3. RENDERING PAGINATION
            renderTable();
        }

        function renderTable() {
            const tableBody = document.getElementById('tableBody');
            const noResultRow = document.getElementById('noResultRow');
            
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            // Clear table
            originalRows.forEach(row => { 
                if (row.parentNode === tableBody) tableBody.removeChild(row); 
            });

            noResultRow.style.display = totalItems === 0 ? '' : 'none';

            // Insert rows for current page
            let counter = start + 1;
            filteredRows.forEach((row, index) => {
                if (index >= start && index < end) {
                    tableBody.insertBefore(row, noResultRow);
                    row.style.display = '';
                    row.querySelector('.index-cell').textContent = counter++;
                } else {
                    row.style.display = 'none';
                    tableBody.insertBefore(row, noResultRow);
                }
            });

            updatePaginationControls(totalItems, totalPages, start, end);
        }

        function updatePaginationControls(totalItems, totalPages, start, end) {
            const paginationControls = document.getElementById('paginationControls');
            const pageInfo = document.getElementById('pageInfo');
            
            let actualEnd = end > totalItems ? totalItems : end;
            pageInfo.textContent = totalItems === 0 ? "Menampilkan 0 data" : `Menampilkan ${start + 1} hingga ${actualEnd} dari ${totalItems} data`;

            paginationControls.innerHTML = '';
            
            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    let pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all ${currentPage === i ? 'bg-[#1a5e35] text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'}`;
                    pageBtn.onclick = () => { 
                        currentPage = i; 
                        renderTable(); 
                    };
                    paginationControls.appendChild(pageBtn);
                }
            }
        }
    </script>
    @include('partials.sweetalert')
</body>

</html>