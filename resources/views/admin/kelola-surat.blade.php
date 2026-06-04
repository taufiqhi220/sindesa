<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('image/SINDESA_ICON_BLACK_TRANSPARNT.png') }}">
    <title>Kelola Jenis Surat - SINDESA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Toggle Switch (Murni CSS) */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input:checked+.slider {
            background-color: #1a5e35;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
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

        <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5 shadow-inner">
            <div
                class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg text-white">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="overflow-hidden text-sm">
                <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Administrator' }}</h4>
                <p class="text-[10px] opacity-70 uppercase tracking-wider">Super Admin</p>
            </div>
        </div>

        <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-2">
                <i class="fas fa-th-large w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('admin.data-warga') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-1">
                <i class="fas fa-users w-5 text-center"></i> Data Warga
            </a>

            <a href="{{ route('admin.data-operator') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-1">
                <i class="fas fa-user-shield w-5 text-center"></i> Data Operator
            </a>

            <a href="{{ route('admin.data-kades') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-1">
                <i class="fas fa-user-tie w-5 text-center"></i> Data Kepala Desa
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-6 px-3">Manajemen Konten
            </div>

            <a href="{{ route('admin.kelola-surat') }}"
                class="flex items-center gap-3 p-3 bg-[#cfa03f] text-white font-medium rounded-lg shadow-md transition-all mb-1">
                <i class="fas fa-file-contract w-5 text-center"></i> Kelola Jenis Surat
            </a>

            <a href="#"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-1">
                <i class="fas fa-question-circle w-5 text-center"></i> Pusat Bantuan
            </a>

            <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-6 px-3">Sistem</div>

            <a href="{{ route('admin.pengaturan') }}"
                class="flex items-center gap-3 p-3 text-white/80 hover:bg-[#cfa03f] hover:text-white rounded-lg transition-all mb-1">
                <i class="fas fa-cogs w-5 text-center"></i> Pengaturan Sistem
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer font-semibold">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-10 lg:ml-[280px] overflow-x-hidden min-h-screen">

        {{-- BURGER MENU MOBILE --}}
        <div class="lg:hidden flex justify-between items-center bg-white p-4 rounded-xl shadow-sm mb-6">
            <img src="{{ asset('image/SINDESA_BLACK_TRANSPARNT.png') }}" alt="Logo" class="h-8">
            <button onclick="toggleSidebar()" class="text-2xl text-[#1a5e35]"><i class="fas fa-bars"></i></button>
        </div>

        {{-- BAGIAN KOP SURAT DINAMIS --}}
        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 p-6 md:p-8 mb-10">
            <div class="flex items-center gap-3 text-lg font-bold text-gray-800 mb-2">
                <i class="fas fa-heading text-[#cfa03f]"></i> Pengaturan Kop Surat (Header PDF)
            </div>
            <p class="text-sm text-gray-500 mb-6">Kop surat ini otomatis digunakan pada cetakan PDF.</p>

            {{-- PREVIEW KOP SURAT (SESUAI PDF ASLI) --}}
            <div
                class="bg-gray-200 p-4 sm:p-6 rounded-lg border border-gray-300 mb-6 overflow-x-auto flex justify-center shadow-inner">
                {{-- Container selebar kertas A4 --}}
                <div class="bg-white shadow-sm w-[210mm] shrink-0"
                    style="font-family: 'Times New Roman', Times, serif; color: black; padding: 0.5cm 1cm;">

                    <table
                        style="width: 100%; border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 10px;">
                        <tr>
                            <td style="width: 80px; text-align: center; vertical-align: middle;">
                                @if($pengaturan && $pengaturan->logo_path)
                                    <img src="{{ asset('storage/' . $pengaturan->logo_path) }}"
                                        style="width: 75px; height: auto;">
                                @else
                                    <img src="{{ asset('image/logo-pinrang.png') }}" style="width: 75px; height: auto;">
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <h2 style="margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase;">
                                    {{ $pengaturan->header_1 ?? 'PEMERINTAH KABUPATEN PINRANG' }}
                                </h2>
                                <h3 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">
                                    {{ $pengaturan->header_2 ?? 'KECAMATAN DUAMPANUA' }}
                                </h3>
                                <h2 style="margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase;">
                                    {{ $pengaturan->nama_desa ?? 'DESA BUTTU SAWE' }}
                                </h2>
                                <p style="margin: 0; font-size: 10pt; font-style: italic;">
                                    {{ $pengaturan->alamat ?? 'Alamat tidak diatur' }}
                                </p>
                            </td>
                            <td style="width: 80px;"></td> {{-- Spacer penyeimbang --}}
                        </tr>
                    </table>

                </div>
            </div>

            <a href="{{ route('admin.kelola-surat.edit-kop') }}"
                class="inline-flex bg-blue-50 text-blue-600 border border-blue-200 px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-600 hover:text-white transition-colors items-center justify-center gap-2 shadow-sm text-sm">
                <i class="fas fa-edit"></i> Edit Teks & Logo Kop
            </a>
        </div>

        {{-- BAGIAN TABEL TOGGLE DINAMIS --}}
        <div class="bg-white rounded-2xl shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50 border-b-2 border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No
                            </th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                                Layanan Surat</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="text-[0.95rem] text-gray-800">
                        @foreach($jenisSurats as $index => $surat)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50">
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $surat->nama_surat }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $surat->kategori }}</td>
                                <td class="px-6 py-4 text-center">
                                    <label class="switch">
                                        <input type="checkbox" onchange="toggleStatus({{ $surat->id }}, this.checked)" {{ $surat->is_active ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>
    @include('partials.sweetalert')
    <script type="module">
        // 1. Daftarkan ke window agar bisa dipanggil onclick
        window.toggleSidebar = function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        };

        // Konfigurasi SweetAlert Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // 2. Daftarkan ke window agar bisa dipanggil onchange
        window.toggleStatus = function (id, isActive) {
            fetch("{{ route('admin.kelola-surat.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: id,
                    is_active: isActive ? 1 : 0
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: isActive ? 'Layanan Surat Diaktifkan' : 'Layanan Dinonaktifkan'
                        });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Terjadi kesalahan pada server.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'Tidak dapat terhubung ke server. Cek console.', 'error');
                    console.error("Error Detail:", error);
                    setTimeout(() => window.location.reload(), 2000);
                });
        };
    </script>
</body>

</html>