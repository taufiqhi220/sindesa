<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Antrean Tanda Tangan - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .tab-btn.active {
            background-color: #ecfdf5;
            color: #065f46;
            border-color: #10b981;
            font-weight: 700;
        }

        .sort-icon {
            transition: transform 0.2s ease;
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
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Kepala Desa' }}</h4>
                <p class="text-[10px] opacity-70">Kepala Desa</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1">
            <a href="{{ route('kades.dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.dashboard') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>

            <a href="{{ route('kades.perlu-ttd') }}"
                class="flex items-center justify-between p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.perlu-ttd*') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-signature w-5"></i> Perlu Tanda Tangan
                </div>
                @if(isset($unreadCountKades) && $unreadCountKades > 0) <span
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $unreadCountKades }}</span> @endif
            </a>

            <a href="{{ route('kades.riwayat') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.riwayat') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-history w-5"></i> Riwayat Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-question-circle w-5 text-center"></i> Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Pengaturan</div>

            <a href="{{ route('kades.pengaturan-akun') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('kades.pengaturan-akun') ? 'bg-[#cfa03f] text-white shadow-md font-medium' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <i class="fas fa-cog w-5"></i> Pengaturan Akun
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all font-medium">
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
            <h2 class="text-3xl font-bold text-[#1a5e35] mb-2">Antrean Tanda Tangan</h2>
            <p class="text-gray-500">Anda memiliki <strong>{{ $countPerluTtd }}</strong> berkas yang siap diterbitkan.
            </p>
        </div>

        <div
            class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden flex flex-col">

            <div
                class="p-6 border-b border-gray-100 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6">
                <div class="flex overflow-x-auto custom-scrollbar gap-2 w-full xl:w-auto pb-2 xl:pb-0">
                    <button onclick="applyFilters('all', this)"
                        class="tab-btn active px-4 py-2 text-xs font-bold whitespace-nowrap rounded-xl border transition-all">
                        <i class="fas fa-layer-group mr-2"></i> Semua
                    </button>
                    <button onclick="applyFilters('kependudukan', this)"
                        class="tab-btn px-4 py-2 text-xs font-semibold text-gray-500 whitespace-nowrap rounded-xl border border-transparent hover:bg-gray-50 transition-all">
                        <i class="fas fa-users text-blue-500 mr-2"></i> Kependudukan
                    </button>
                    <button onclick="applyFilters('umum', this)"
                        class="tab-btn px-4 py-2 text-xs font-semibold text-gray-500 whitespace-nowrap rounded-xl border border-transparent hover:bg-gray-50 transition-all">
                        <i class="fas fa-file-alt text-purple-500 mr-2"></i> Keterangan Umum
                    </button>
                    <button onclick="applyFilters('perizinan', this)"
                        class="tab-btn px-4 py-2 text-xs font-semibold text-gray-500 whitespace-nowrap rounded-xl border border-transparent hover:bg-gray-50 transition-all">
                        <i class="fas fa-store text-orange-500 mr-2"></i> Perizinan
                    </button>
                    <button onclick="applyFilters('sosial', this)"
                        class="tab-btn px-4 py-2 text-xs font-semibold text-gray-500 whitespace-nowrap rounded-xl border border-transparent hover:bg-gray-50 transition-all">
                        <i class="fas fa-hands-helping text-emerald-500 mr-2"></i> Sosial
                    </button>

                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs text-gray-500 font-bold uppercase">Tampilkan:</span>
                        <select id="itemsPerPage"
                            class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs font-bold focus:border-[#1a5e35] outline-none">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="relative w-full sm:w-64">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari pemohon / jenis surat..."
                            class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-xl outline-none focus:border-[#1a5e35] focus:ring-1 focus:ring-[#1a5e35] transition-all text-xs bg-gray-50">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase text-center w-12">No</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="handleSort('tanggal', this)">
                                Waktu Pengajuan <i
                                    class="fas fa-chevron-down ml-1 text-gray-300 sort-icon text-[#1a5e35] rotate-180"></i>
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="handleSort('pemohon', this)">
                                Identitas Pemohon <i
                                    class="fas fa-chevron-down ml-1 text-gray-300 sort-icon group-hover:text-gray-500"></i>
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="handleSort('jenis', this)">
                                Jenis & Keterangan Surat <i
                                    class="fas fa-chevron-down ml-1 text-gray-300 sort-icon group-hover:text-gray-500"></i>
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase text-center">Tindakan
                            </th>
                        </tr>
                    </thead>
                    <tbody id="mainTableBody" class="text-sm text-gray-800">
                        @forelse($surats as $index => $surat)
                            @php
                                // Pemetaan Nama Surat Lengkap
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
                                    'keterangan_janda_duda' => 'Surat Keterangan Status Janda/Duda'
                                ];

                                $namaSurat = $namaSuratLengkap[$surat->jenis_surat] ?? ucwords(str_replace('_', ' ', $surat->jenis_surat));

                                // Pengelompokan Kategori & Warna
                                $kat = 'umum';
                                if (in_array($surat->jenis_surat, ['pengantar_akta_lahir', 'pengantar_ktp', 'pengantar_kk', 'keterangan_kematian', 'keterangan_pindah'])) {
                                    $kat = 'kependudukan';
                                    $badge = 'bg-blue-50 text-blue-700 border-blue-100';
                                    $icon = 'fa-users';
                                } elseif (in_array($surat->jenis_surat, ['keterangan_usaha', 'izin_keramaian', 'keterangan_kehilangan'])) {
                                    $kat = 'perizinan';
                                    $badge = 'bg-orange-50 text-orange-700 border-orange-100';
                                    $icon = 'fa-store';
                                } elseif (in_array($surat->jenis_surat, ['keterangan_tidak_mampu', 'keterangan_penghasilan'])) {
                                    $kat = 'sosial';
                                    $badge = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                    $icon = 'fa-hands-helping';
                                } else {
                                    $badge = 'bg-purple-50 text-purple-700 border-purple-100';
                                    $icon = 'fa-file-alt';
                                }
                            @endphp
                            <<tr class="data-row md:hover:bg-gray-50 border-b border-gray-100 transition-colors"
                                data-kategori="{{ $kat }}" data-tanggal="{{ $surat->updated_at->timestamp }}"
                                data-pemohon="{{ strtolower($surat->user->name ?? '') }}"
                                data-jenis="{{ strtolower($namaSurat) }}">

                                <td class="index-cell px-6 py-5 text-center font-bold text-gray-300"></td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-gray-700">{{ $surat->updated_at->diffForHumans() }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $surat->updated_at->format('d/m/Y - H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center font-bold text-gray-600 text-xs shadow-sm shrink-0 uppercase">
                                            {{ substr($surat->user->name ?? 'W', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $surat->user->name ?? 'Warga' }}</div>
                                            <div class="text-[10px] text-gray-400 font-mono tracking-tighter">
                                                {{ $surat->user->nik ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="{{ $badge }} border px-3 py-1.5 rounded-lg text-[11px] font-bold inline-flex items-center gap-2 shadow-sm">
                                        <i class="fas {{ $icon }} opacity-60"></i>
                                        {{ $namaSurat }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('kades.surat.detail', ['type' => $surat->jenis_surat, 'id' => $surat->id]) }}"
                                        class="bg-[#1a5e35] active:bg-[#11442b] active:scale-95 text-white px-5 py-2.5 rounded-xl inline-flex items-center gap-2 text-xs font-bold shadow-md transition-transform">
                                        <i class="fas fa-file-signature"></i> Periksa Berkas
                                    </a>
                                </td>
                                </tr>
                        @empty
                                <tr id="emptyReal">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400"><i
                                            class="fas fa-check-circle text-4xl mb-3 opacity-10 block"></i>Antrean kosong.
                                        Semua
                                        surat telah diproses.</td>
                                </tr>
                            @endforelse
                            <tr id="emptySearch" style="display: none;">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400"><i
                                        class="fas fa-search text-4xl mb-3 opacity-10 block"></i>Data tidak ditemukan
                                    dalam
                                    kategori / pencarian ini.</td>
                            </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div id="pageInfo" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest"></div>
                <div id="paginationControls" class="flex gap-1"></div>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        }

        // STATE MANAGEMENT
        let currentKategori = 'all';
        let currentPage = 1;
        let itemsPerPage = 5;
        let currentSortColumn = 'tanggal';
        let isAscending = false; // Default: Paling baru di atas
        let allRows = [];

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const itemsSelect = document.getElementById('itemsPerPage');

            allRows = Array.from(document.querySelectorAll('.data-row'));

            // Initial Sort & Display
            sortRows();
            updateDisplay();

            searchInput.addEventListener('input', () => { currentPage = 1; updateDisplay(); });
            itemsSelect.addEventListener('change', (e) => { itemsPerPage = parseInt(e.target.value); currentPage = 1; updateDisplay(); });
        });

        // FUNGSI SORTING UI & DATA
        function handleSort(col, thEl) {
            // Toggle arah jika kolom sama, jika beda reset ke ascending
            if (currentSortColumn === col) {
                isAscending = !isAscending;
            } else {
                currentSortColumn = col;
                isAscending = true;
            }

            // Reset semua ikon
            document.querySelectorAll('.sort-icon').forEach(icon => {
                icon.classList.remove('text-[#1a5e35]', 'rotate-180');
                icon.classList.add('text-gray-300');
            });

            // Aktifkan ikon yang di-klik
            const icon = thEl.querySelector('.sort-icon');
            if (icon) {
                icon.classList.remove('text-gray-300');
                icon.classList.add('text-[#1a5e35]');
                if (isAscending) icon.classList.add('rotate-180');
            }

            sortRows();
            currentPage = 1;
            updateDisplay();
        }

        function sortRows() {
            allRows.sort((a, b) => {
                let valA = a.dataset[currentSortColumn];
                let valB = b.dataset[currentSortColumn];

                // Jika kolom tanggal, parsing ke integer agar sortir benar
                if (currentSortColumn === 'tanggal') {
                    valA = parseInt(valA);
                    valB = parseInt(valB);
                }

                if (valA < valB) return isAscending ? -1 : 1;
                if (valA > valB) return isAscending ? 1 : -1;
                return 0;
            });
        }

        function applyFilters(kat, btn) {
            currentKategori = kat;
            currentPage = 1;

            document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');

            updateDisplay();
        }

        function updateDisplay() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const tableBody = document.getElementById('mainTableBody');

            // 1. Filter Logic
            const visibleData = allRows.filter(row => {
                const matchKat = (currentKategori === 'all' || row.dataset.kategori === currentKategori);
                const matchSearch = row.dataset.pemohon.includes(query) || row.dataset.jenis.includes(query);
                return matchKat && matchSearch;
            });

            // 2. Hide All First & Detach
            allRows.forEach(r => r.style.display = 'none');

            // 3. Pagination Logic
            const total = visibleData.length;
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = visibleData.slice(start, end);

            // 4. Attach kembali ke DOM sesuai urutan sortir & pagination
            paginatedData.forEach((row, idx) => {
                tableBody.appendChild(row); // Append ulang untuk mengatur urutan elemen HTML
                row.style.display = '';
                row.querySelector('.index-cell').textContent = start + idx + 1;
            });

            // 5. Update UI Info
            const emptySearch = document.getElementById('emptySearch');
            const emptyReal = document.getElementById('emptyReal');

            if (emptyReal && emptyReal.style.display !== 'none' && allRows.length === 0) {
                // Biarkan jika asli dari server kosong
            } else if (emptySearch) {
                emptySearch.style.display = (total === 0 && allRows.length > 0) ? '' : 'none';
                if (total === 0) tableBody.appendChild(emptySearch); // Taruh state kosong di paling bawah
            }

            renderControls(total);
        }

        function renderControls(total) {
            const container = document.getElementById('paginationControls');
            const info = document.getElementById('pageInfo');
            const totalPages = Math.ceil(total / itemsPerPage);

            info.textContent = total > 0 ? `Menampilkan ${Math.min(total, (currentPage - 1) * itemsPerPage + 1)} - ${Math.min(total, currentPage * itemsPerPage)} dari ${total} data` : 'Tidak ada data';

            container.innerHTML = '';
            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all ${i === currentPage ? 'bg-[#1a5e35] text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-200 border border-gray-200'}`;
                btn.onclick = () => { currentPage = i; updateDisplay(); window.scrollTo({ top: 0, behavior: 'smooth' }); };
                container.appendChild(btn);
            }
        }
    </script>

    @if(session('success'))
        <script type="module">
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false, /* Hilangkan tombol OK */
                allowOutsideClick: true, /* Izinkan tutup dengan klik latar belakang */
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        </script>
    @endif

    @if(session('error'))
        <script type="module">
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                allowOutsideClick: true,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        </script>
    @endif
</body>

</html>