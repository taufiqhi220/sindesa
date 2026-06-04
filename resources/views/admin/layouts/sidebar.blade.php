{{-- Overlay untuk Mobile --}}
<div id="overlay" class="fixed inset-0 bg-black/50 z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>

{{-- Sidebar Container --}}
<aside id="sidebar"
    class="fixed inset-y-0 left-0 w-[280px] h-screen bg-gradient-to-b from-[#11442b] to-[#1a5e35] text-white transition-transform duration-300 transform -translate-x-full lg:translate-x-0 z-[1000] flex flex-col shadow-xl overflow-y-auto">

    <div class="p-8 text-center border-b border-white/10 flex justify-center shrink-0">
        <img src="{{ asset('image/SINDESA_WHITE_TRANSPARNT.png') }}" alt="SINDESA Logo"
            class="h-10 mx-auto block object-contain">
    </div>

    <div class="m-4 p-5 bg-white/10 rounded-xl flex items-center gap-4 border border-white/5 shadow-inner">
        <div
            class="w-11 h-11 bg-[#cfa03f] rounded-full flex shrink-0 items-center justify-center font-bold text-lg text-white overflow-hidden shadow-md">
            @if(Auth::user() && Auth::user()->foto_profil)
                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Profil"
                    class="w-full h-full object-cover">
            @else
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            @endif
        </div>
        <div class="overflow-hidden text-sm">
            <h4 class="font-semibold truncate">{{ Auth::user()->name ?? 'Administrator' }}</h4>
            <p class="text-[10px] opacity-70 uppercase tracking-wider font-medium">Administrator</p>
        </div>
    </div>

    <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
        </a>

        <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Database Pengguna</div>

        <a href="{{ route('admin.data-warga') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.data-warga*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-users w-5 text-center"></i> Data Warga
        </a>

        <a href="{{ route('admin.data-operator') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.data-operator*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-user-shield w-5 text-center"></i> Data Operator
        </a>

        <a href="{{ route('admin.data-kades') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.data-kades*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-user-tie w-5 text-center"></i> Data Kepala Desa
        </a>

        <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Manajemen Konten</div>

        <a href="{{ route('admin.kelola-surat') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.kelola-surat*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-file-contract w-5 text-center"></i> Kelola Jenis Surat
        </a>

        <a href="{{ route('admin.pusat-bantuan') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.pusat-bantuan*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-question-circle w-5 text-center"></i> Pusat Bantuan
        </a>

        <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 mt-4 px-3">Sistem</div>

        <a href="{{ route('admin.pengaturan') }}"
            class="flex items-center gap-3 p-3 rounded-lg transition-all mb-1 {{ request()->routeIs('admin.pengaturan*') ? 'bg-[#cfa03f] text-white font-medium shadow-md' : 'text-white/80 hover:bg-[#cfa03f] hover:text-white' }}">
            <i class="fas fa-cogs w-5 text-center"></i> Pengaturan Sistem
        </a>

        <a href="{{ route('admin.log-aktivitas') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.log-aktivitas') ? 'bg-[#cfa03f] text-white shadow-md' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-history w-6"></i>
            <span class="font-medium">Log Aktivitas</span>
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