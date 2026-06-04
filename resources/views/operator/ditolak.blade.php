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
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.dashboard') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('operator.verifikasi') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.verifikasi*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
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
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.menunggu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-signature w-5 text-center"></i> Menunggu TTD
            </a>

            <a href="{{ route('operator.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.riwayat*') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5 text-center"></i> Riwayat Surat
            </a>

            <a href="{{ route('operator.ditolak') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.ditolak*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-times-circle w-5 text-center"></i> Surat Ditolak
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('operator.pengaturan-surat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-surat') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-file-contract w-5 text-center"></i> Pengaturan Surat
            </a>

            <a href="{{ route('operator.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('operator.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
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
            <h2 class="text-3xl font-bold text-red-600">Surat Ditolak</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar permohonan surat yang tidak disetujui karena ketidaksesuaian
                syarat.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[5%] text-center">No</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[15%]">Tanggal Tolak</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[20%]">Nama Pemohon</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[20%] text-center">Jenis
                                Layanan</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[30%]">Alasan Penolakan
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase w-[10%] text-center">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-[0.95rem] text-gray-800">
                        @forelse($surats as $index => $surat)
                            <tr
                                class="data-row hover:bg-red-50/30 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-5 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-700">{{ $surat->updated_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-5 py-4 font-bold">{{ $surat->user->name ?? 'Warga' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-[11px] font-bold">
                                        {{ ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-red-500 italic whitespace-normal leading-relaxed">
                                        "{{ $surat->pesan_penolakan ?? 'Tidak ada alasan spesifik.' }}"
                                    </p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('operator.verifikasi.show', $surat->id) }}"
                                        class="text-blue-600 hover:underline text-xs font-bold">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-400 italic">Belum ada surat yang
                                    ditolak.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

        // --- Logika JS untuk Pagination, Search, Sorting (Sama dengan halaman verifikasi) ---
        let originalRows = [];
        let filteredRows = [];
        let currentPage = 1;
        let itemsPerPage = 5;
        let currentSortColumn = '';
        let isAscending = true;

        document.addEventListener('DOMContentLoaded', function () {
            originalRows = Array.from(document.querySelectorAll('#tableBody tr.data-row'));
            filteredRows = [...originalRows];
            const itemsPerPageSelect = document.getElementById('itemsPerPage');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');

            itemsPerPageSelect.value = "10";
            itemsPerPage = 10;

            let noResultRow = document.createElement('tr');
            noResultRow.id = 'noResultRow';
            noResultRow.innerHTML = '<td colspan="6" class="px-5 py-8 text-center text-gray-500"><i class="fas fa-search text-3xl mb-2 text-gray-300 block"></i>Arsip tidak ditemukan.</td>';
            noResultRow.style.display = 'none';
            tableBody.appendChild(noResultRow);

            sortData('tanggal', document.querySelector('[data-sort="tanggal"]'));

            itemsPerPageSelect.addEventListener('change', function () {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                filteredRows = originalRows.filter(row => row.textContent.toLowerCase().includes(query));
                currentPage = 1;
                renderTable();
            });
        });

        function renderTable() {
            if (originalRows.length === 0) return;
            const tableBody = document.getElementById('tableBody');
            const noResultRow = document.getElementById('noResultRow');
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            originalRows.forEach(row => { if (row.parentNode === tableBody) tableBody.removeChild(row); });
            noResultRow.style.display = totalItems === 0 ? '' : 'none';

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
            pageInfo.textContent = totalItems === 0 ? "Menampilkan 0 data" : `MENAMPILKAN ${start + 1} HINGGA ${actualEnd} DARI ${totalItems} DATA`;

            paginationControls.innerHTML = '';
            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    let pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all ${currentPage === i ? 'bg-[#1a5e35] text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'}`;
                    pageBtn.onclick = () => { currentPage = i; renderTable(); };
                    paginationControls.appendChild(pageBtn);
                }
            }
        }

        function sortData(column, thElement) {
            if (!thElement) return;
            if (currentSortColumn === column) isAscending = !isAscending;
            else { currentSortColumn = column; isAscending = true; }

            document.querySelectorAll('th[data-sort]').forEach(th => {
                th.classList.remove('active-header');
                const icon = th.querySelector('.sort-icon');
                if (icon) icon.classList.remove('text-[#1a5e35]', 'rotate-180');
            });

            thElement.classList.add('active-header');
            const activeIcon = thElement.querySelector('.sort-icon');
            if (activeIcon) {
                activeIcon.classList.add('text-[#1a5e35]');
                if (isAscending) activeIcon.classList.add('rotate-180');
            }

            filteredRows.sort((a, b) => {
                let valA, valB;
                if (column === 'tanggal') {
                    valA = parseInt(a.querySelector('.col-tanggal').dataset.value);
                    valB = parseInt(b.querySelector('.col-tanggal').dataset.value);
                } else {
                    valA = a.querySelector(`.col-${column}`).textContent.trim().toLowerCase();
                    valB = b.querySelector(`.col-${column}`).textContent.trim().toLowerCase();
                }
                return isAscending ? (valA < valB ? -1 : 1) : (valA > valB ? -1 : 1);
            });
            currentPage = 1;
            renderTable();
        }
    </script>
    @include('partials.sweetalert')
</body>

</html>