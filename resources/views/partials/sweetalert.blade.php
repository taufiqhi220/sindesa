<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                confirmButtonColor: '#1a5e35',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        @if(session('error') || $errors->any())
            @php
                $errorMsg = session('error');
                if (!$errorMsg) {
                    $errorMsg = implode('<br>', $errors->all());
                }
            @endphp
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: {!! json_encode($errorMsg) !!},
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    });
</script>