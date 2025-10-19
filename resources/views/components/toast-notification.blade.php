{{-- File: resources/views/components/toast-notification.blade.php --}}

@if (session()->has('status_message'))
    @php
        // Ambil data dari session
        $message = session('status_message');
        $type = session('status_type') ?? 'success'; 
        
        // Atur warna header Toast berdasarkan tipe status
        $headerClass = match ($type) {
            'error' => 'bg-danger text-white',
            'warning' => 'bg-warning text-dark',
            'info' => 'bg-info text-white',
            default => 'bg-success text-white',
        };
        $icon = match ($type) {
            'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle',
            default => 'check-circle',
        };
        // Sesuaikan ikon jika Anda menggunakan Boxicons (bx)
        $bxIcon = match ($type) {
            'error' => 'bxs-x-circle',
            'warning' => 'bxs-error-alt',
            'info' => 'bxs-info-circle',
            default => 'bxs-check-circle',
        };
    @endphp

    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        
        {{-- Header Toast --}}
        <div class="toast-header {{ $headerClass }}">
            {{-- Menggunakan Boxicons (bx) --}}
            <i class='bx {{ $bxIcon }} me-2'></i> 
            <strong class="me-auto">{{ ucfirst($type) }}</strong>
            <small>Baru Saja</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        
        {{-- Body Toast --}}
        <div class="toast-body">
            {!! $message !!}
        </div>
    </div>

    {{-- JavaScript untuk menampilkan Toast secara otomatis --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                // Inisialisasi Toast Bootstrap (pastikan Bootstrap JS sudah dimuat)
                const toast = new bootstrap.Toast(toastEl, { delay: 5000 }); // Tampilkan 5 detik
                toast.show();
            }
        });
    </script>
    @endpush
@endif