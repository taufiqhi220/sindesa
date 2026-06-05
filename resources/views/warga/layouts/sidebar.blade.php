@php
    // Ambil semua kode surat yang aktif
    $suratAktif = \App\Models\JenisSurat::where('is_active', true)->pluck('kode_surat')->toArray();

    // Cek apakah ada minimal 1 surat aktif di tiap kategori agar tombol dropdown tidak kosong
    $hasKependudukan = count(array_intersect(['akta-lahir', 'ktp', 'kk', 'kematian', 'pindah'], $suratAktif)) > 0;
    $hasUmum = count(array_intersect(['domisili', 'belum-menikah', 'janda-duda', 'beda-nama', 'kehilangan', 'skck'], $suratAktif)) > 0;
    $hasPerizinan = count(array_intersect(['usaha', 'izin-keramaian'], $suratAktif)) > 0;
    $hasSosial = count(array_intersect(['tidak-mampu', 'penghasilan'], $suratAktif)) > 0;
    $hasAnySurat = $hasKependudukan || $hasUmum || $hasPerizinan || $hasSosial;
@endphp

<style>
    /* CSS murni untuk menghilangkan scrollbar dengan aman */
    .hilangkan-scrollbar::-webkit-scrollbar { display: none; }
    .hilangkan-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<aside id="sidebar"
    class="fixed lg:sticky top-0 left-0 h-screen shrink-0 w-[280px] bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl">

    <div class="p-8 text-center border-b border-white/10 shrink-0">
        <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo" class="h-10 mx-auto">
    </div>

    <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 shrink-0">
        <div class="w-11 h-11 rounded-full flex shrink-0 items-center justify-center font-bold text-lg overflow-hidden border border-white/20 shadow-sm {{ Auth::user()->foto_profil ? 'bg-transparent' : 'bg-[#cfa03f]' }}">
            @if(Auth::user()->foto_profil)
                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="w-full h-full object-cover" alt="Profil">
            @else
                {{ substr(Auth::user()->name, 0, 1) }}
            @endif
        </div>
        <div class="overflow-hidden text-sm">
            <h4 class="font-semibold truncate">{{ Auth::user()->name }}</h4>
            <p class="text-[10px] opacity-70">NIK: {{ Auth::user()->nik ?? 'Belum diatur' }}</p>
        </div>
    </div>

    <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto hilangkan-scrollbar">

        <a href="{{ route('warga.dashboard') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('warga.dashboard') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-home w-5 text-center"></i> Beranda
        </a>

        @php 
            $isRiwayat = request()->routeIs('warga.riwayat', 'warga.verifikasi', 'warga.selesai'); 
        @endphp
        <div class="mb-4">
            <button onclick="toggleMenu('menu-riwayat', this)"
                class="w-full flex items-center justify-between p-3 rounded-lg transition-all group {{ $isRiwayat ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                <span class="flex items-center gap-3"><i class="fas fa-history w-5 text-center"></i> Riwayat & Status</span>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isRiwayat ? 'rotate-180' : '' }}"></i>
            </button>
            <div id="menu-riwayat" class="{{ $isRiwayat ? 'block' : 'hidden' }} bg-black/10 rounded-lg overflow-hidden ml-4 mt-1">
                
                <a href="{{ route('warga.riwayat') }}"
                    class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.riwayat') ? 'text-white font-bold pl-4' : 'text-white/70 hover:text-white hover:pl-4' }}">
                    <i class="fas fa-list mr-1"></i> Semua Pengajuan
                </a>
                
                <a href="{{ route('warga.verifikasi') }}"
                    class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.verifikasi') ? 'text-blue-300 font-bold pl-4' : 'text-white/70 hover:text-blue-300 hover:pl-4' }}">
                    <i class="fas fa-clock mr-1"></i> Diproses Operator
                </a>
                
                <a href="{{ route('warga.selesai') }}"
                    class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.selesai') ? 'text-emerald-400 font-bold pl-4' : 'text-white/70 hover:text-emerald-400 hover:pl-4' }}">
                    <i class="fas fa-check-circle mr-1"></i> Selesai
                </a>
                
            </div>
        </div>

        {{-- HANYA TAMPILKAN MENU BUAT SURAT JIKA AKUN AKTIF --}}
        @if(Auth::user()->status === 'active')

            @if($hasAnySurat)
                <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Buat Surat Baru</div>
            @endif

            {{-- MENU ADMINISTRASI KEPENDUDUKAN --}}
            @if($hasKependudukan)
                @php $isKependudukan = request()->routeIs('warga.form.akta-lahir*', 'warga.form.ktp*', 'warga.form.kk*', 'warga.form.kematian*', 'warga.form.pindah*'); @endphp
                <div>
                    <button onclick="toggleMenu('menu-kependudukan', this)"
                        class="w-full flex items-center justify-between p-3 rounded-lg transition-all group {{ $isKependudukan ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                        <span class="flex items-center gap-3"><i class="fas fa-users w-5 text-center"></i> Adm. Kependudukan</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isKependudukan ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="menu-kependudukan" class="{{ $isKependudukan ? 'block' : 'hidden' }} bg-black/10 rounded-lg overflow-hidden ml-4 mt-1">
                        @if(in_array('akta-lahir', $suratAktif))
                            <a href="{{ route('warga.form.akta-lahir') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.akta-lahir*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Pengantar Akta Kelahiran</a>
                        @endif
                        @if(in_array('ktp', $suratAktif))
                            <a href="{{ route('warga.form.ktp') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.ktp*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Pengantar KTP</a>
                        @endif
                        @if(in_array('kk', $suratAktif))
                            <a href="{{ route('warga.form.kk') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.kk*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Pengantar Kartu Keluarga</a>
                        @endif
                        @if(in_array('kematian', $suratAktif))
                            <a href="{{ route('warga.form.kematian') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.kematian*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Kematian</a>
                        @endif
                        @if(in_array('pindah', $suratAktif))
                            <a href="{{ route('warga.form.pindah') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.pindah*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Pindah</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- MENU KETERANGAN UMUM --}}
            @if($hasUmum)
                @php $isUmum = request()->routeIs('warga.form.domisili*', 'warga.form.belum-menikah*', 'warga.form.janda-duda*', 'warga.form.beda-nama*', 'warga.form.kehilangan*', 'warga.form.skck*'); @endphp
                <div>
                    <button onclick="toggleMenu('menu-umum', this)"
                        class="w-full flex items-center justify-between p-3 rounded-lg transition-all group {{ $isUmum ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                        <span class="flex items-center gap-3"><i class="fas fa-file-alt w-5 text-center"></i> Keterangan Umum</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isUmum ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="menu-umum" class="{{ $isUmum ? 'block' : 'hidden' }} bg-black/10 rounded-lg overflow-hidden ml-4 mt-1">
                        @if(in_array('domisili', $suratAktif))
                            <a href="{{ route('warga.form.domisili') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.domisili*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Domisili</a>
                        @endif
                        @if(in_array('belum-menikah', $suratAktif))
                            <a href="{{ route('warga.form.belum-menikah') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.belum-menikah*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Belum Menikah</a>
                        @endif
                        @if(in_array('janda-duda', $suratAktif))
                            <a href="{{ route('warga.form.janda-duda') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.janda-duda*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Janda / Duda</a>
                        @endif
                        @if(in_array('beda-nama', $suratAktif))
                            <a href="{{ route('warga.form.beda-nama') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.beda-nama*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Beda Nama</a>
                        @endif
                        @if(in_array('kehilangan', $suratAktif))
                            <a href="{{ route('warga.form.kehilangan') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.kehilangan*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Kehilangan</a>
                        @endif
                        @if(in_array('skck', $suratAktif))
                            <a href="{{ route('warga.form.skck') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.skck*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Pengantar SKCK</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- MENU LAYANAN PERIZINAN --}}
            @if($hasPerizinan)
                @php $isPerizinan = request()->routeIs('warga.form.usaha*', 'warga.form.izin-keramaian*'); @endphp
                <div>
                    <button onclick="toggleMenu('menu-perizinan', this)"
                        class="w-full flex items-center justify-between p-3 rounded-lg transition-all group {{ $isPerizinan ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                        <span class="flex items-center gap-3"><i class="fas fa-store w-5 text-center"></i> Layanan Perizinan</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isPerizinan ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="menu-perizinan" class="{{ $isPerizinan ? 'block' : 'hidden' }} bg-black/10 rounded-lg overflow-hidden ml-4 mt-1">
                        @if(in_array('usaha', $suratAktif))
                            <a href="{{ route('warga.form.usaha') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.usaha*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Usaha</a>
                        @endif
                        @if(in_array('izin-keramaian', $suratAktif))
                            <a href="{{ route('warga.form.izin-keramaian') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.izin-keramaian*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Pengantar Izin Keramaian</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- MENU SOSIAL & EKONOMI --}}
            @if($hasSosial)
                @php $isSosial = request()->routeIs('warga.form.tidak-mampu*', 'warga.form.penghasilan*'); @endphp
                <div>
                    <button onclick="toggleMenu('menu-sosial', this)"
                        class="w-full flex items-center justify-between p-3 rounded-lg transition-all group {{ $isSosial ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
                        <span class="flex items-center gap-3"><i class="fas fa-hands-helping w-5 text-center"></i> Sosial & Ekonomi</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isSosial ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="menu-sosial" class="{{ $isSosial ? 'block' : 'hidden' }} bg-black/10 rounded-lg overflow-hidden ml-4 mt-1">
                        @if(in_array('tidak-mampu', $suratAktif))
                            <a href="{{ route('warga.form.tidak-mampu') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.tidak-mampu*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Tidak Mampu</a>
                        @endif
                        @if(in_array('penghasilan', $suratAktif))
                            <a href="{{ route('warga.form.penghasilan') }}" class="block p-2.5 text-xs transition-all {{ request()->routeIs('warga.form.penghasilan*') ? 'text-[#cfa03f] font-bold pl-4' : 'text-white/70 hover:text-[#cfa03f] hover:pl-4' }}">Keterangan Penghasilan</a>
                        @endif
                    </div>
                </div>
            @endif

        @endif 
        {{-- END OF: HANYA TAMPILKAN MENU BUAT SURAT JIKA AKUN AKTIF --}}

        <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-6 px-3">Pengaturan</div>

        <a href="{{ route('warga.profil') ?? '#' }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('warga.profil') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-user-cog w-5 text-center"></i> Profil & Akun
        </a>

        <a href="{{ route('warga.bantuan') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all {{ request()->routeIs('warga.bantuan') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-book w-5 text-center"></i> Panduan Penggunaan
        </a>

    </nav>

    <div class="p-4 mt-auto border-t border-white/10 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 p-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer font-medium">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    if (typeof toggleMenu !== 'function') {
        function toggleMenu(menuId, element) {
            const submenu = document.getElementById(menuId);
            const icon = element.querySelector('.fa-chevron-down');
            submenu.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }

    // Global form validation error handler (mengatasi input file hidden yang tidak muncul tooltip browser)
    document.addEventListener('invalid', function (e) {
        e.preventDefault(); // Cegah default browser tooltip
        
        let errorMsg = e.target.validationMessage || 'Mohon periksa kembali. Lengkapi semua data wajib yang ditandai, termasuk file upload (jika ada).';
        let errorTitle = 'Data Belum Lengkap!';
        
        if (e.target.type === 'checkbox') {
            errorTitle = 'Persetujuan Diperlukan!';
        }

        Swal.fire({
            icon: 'warning',
            title: errorTitle,
            text: errorMsg,
            confirmButtonColor: '#1a5e35',
            confirmButtonText: 'Baik'
        });
    }, true);
</script>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memproses Data',
                html: `
                    <div class="text-left text-sm text-red-700 bg-red-50 p-4 rounded-xl mt-2 border border-red-200">
                        <p class="font-semibold mb-2">Terdapat beberapa kesalahan berikut:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#1a5e35',
                confirmButtonText: 'Baik, saya perbaiki',
                customClass: {
                    popup: 'rounded-2xl shadow-xl'
                }
            });
        });
    </script>
@endif