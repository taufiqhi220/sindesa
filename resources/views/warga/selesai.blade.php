<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Surat Selesai - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    <div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

    @include('warga.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-8 lg:ml-0 overflow-x-hidden">

        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        <div class="mb-6 flex items-center gap-4">
            <div
                class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Surat Selesai</h2>
                <p class="text-gray-500 text-sm">Dokumen pengajuan Anda yang telah diproses oleh Kepala Desa.</p>
            </div>
        </div>

        {{-- INFO BANNER SESUAI PILIHAN KADES --}}
        <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl shadow-sm">
            <h4 class="font-bold text-blue-800 text-sm mb-3"><i class="fas fa-info-circle mr-1"></i> Penjelasan Status &
                Tanda Tangan</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-blue-800">
                <div class="bg-white/60 p-3 rounded-lg border border-blue-100">
                    {{-- Warna Sky Blue (Biru Langit) --}}
                    <div class="mb-2">
                        <span
                            class="bg-sky-100 text-sky-700 border border-sky-200 font-bold px-2 py-1 rounded-md inline-block"><i
                                class="fas fa-qrcode mr-1"></i> 1. Elektronik (QR Code)</span>
                    </div>
                    Menyematkan QR Code validasi langsung ke dokumen. Sah digunakan dan <b>dapat Anda cetak/unduh
                        langsung</b>.
                </div>
                <div class="bg-white/60 p-3 rounded-lg border border-blue-100">
                    <div class="mb-2">
                        <span
                            class="bg-purple-100 text-purple-700 border border-purple-200 font-bold px-2 py-1 rounded-md inline-block"><i
                                class="fas fa-signature mr-1"></i> 2. Konvensional (Gambar)</span>
                    </div>
                    Menyematkan gambar scan TTD Kades ke dokumen. Sah digunakan dan <b>dapat Anda cetak/unduh
                        langsung</b>.
                </div>
                <div class="bg-white/60 p-3 rounded-lg border border-blue-100 border-l-4 border-l-amber-500">
                    <div class="mb-2">
                        <span
                            class="bg-amber-100 text-amber-700 border border-amber-200 font-bold px-2 py-1 rounded-md inline-block"><i
                                class="fas fa-pen-nib mr-1"></i> 3. Basah (Manual)</span>
                    </div>
                    Menyiapkan ruang kosong pada dokumen. <b>Anda WAJIB ke Balai Desa</b> untuk meminta TTD asli pulpen
                    & stempel Kades.
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col border-t-4 border-[#1a5e35]">

            {{-- Header Tabel --}}
            <div
                class="p-6 border-b border-[#e0e0e0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
                <div class="text-xl font-semibold text-gray-800">Daftar Surat Siap Diambil/Diunduh</div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Tampilkan</span>
                        <select id="itemsPerPage"
                            class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-[#1a5e35]">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari jenis surat..."
                            class="w-full sm:w-[250px] focus:w-[280px] pl-11 pr-4 py-2 border border-gray-300 rounded-full outline-none focus:border-[#1a5e35] focus:ring-1 focus:ring-[#1a5e35] transition-all text-sm">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50 select-none">
                        <tr>
                            <th class="px-5 py-4 text-sm font-semibold text-gray-500 uppercase w-[5%]">No</th>
                            <th class="px-5 py-4 text-sm font-semibold text-gray-500 uppercase w-[20%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="tanggal" onclick="sortData('tanggal', this)">
                                <div class="flex items-center">Tanggal Selesai <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th class="px-5 py-4 text-sm font-semibold text-gray-500 uppercase w-[30%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="jenis" onclick="sortData('jenis', this)">
                                <div class="flex items-center">Jenis Surat <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th class="px-5 py-4 text-sm font-semibold text-gray-500 uppercase w-[25%] cursor-pointer hover:bg-gray-200 transition-colors duration-200"
                                data-sort="status" onclick="sortData('status', this)">
                                <div class="flex items-center">Status & Tipe TTD <i
                                        class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform duration-200 sort-icon"></i>
                                </div>
                            </th>
                            <th class="px-5 py-4 text-sm font-semibold text-gray-500 uppercase w-[20%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-[0.95rem] text-gray-800">
                        @forelse($pengajuanSelesai as $index => $surat)
                            <tr
                                class="data-row hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                <td class="px-5 py-4 index-cell">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 col-tanggal text-gray-600"
                                    data-value="{{ $surat->updated_at->timestamp }}">
                                    {{ $surat->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-4 font-semibold capitalize col-jenis">
                                    {{ str_replace('_', ' ', $surat->jenis_surat) }}
                                </td>
                                <td class="px-5 py-4 col-status" data-value="selesai">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <span
                                            class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>

                                        @if($surat->metode_ttd == 'manual')
                                            <span
                                                class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1">
                                                <i class="fas fa-pen-nib"></i> Basah (Manual)
                                            </span>
                                        @elseif($surat->metode_ttd == 'konvensional')
                                            <span
                                                class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1">
                                                <i class="fas fa-signature"></i> Konvensional (Gambar)
                                            </span>
                                        @else
                                            {{-- Warna Sky Blue (Biru Langit) --}}
                                            <span
                                                class="bg-sky-100 text-sky-700 px-2.5 py-1 rounded-md text-[10px] font-bold inline-flex items-center gap-1">
                                                <i class="fas fa-qrcode"></i> Elektronik (QR Code)
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-1.5">
                                        @php
                                            $isManual = ($surat->metode_ttd == 'manual') ? 'true' : 'false';
                                        @endphp

                                        <a href="{{ route('warga.surat.cetak', $surat->id) }}?download=1"
                                            onclick="return cekTipeTTD(event, this.href, {{ $isManual }})"
                                            class="w-9 h-9 bg-[#1a5e35] hover:bg-[#2e7d32] text-white rounded-lg flex justify-center items-center transition-colors shadow-sm"
                                            title="Unduh PDF">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <a href="{{ route('warga.surat.cetak', $surat->id) }}" target="_blank"
                                            onclick="return cekTipeTTD(event, this.href, {{ $isManual }})"
                                            class="w-9 h-9 bg-gray-600 hover:bg-gray-800 text-white rounded-lg flex justify-center items-center transition-colors shadow-sm"
                                            title="Cetak Surat">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyServerState">
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mb-3">
                                            <i class="fas fa-file-excel"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada surat yang selesai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="p-4 border-t border-[#e0e0e0] flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 rounded-b-2xl">
                <div class="text-sm text-gray-600" id="pageInfo">Menampilkan 0 data</div>
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

        function cekTipeTTD(event, url, isManual) {
            if (isManual) {
                event.preventDefault();
                Swal.fire({
                    title: 'Perhatian! Tanda Tangan Basah',
                    html: "Dokumen ini menyiapkan ruang kosong untuk <b>Tanda Tangan Manual (Basah)</b>.<br><br>Anda bisa mengunduh drafnya untuk dicetak sendiri, <b>ATAU</b> langsung datang ke Balai Desa agar petugas mencetakkannya dan meminta TTD pulpen + Stempel Kades.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1a5e35',
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: '<i class="fas fa-print mr-1"></i> Lanjut Unduh Draf',
                    cancelButtonText: 'Tutup',
                    customClass: { popup: 'rounded-2xl' }
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = url;
                });
                return false;
            }
            return true;
        }

        // ============================================
        // JS LOGIC: SEARCH, PAGINATION & SORTING 
        // ============================================
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

            itemsPerPage = parseInt(itemsPerPageSelect.value);

            let noResultRow = document.createElement('tr');
            noResultRow.id = 'noResultRow';
            noResultRow.innerHTML = '<td colspan="5" class="px-5 py-12 text-center text-gray-500"><i class="fas fa-search text-3xl mb-2 text-gray-300 block"></i>Data tidak ditemukan.</td>';
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
                    const textContent = row.textContent.toLowerCase();
                    return textContent.includes(query);
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
                pageInfo.textContent = `Menampilkan ${start + 1} hingga ${actualEnd} dari ${totalItems} data`;
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
                icon.classList.remove('text-[#1a5e35]', 'rotate-180');
                icon.classList.add('text-gray-400');
            });

            thElement.classList.add('active-header');
            const activeIcon = thElement.querySelector('.sort-icon');
            activeIcon.classList.remove('text-gray-400');
            activeIcon.classList.add('text-[#1a5e35]');

            if (isAscending) {
                activeIcon.classList.add('rotate-180');
            }

            filteredRows.sort((a, b) => {
                let valA, valB;

                if (column === 'tanggal') {
                    valA = parseInt(a.querySelector('.col-tanggal').dataset.value) || 0;
                    valB = parseInt(b.querySelector('.col-tanggal').dataset.value) || 0;
                } else if (column === 'jenis') {
                    valA = a.querySelector('.col-jenis').textContent.trim().toLowerCase();
                    valB = b.querySelector('.col-jenis').textContent.trim().toLowerCase();
                } else if (column === 'status') {
                    valA = a.querySelector('.col-status').dataset.value.toLowerCase();
                    valB = b.querySelector('.col-status').dataset.value.toLowerCase();
                }

                if (valA < valB) return isAscending ? -1 : 1;
                if (valA > valB) return isAscending ? 1 : -1;
                return 0;
            });

            currentPage = 1;
            renderTable();
        }
    </script>
    @include('partials.sweetalert')
</body>

</html>