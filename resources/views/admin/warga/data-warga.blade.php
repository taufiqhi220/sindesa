<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Data Warga - SINDESA</title>
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

    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">

        {{-- Header Mobile --}}
        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        {{-- Judul & Aksi Atas --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1a5e35]">Daftar Warga</h2>

            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Tombol Buka Modal Cetak Rekap --}}
                <button type="button" onclick="openExportModal()"
                    class="bg-white text-[#1a5e35] border border-[#1a5e35] px-4 py-2 rounded-lg font-medium hover:bg-[#f0fdf4] transition-colors flex items-center justify-center gap-2 shadow-sm text-sm">
                    <i class="fas fa-file-pdf"></i> Download Rekap
                </button>

                {{-- FORM FILTER PENGGABUNGAN --}}
                <form action="{{ route('admin.data-warga') }}" method="GET" class="flex gap-2">
                    {{-- Dropdown Per Page --}}
                    <select name="per_page" onchange="this.form.submit()"
                        class="py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] text-sm bg-white cursor-pointer">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>

                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                            class="w-full sm:w-48 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm">
                    </div>
                </form>
            </div>
        </div>

        {{-- REKAPITULASI --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Total Warga</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $total }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Status Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $aktif }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Non-Aktif (Baru)</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $nonaktif }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Diblokir</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $ditangguhkan }}</h3>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50 border-b-2 border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK</th>
                            
                            {{-- HEADER PROFIL & NAMA --}}
                            @php
                                $sortNama = request('sort') == 'nama_asc' ? 'nama_desc' : 'nama_asc';
                                $iconNama = request('sort') == 'nama_asc' ? 'fa-sort-alpha-down' : (request('sort') == 'nama_desc' ? 'fa-sort-alpha-up' : 'fa-sort');
                            @endphp
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortNama]) }}" class="flex items-center gap-2">
                                    Profil & Nama Warga <i class="fas {{ $iconNama }} text-gray-400"></i>
                                </a>
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dusun (RT/RW)</th>
                            
                            {{-- HEADER TGL TERDAFTAR --}}
                            @php
                                // default sort request for date is empty or 'terbaru', opposite is 'terlama'
                                $currentSort = request('sort', 'terbaru');
                                $sortTgl = $currentSort == 'terbaru' ? 'terlama' : 'terbaru';
                                $iconTgl = $currentSort == 'terlama' ? 'fa-sort-numeric-up' : ($currentSort == 'terbaru' ? 'fa-sort-numeric-down' : 'fa-sort');
                            @endphp
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortTgl]) }}" class="flex items-center gap-2">
                                    Tgl Daftar <i class="fas {{ $iconTgl }} text-gray-400"></i>
                                </a>
                            </th>

                            {{-- HEADER STATUS --}}
                            @php
                                $sortStatus = request('sort') == 'status_asc' ? 'status_desc' : 'status_asc';
                                $iconStatus = request('sort') == 'status_asc' ? 'fa-sort-amount-down' : (request('sort') == 'status_desc' ? 'fa-sort-amount-up' : 'fa-sort');
                            @endphp
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortStatus]) }}" class="flex items-center gap-2">
                                    Status <i class="fas {{ $iconStatus }} text-gray-400"></i>
                                </a>
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[0.95rem] text-gray-800">
                        @forelse($wargas as $index => $warga)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50">
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $wargas->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $warga->nik ?? '-' }}</td>

                                {{-- KOLOM PROFIL DAN NAMA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm overflow-hidden shadow-sm border border-gray-100 shrink-0">
                                            @if($warga->foto_profil)
                                                <img src="{{ asset('storage/' . $warga->foto_profil) }}"
                                                    alt="{{ $warga->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($warga->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $warga->name }}</h4>
                                            <p class="text-[11px] text-gray-500">{{ $warga->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">{{ $warga->jenis_kelamin ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $warga->alamat_lengkap ?? '-' }}
                                    <span class="text-xs text-gray-400 block">RT/RW: {{ $warga->rt_rw ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 font-medium">{{ $warga->created_at ? $warga->created_at->format('d M Y') : '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($warga->status == 'active')
                                        <span
                                            class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200">Aktif</span>
                                    @elseif($warga->status == 'suspended')
                                        <span
                                            class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold border border-gray-300">Diblokir</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold border border-red-200">Non
                                            Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.warga.edit', $warga->id) }}"
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors shadow-sm"
                                            title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        <form action="{{ route('admin.warga.destroy', $warga->id) }}" method="POST"
                                            id="delete-form-{{ $warga->id }}" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $warga->id }}', '{{ $warga->name }}')"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors shadow-sm"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users-slash text-3xl mb-3 block text-gray-300"></i>
                                    <p class="font-medium">Data Warga Tidak Ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- BAGIAN INI GANTI DENGAN KODE DI BAWAH --}}
            @if ($wargas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan <span class="font-bold text-[#1a5e35]">{{ $wargas->firstItem() }}</span> hingga <span class="font-bold text-[#1a5e35]">{{ $wargas->lastItem() }}</span> dari <span class="font-bold text-[#1a5e35]">{{ $wargas->total() }}</span> data
                    </p>
                    
                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Sebelumnya --}}
                        @if ($wargas->onFirstPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                        @else
                            <a href="{{ $wargas->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i class="fas fa-chevron-left text-xs"></i></a>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($wargas->links()->elements as $element)
                            @if (is_string($element))
                                <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500">{{ $element }}</span>
                            @endif
                            
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $wargas->currentPage())
                                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#1a5e35] text-white font-bold shadow-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-[#cfa03f] hover:text-white hover:border-[#cfa03f] transition-colors shadow-sm">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Tombol Selanjutnya --}}
                        @if ($wargas->hasMorePages())
                            <a href="{{ $wargas->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </main>

    {{-- Modal Export PDF --}}
    <div id="exportModal" class="fixed inset-0 bg-black/50 z-[1001] hidden flex items-center justify-center backdrop-blur-sm transition-all duration-300">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300" id="exportModalContent">
            <button onclick="closeExportModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Export Rekap Warga</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih urutan data untuk dokumen PDF rekapitulasi.</p>
            </div>
            <form action="{{ route('admin.warga.export') }}" method="GET" target="_blank" onsubmit="setTimeout(closeExportModal, 500)">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Urutan Data PDF</label>
                    <select name="sort" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#1a5e35] focus:ring-4 focus:ring-[#1a5e35]/10 outline-none transition-all cursor-pointer text-sm">
                        <option value="terbaru">Terbaru Ditambahkan (Default)</option>
                        <option value="terlama">Terlama Ditambahkan</option>
                        <option value="nama_asc">Nama (A-Z)</option>
                        <option value="nama_desc">Nama (Z-A)</option>
                        <option value="status">Status Aktif</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeExportModal()" class="flex-1 bg-gray-100 text-gray-600 px-4 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors focus:outline-none">Batal</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-3 rounded-xl font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/30 flex items-center justify-center gap-2 focus:outline-none">
                        <i class="fas fa-download"></i> Cetak PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openExportModal() {
            const modal = document.getElementById('exportModal');
            const content = document.getElementById('exportModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeExportModal() {
            const modal = document.getElementById('exportModal');
            const content = document.getElementById('exportModalContent');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Data Warga?',
                html: `Anda yakin ingin menghapus data <b>${name}</b>?<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6e7881',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    @include('partials.sweetalert')
</body>

</html>