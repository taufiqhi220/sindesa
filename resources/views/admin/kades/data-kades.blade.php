<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Data Kepala Desa - SINDESA</title>
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
        <div
            class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none"><i
                    class="fas fa-bars"></i></button>
        </div>

        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-[#1a5e35]">Daftar Kepala Desa</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola riwayat dan tanda tangan elektronik Pejabat Desa.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.kades.create') }}"
                    class="bg-[#1a5e35] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#2e7d32] hover:shadow-lg transition-all flex items-center justify-center gap-2 shadow-sm text-sm whitespace-nowrap">
                    <i class="fas fa-plus"></i> Tambah Pejabat
                </a>

                {{-- FORM FILTER & SEARCH --}}
                <form action="{{ route('admin.data-kades') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-2 w-full">

                    {{-- Simpan parameter sort jika ada, agar tidak hilang saat submit form --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Dropdown Per Page --}}
                    <select name="per_page" onchange="this.form.submit()"
                        class="py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 text-sm bg-white cursor-pointer w-full sm:w-auto">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 Baris</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                    </select>

                    <div class="relative w-full">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#1a5e35] focus:ring-2 focus:ring-[#1a5e35]/20 transition-all text-sm">
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl flex items-start gap-4 shadow-sm">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
            <p class="text-sm text-amber-800 font-medium">Hanya boleh ada <strong>1 (satu)</strong> akun Kepala Desa
                berstatus <strong>Aktif</strong> untuk keperluan validasi Tanda Tangan Elektronik.</p>
        </div>

        {{-- REKAPITULASI --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i
                        class="fas fa-users"></i></div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Riwayat</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $total }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Aktif Menjabat</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $aktif }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center text-xl"><i
                        class="fas fa-user-clock"></i></div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Purna Tugas</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $nonaktif }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50 border-b-2 border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>

                            {{-- HEADER PROFIL --}}
                            @php
                                $sortNama = request('sort') == 'nama_asc' ? 'nama_desc' : 'nama_asc';
                                $iconNama = request('sort') == 'nama_asc' ? 'fa-sort-alpha-down' : (request('sort') == 'nama_desc' ? 'fa-sort-alpha-up' : 'fa-sort');
                            @endphp
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortNama]) }}"
                                    class="flex items-center gap-2">
                                    Profil Pejabat <i class="fas {{ $iconNama }} text-gray-400"></i>
                                </a>
                            </th>

                            {{-- HEADER STATUS TTD --}}
                            @php
                                $sortTtd = request('sort') == 'ttd_asc' ? 'ttd_desc' : 'ttd_asc';
                                $iconTtd = request('sort') == 'ttd_asc' ? 'fa-sort-amount-down' : (request('sort') == 'ttd_desc' ? 'fa-sort-amount-up' : 'fa-sort');
                            @endphp
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortTtd]) }}"
                                    class="flex items-center gap-2">
                                    Status TTD <i class="fas {{ $iconTtd }} text-gray-400"></i>
                                </a>
                            </th>

                            {{-- HEADER STATUS AKUN --}}
                            @php
                                $sortStatus = request('sort') == 'status_asc' ? 'status_desc' : 'status_asc';
                                $iconStatus = request('sort') == 'status_asc' ? 'fa-sort-amount-down' : (request('sort') == 'status_desc' ? 'fa-sort-amount-up' : 'fa-sort');
                            @endphp
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-200 transition-colors">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $sortStatus]) }}"
                                    class="flex items-center gap-2">
                                    Status Akun <i class="fas {{ $iconStatus }} text-gray-400"></i>
                                </a>
                            </th>

                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[0.95rem] text-gray-800">
                        @forelse($kades as $index => $k)
                            <tr
                                class="hover:bg-gray-50 transition-colors border-b border-gray-50 {{ $k->status != 'active' ? 'opacity-70' : '' }}">
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $kades->firstItem() + $index }}</td>

                                {{-- FOTO PROFIL KADES --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($k->foto_profil)
                                            <img src="{{ asset('storage/' . $k->foto_profil) }}" alt="{{ $k->name }}"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-[#1a5e35]/10 text-[#1a5e35] flex items-center justify-center font-bold text-sm shadow-sm border border-gray-100 shrink-0">
                                                {{ substr($k->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <div>
                                            <div class="font-bold text-gray-900 text-base">{{ $k->name }}</div>
                                                                                        <div class="text-[11px] text-gray-500 font-mono mt-1 space-x-1">
                                                <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200">NIK: {{ $k->nik ?? '-' }}</span>
                                                <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200">NIP: {{ $k->nip ?? '-' }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5"><i
                                                    class="fas fa-envelope mr-1"></i>{{ $k->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($k->ttd_path)
                                            <div
                                                class="w-8 h-8 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                                <i class="fas fa-signature"></i>
                                            </div>
                                            <div>
                                                <span class="text-sm font-semibold text-emerald-700 block">Terunggah</span>
                                                <a href="{{ asset('storage/' . $k->ttd_path) }}" target="_blank"
                                                    class="text-[10px] text-blue-500 hover:underline">Lihat Spesimen</a>
                                            </div>
                                        @else
                                            <div
                                                class="w-8 h-8 rounded bg-red-100 text-red-600 flex items-center justify-center">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <span class="text-sm font-semibold text-red-700 block">Belum Ada TTD</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($k->status == 'active')
                                        <span
                                            class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200">Aktif
                                            Menjabat</span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">Purna
                                            Tugas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($k->status == 'active')
                                            <a href="{{ route('admin.kades.edit', $k->id) }}"
                                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors shadow-sm"
                                                title="Edit Data"><i class="fas fa-pen text-xs"></i></a>
                                            <form action="{{ route('admin.kades.nonaktif', $k->id) }}" method="POST"
                                                class="form-nonaktif inline-block">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors shadow-sm"
                                                    title="Akhiri Jabatan"><i class="fas fa-power-off text-xs"></i></button>
                                            </form>
                                        @else
                                            <button class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed"
                                                disabled title="Aksi tidak tersedia"><i class="fas fa-pen text-xs"></i></button>
                                            <form action="{{ route('admin.kades.destroy', $k->id) }}" method="POST"
                                                class="form-delete inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors shadow-sm"
                                                    title="Hapus Riwayat"><i class="fas fa-trash text-xs"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-user-tie text-3xl mb-3 block text-gray-300"></i>
                                    <p class="font-medium">Belum ada data Kepala Desa terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">@if ($kades->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan <span class="font-bold text-[#1a5e35]">{{ $kades->firstItem() }}</span> hingga <span
                            class="font-bold text-[#1a5e35]">{{ $kades->lastItem() }}</span> dari <span
                            class="font-bold text-[#1a5e35]">{{ $kades->total() }}</span> data
                    </p>

                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Sebelumnya --}}
                        @if ($kades->onFirstPage())
                            <span
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i
                                    class="fas fa-chevron-left text-xs"></i></span>
                        @else
                            <a href="{{ $kades->previousPageUrl() }}"
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i
                                    class="fas fa-chevron-left text-xs"></i></a>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($kades->links()->elements as $element)
                            @if (is_string($element))
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500">{{ $element }}</span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $kades->currentPage())
                                        <span
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#1a5e35] text-white font-bold shadow-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-[#cfa03f] hover:text-white hover:border-[#cfa03f] transition-colors shadow-sm">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Tombol Selanjutnya --}}
                        @if ($kades->hasMorePages())
                            <a href="{{ $kades->nextPageUrl() }}"
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i
                                    class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span
                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i
                                    class="fas fa-chevron-right text-xs"></i></span>
                        @endif
                    </div>
                </div>
            @endif
            </div>
        </div>
    </main>

    <script type="module">
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
        }
        window.toggleSidebar = toggleSidebar;

        // SweetAlert untuk Hapus Riwayat
        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Riwayat Kades?', text: "Data tidak bisa dikembalikan!", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#1a5e35', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });

        // SweetAlert untuk Purna Tugas
        document.querySelectorAll('.form-nonaktif').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Akhiri Jabatan?',
                    text: "Pejabat ini akan dipurnatugaskan dan tidak bisa lagi melakukan validasi dokumen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#1a5e35',
                    confirmButtonText: 'Ya, Purna Tugaskan!',
                    cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
    </script>
    @include('partials.sweetalert')
</body>

</html>
