<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Log Aktivitas Sistem - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    </style>
</head>

<body class="font-['Poppins'] bg-[#f4f6f9] flex min-h-screen">

    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">

        {{-- Header Mobile --}}
        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-8 border border-gray-100">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35] focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        {{-- Judul Atas --}}
        <div class="flex flex-col gap-2 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1a5e35] flex items-center gap-3">
                <i class="fas fa-history text-[#cfa03f]"></i> Log Aktivitas Sistem
            </h2>
            <p class="text-gray-500 text-sm">Catatan audit seluruh perubahan master data yang dilakukan oleh Administrator.</p>
        </div>

        {{-- Tabel Log --}}
        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            
            {{-- Toolbar: Dropdown Limit --}}
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
                    <span>Tampilkan</span>
                    <form id="filterForm" method="GET" class="m-0">
                        <select name="per_page" onchange="document.getElementById('filterForm').submit()" 
                            class="border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-[#1a5e35] focus:border-[#1a5e35] outline-none bg-white font-semibold cursor-pointer shadow-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <span>data</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50 border-b-2 border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aktor (Admin)</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="text-[0.95rem] text-gray-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50">
                                <td class="px-6 py-4 text-gray-500 font-medium text-sm">
                                    @php
                                        // Ubah zona waktu ke Sulawesi Selatan (WITA)
                                        $waktu = $log->created_at->timezone('Asia/Makassar');
                                    @endphp
                                    {{ $waktu->locale('id')->translatedFormat('d M Y') }} <br>
                                    <span class="text-xs text-gray-400">{{ $waktu->format('H:i:s') }} WITA</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($log->causer->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="font-bold text-gray-700">{{ $log->causer->name ?? 'Sistem/Otomatis' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-md text-xs font-bold border border-purple-200">
                                        {{ $log->log_name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        // Bypass casting model, ambil raw string langsung dari DB
                                        $rawJson = $log->getRawOriginal('attribute_changes') ?? $log->getRawOriginal('properties');
                                        $parsed = json_decode($rawJson, true) ?? [];
                                        $attributes = $parsed['attributes'] ?? [];
                                        $olds = $parsed['old'] ?? [];

                                        $action = $log->description;
                                        
                                        // Tentukan nama subjek secara spesifik
                                        $subjectName = 'Data Terhapus';
                                        if ($log->subject) {
                                            if (class_basename($log->subject_type) === 'User') {
                                                $subjectName = $log->subject->name;
                                            } elseif (class_basename($log->subject_type) === 'PengajuanSurat') {
                                                $subjectName = 'Surat ' . ucwords(str_replace('_', ' ', $log->subject->jenis_surat));
                                            } elseif (class_basename($log->subject_type) === 'PengaturanSurat') {
                                                $subjectName = 'Pengaturan KOP Surat';
                                            } else {
                                                $subjectName = $log->subject->name ?? class_basename($log->subject_type);
                                            }
                                        } else {
                                            // Fallback jika subject terhapus
                                            if (class_basename($log->subject_type) === 'User') {
                                                $subjectName = $olds['name'] ?? $attributes['name'] ?? 'Akun Pengguna (Terhapus)';
                                            } elseif (class_basename($log->subject_type) === 'PengajuanSurat') {
                                                $jenis = $olds['jenis_surat'] ?? $attributes['jenis_surat'] ?? 'Surat';
                                                $subjectName = 'Surat ' . ucwords(str_replace('_', ' ', $jenis)) . ' (Terhapus)';
                                            } elseif (class_basename($log->subject_type) === 'PengaturanSurat') {
                                                $subjectName = 'Pengaturan KOP Surat';
                                            }
                                        }

                                        $badgeColor = 'bg-gray-100 text-gray-600 border-gray-200';
                                        $icon = 'fa-info-circle';
                                        $actionText = 'Melakukan perubahan pada';

                                        if ($action == 'created') {
                                            $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                            $icon = 'fa-plus-circle';
                                            $actionText = 'Menambahkan data baru:';
                                        } elseif ($action == 'updated') {
                                            $badgeColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                            $icon = 'fa-edit';
                                            $actionText = 'Memperbarui data:';
                                        } elseif ($action == 'deleted') {
                                            $badgeColor = 'bg-red-100 text-red-800 border-red-200';
                                            $icon = 'fa-trash-alt';
                                            $actionText = 'Menghapus permanen data:';
                                        }
                                    @endphp

                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="{{ $badgeColor }} px-2 py-0.5 rounded text-[10px] font-bold border uppercase tracking-wider">
                                                <i class="fas {{ $icon }} mr-1"></i> {{ $action }}
                                            </span>
                                            <span class="text-gray-600 text-sm">{{ $actionText }} <b>{{ $subjectName }}</b></span>
                                        </div>

                                        {{-- Tampilkan detail perubahan --}}
                                        @if($action == 'updated' && !empty($attributes))
                                            <div class="mt-2 text-[11px] bg-white p-3 rounded-lg border border-gray-200 shadow-sm w-max max-w-lg">
                                                <p class="font-bold text-gray-800 mb-2 border-b border-gray-100 pb-1">Detail Perubahan Data:</p>
                                                <div class="flex flex-col gap-1.5">
                                                    @foreach($attributes as $key => $newValue)
                                                        @php $oldValue = $olds[$key] ?? ''; @endphp

                                                        @if($oldValue != $newValue && $key !== 'updated_at')
                                                            <div class="flex items-start gap-3">
                                                                <span class="font-semibold text-gray-600 w-28 shrink-0">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <span class="px-2 py-0.5 bg-red-50 text-red-500 rounded line-through border border-red-100">{{ is_array($oldValue) ? json_encode($oldValue) : ($oldValue ?: '(Kosong)') }}</span>
                                                                    <i class="fas fa-arrow-right text-gray-400 text-[10px]"></i>
                                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 font-bold rounded border border-emerald-100">{{ is_array($newValue) ? json_encode($newValue) : ($newValue ?: '(Kosong)') }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-history text-3xl mb-3 block text-gray-300"></i>
                                    <p class="font-medium">Belum ada aktivitas yang tercatat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- CUSTOM PAGINATION MANUAL --}}
            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        Menampilkan <span class="font-bold text-[#1a5e35]">{{ $logs->firstItem() }}</span> hingga <span class="font-bold text-[#1a5e35]">{{ $logs->lastItem() }}</span> dari <span class="font-bold text-[#1a5e35]">{{ $logs->total() }}</span> data
                    </p>
                    
                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Sebelumnya --}}
                        @if ($logs->onFirstPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i class="fas fa-chevron-left text-xs"></i></a>
                        @endif

                        {{-- Nomor Halaman --}}
                        @foreach ($logs->links()->elements as $element)
                            @if (is_string($element))
                                <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500">{{ $element }}</span>
                            @endif
                            
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $logs->currentPage())
                                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#1a5e35] text-white font-bold shadow-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-[#cfa03f] hover:text-white hover:border-[#cfa03f] transition-colors shadow-sm">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Tombol Selanjutnya --}}
                        @if ($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-[#1a5e35] hover:bg-[#1a5e35] hover:text-white transition-colors shadow-sm"><i class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                        @endif
                    </div>
                </div>
            @endif
            
        </div>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @include('partials.sweetalert')
</body>

</html>
