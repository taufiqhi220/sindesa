<div id="printModal"
    class="fixed inset-0 bg-black/60 z-[1050] hidden flex-col items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <form action="{{ route('kades.surat.proses', $surat->id) }}" method="POST"
        class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300"
        id="printModalContent">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="selesai">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-[#1a5e35] flex items-center gap-2"><i class="fas fa-pen-fancy"></i>
                Opsi Penandatanganan</h3>
            <button type="button" onclick="closePrintModal()"
                class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none"><i
                    class="fas fa-times text-lg"></i></button>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-500 mb-6">Pilih metode penandatanganan sebelum menyetujui surat ini.</p>
            <div class="space-y-4">
                <!-- Opsi 1: QR Code -->
                <label
                    class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                    <input type="radio" name="ttd_method" value="digital" class="sr-only" checked>
                    <div class="flex items-center gap-4 w-full">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <i class="fas fa-qrcode text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm">Tanda Tangan Elektronik (QR Code)</p>
                            <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyematkan QR Code validasi
                                langsung ke dokumen PDF.</p>
                        </div>
                        <div
                            class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                            <div
                                class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Opsi 2: TTD Konvensional -->
                <label
                    class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                    <input type="radio" name="ttd_method" value="konvensional" class="sr-only">
                    <div class="flex items-center gap-4 w-full">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-signature text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm">TTD Konvensional (Gambar)</p>
                            <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyematkan gambar scan tanda
                                tangan Kades ke dokumen PDF.</p>
                        </div>
                        <div
                            class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                            <div
                                class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Opsi 3: TTD Basah (Manual) -->
                <label
                    class="group relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-[#cfa03f] transition-all has-[:checked]:border-[#cfa03f] has-[:checked]:bg-orange-50/50">
                    <input type="radio" name="ttd_method" value="manual" class="sr-only">
                    <div class="flex items-center gap-4 w-full">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-pen-nib text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm">TTD Basah (Kosong)</p>
                            <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menyiapkan ruang kosong pada
                                dokumen untuk ditandatangani pulpen.</p>
                        </div>
                        <div
                            class="ml-4 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-has-[:checked]:border-[#cfa03f] transition-all">
                            <div
                                class="h-2.5 w-2.5 rounded-full bg-[#cfa03f] scale-0 group-has-[:checked]:scale-100 transition-transform duration-200">
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/50">
            <button type="button" onclick="closePrintModal()"
                class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors text-sm shadow-sm">Batal</button>
            <button type="submit"
                class="px-5 py-2.5 bg-[#cfa03f] text-white rounded-xl font-bold hover:bg-[#b88e32] transition-colors flex items-center gap-2 shadow-md text-sm">
                <i class="fas fa-check-circle"></i> Proses Sekarang
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function validasiTolak() {
        const pesan = document.getElementById('catatanPenolakan');
        if (pesan.value.trim() === "") {
            Swal.fire({
                icon: 'error',
                title: 'Alasan Belum Diisi',
                text: 'Wajib memberikan alasan penolakan agar operator/warga mengetahui kekurangan berkas.',
                confirmButtonColor: '#d33',
            });
            pesan.focus();
            return false;
        }
        return true;
    }

    function openPrintModal() {
        document.getElementById('catatanPenolakan').removeAttribute('required');
        const modal = document.getElementById('printModal');
        const content = document.getElementById('printModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closePrintModal() {
        document.getElementById('catatanPenolakan').setAttribute('required', 'required');
        const modal = document.getElementById('printModal');
        const content = document.getElementById('printModalContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>