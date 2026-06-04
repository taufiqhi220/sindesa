<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Menunggu TTD - SINDESA</title>
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
            <div class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil" class="w-full h-full object-cover">
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
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
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

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#1a5e35]">Menunggu TTD Kades</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar permohonan surat yang telah Anda verifikasi dan sedang diproses Kepala Desa.</p>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-center">
            <span class="text-sm font-semibold text-gray-600 mr-2"><i class="fas fa-info-circle mr-1"></i> Keterangan Kategori:</span>
            <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-md text-xs font-bold">Kependudukan (Biru)</span>
            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-md text-xs font-bold">Sosial & Ekonomi (Hijau)</span>
            <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-md text-xs font-bold">Perizinan (Oranye)</span>
            <span class="bg-purple-50 text-purple-600 border border-purple-100 px-3 py-1 rounded-md text-xs font-bold">Keterangan Umum (Ungu)</span>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col">

            <div class="p-6 border-b border-[#e0e0e0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
                <div class="text-xl font-semibold text-gray-800">Antrian Tanda Tangan</div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Tampilkan</span>
                        <select id="itemsPerPage" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-[#1a5e35]">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari pemohon atau layanan..."
                            class="w-full sm:w-[250px] focus:w-[280px] pl-11 pr-4 py-2 border border-gray-300 rounded-full outline-none focus:border-[#1a5e35] focus:ring-1 focus:ring-[#1a5e35] transition-all text-sm">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 select-none">
                        <tr>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[5%] text-center">No</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[20%] cursor-pointer hover:bg-gray-200 transition-colors duration-200" data-sort="tanggal" onclick="sortData('tanggal', this)">
                                <div class="flex items-center">Waktu Verifikasi <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[25%] cursor-pointer hover:bg-gray-200 transition-colors duration-200" data-sort="pemohon" onclick="sortData('pemohon', this)">
                                <div class="flex items-center">Nama Pemohon <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[25%] cursor-pointer hover:bg-gray-200 transition-colors duration-200" data-sort="jenis" onclick="sortData('jenis', this)">
                                <div class="flex items-center">Jenis Layanan <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i></div>
                            </th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[15%]">Status Internal</th>
                            <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-[10%] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-[0.95rem] text-gray-800">
                        @forelse($surats as $index => $surat)
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
                            @endphp

                            <tr class="data-row hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-5 py-4 index-cell text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 col-tanggal" data-value="{{ $surat->updated_at->timestamp }}">
                                    <div class="font-semibold text-gray-700">{{ $surat->updated_at->diffForHumans() }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $surat->updated_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-5 py-4 col-pemohon">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1a5e35] to-[#2e7d32] text-white flex justify-center items-center font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($surat->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $surat->user->name ?? 'Tanpa Nama' }}</div>
                                            <div class="text-[11px] text-gray-400 font-mono italic">{{ $surat->user->nik ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 col-jenis">
                                    <span class="{{ $colorClass }} px-3 py-1.5 rounded-md text-xs font-bold inline-flex max-w-[180px] whitespace-normal leading-snug">
                                        {{ ucwords(str_replace('_', ' ', $surat->jenis_surat)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-100 shadow-sm animate-pulse">
                                        <i class="fas fa-signature text-[10px] text-orange-600"></i>
                                        <span class="text-[11px] font-bold text-orange-700 uppercase tracking-widest">Diproses Kades</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('operator.verifikasi.show', $surat->id) }}"
                                            class="w-10 h-10 bg-white border border-gray-200 text-blue-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 rounded-xl flex justify-center items-center transition-all shadow-sm group"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                                        </a>
                                        <form action="{{ route('operator.verifikasi.tarik', $surat->id) }}" method="POST" class="inline-block rollback-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" onclick="confirmRollback(this)"
                                                class="w-10 h-10 bg-white border border-gray-200 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 rounded-xl flex justify-center items-center transition-all shadow-sm group"
                                                title="Tarik Kembali">
                                                <i class="fas fa-undo group-hover:rotate-[-45deg] transition-transform"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyServerState">
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                    <i class="fas fa-clipboard-check text-3xl mb-2 text-gray-300 block"></i>
                                    Tidak ada surat yang sedang diproses oleh Kepala Desa saat ini.
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

    @include('partials.sweetalert')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Fungsi SweetAlert untuk Rollback
        function confirmRollback(button) {
            Swal.fire({
                title: 'Tarik Kembali Surat?',
                text: "Surat akan dikembalikan ke antrean verifikasi untuk diperbaiki.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1a5e35',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tarik!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }

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

            itemsPerPageSelect.value = "5";

            let noResultRow = document.createElement('tr');
            noResultRow.id = 'noResultRow';
            noResultRow.innerHTML = '<td colspan="6" class="px-5 py-8 text-center text-gray-500"><i class="fas fa-search text-3xl mb-2 text-gray-300 block"></i>Data tidak ditemukan.</td>';
            noResultRow.style.display = 'none';
            tableBody.appendChild(noResultRow);

            isAscending = false;
            sortData('tanggal', document.querySelector('[data-sort="tanggal"]'));

            itemsPerPageSelect.addEventListener('change', function () {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                filteredRows = originalRows.filter(row => {
                    return row.textContent.toLowerCase().includes(query);
                });

                if (currentSortColumn) {
                    let tempAsc = isAscending;
                    currentSortColumn = '';
                    isAscending = tempAsc;
                    sortData(document.querySelector(`[data-sort].active-header`)?.dataset.sort || 'tanggal', document.querySelector(`[data-sort].active-header`) || document.querySelector('[data-sort="tanggal"]'));
                } else {
                    currentPage = 1;
                    renderTable();
                }
            });
        });

        function renderTable() {
            if (originalRows.length === 0) return;
            const tableBody = document.getElementById('tableBody');
            const noResultRow = document.getElementById('noResultRow');
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            originalRows.forEach(row => {
                if (row.parentNode === tableBody) tableBody.removeChild(row);
            });

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
            
            if (totalItems === 0) {
                pageInfo.textContent = "Menampilkan 0 data";
            } else {
                pageInfo.textContent = `MENAMPILKAN ${start + 1} HINGGA ${actualEnd} DARI ${totalItems} DATA`;
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

        function sortData(column, thElement) {
            if (!thElement) return;
            if (currentSortColumn === column) {
                isAscending = !isAscending;
            } else {
                currentSortColumn = column;
                isAscending = true;
            }

            document.querySelectorAll('th[data-sort]').forEach(th => {
                th.classList.remove('active-header'); 
                const icon = th.querySelector('.sort-icon');
                if (icon) {
                    icon.classList.remove('text-[#1a5e35]', 'rotate-180');
                    icon.classList.add('text-gray-400');
                }
            });

            thElement.classList.add('active-header');
            const activeIcon = thElement.querySelector('.sort-icon');
            if (activeIcon) {
                activeIcon.classList.remove('text-gray-400');
                activeIcon.classList.add('text-[#1a5e35]');
                if (isAscending) activeIcon.classList.add('rotate-180');
            }

            filteredRows.sort((a, b) => {
                let valA, valB;
                if (column === 'tanggal') {
                    valA = parseInt(a.querySelector('.col-tanggal').dataset.value) || 0;
                    valB = parseInt(b.querySelector('.col-tanggal').dataset.value) || 0;
                } else if (column === 'pemohon') {
                    valA = a.querySelector('.col-pemohon').textContent.trim().toLowerCase();
                    valB = b.querySelector('.col-pemohon').textContent.trim().toLowerCase();
                } else if (column === 'jenis') {
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
    </script>
</body>

</html>