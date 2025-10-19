document.addEventListener('DOMContentLoaded', function () {
    const actionButtons = document.querySelectorAll('.action-btn');
    const notificationBox = document.getElementById('notificationBox');
    
    // Ambil token CSRF dari meta tag (asumsi ada di layout)
    // PASTIKAN <meta name="csrf-token" content="{{ csrf_token() }}"> ADA DI LAYOUT UTAMA!
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
        // Hilangkan d-none untuk memunculkan box
        notificationBox.classList.remove('d-none', 'alert-success', 'alert-danger');
        notificationBox.classList.add(`alert-${type}`);
        
        // Atur isi notifikasi, termasuk tombol close
        notificationBox.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    }
    
    // Fungsi untuk menyembunyikan notifikasi
    function hideNotification() {
        notificationBox.classList.add('d-none');
    }

    // --- LOGIC AJAX UNTUK APPROVE/REJECT ---
    actionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reviewId = this.dataset.reviewId;
            const action = this.dataset.action; // 'approve' atau 'reject'
            
            // Konfirmasi sebelum aksi
            if (!confirm(`Anda yakin ingin ${action === 'approve' ? 'MENYETUJUI' : 'MENOLAK'} review ini? (ID: ${reviewId})`)) {
                return;
            }

            hideNotification(); // Sembunyikan notifikasi sebelumnya
            
            // 💡 PERBAIKAN: Ganti URL ke prefix 'managereview'
            const url = `/managereview/${reviewId}/status`; 
            
            fetch(url, {
                method: 'POST', // Menggunakan POST untuk mengirimkan _method: 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, 
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    status: action, // Key ini dibaca oleh Controller
                    _method: 'PUT' // Penting untuk diterima oleh Route::put di Laravel
                })
            })
            .then(response => {
                // Tangani error HTTP seperti 403, 404, 500
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    updateRowDisplay(reviewId, action);
                } else {
                    showNotification(data.message || 'Gagal mengubah status.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Menangani error umum atau error 403/419 jika CSRF gagal
                if (error.message.includes('403') || error.message.includes('419')) {
                     showNotification(`Aksi ditolak (Token Kedaluwarsa/CSRF). Silakan refresh halaman.`, 'danger');
                } else {
                     showNotification(`Terjadi kesalahan: ${error.message}`, 'danger');
                }
            });
        });
    });

    // Fungsi untuk mengupdate tampilan baris setelah aksi berhasil
    function updateRowDisplay(reviewId, newStatus) {
        // ... (Logika update tampilan baris Anda sudah benar) ...
        const statusBadge = document.getElementById(`status-badge-${reviewId}`).querySelector('span');
        const approveBtn = document.getElementById(`approve-btn-${reviewId}`);
        const rejectBtn = document.getElementById(`reject-btn-${reviewId}`);
        const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        
        statusBadge.textContent = statusText;
        statusBadge.className = 'badge p-2';
        
        if (newStatus === 'approved') {
            statusBadge.classList.add('bg-success', 'text-white');
            approveBtn.style.display = 'none';
            rejectBtn.style.display = 'inline-block';
        } else if (newStatus === 'rejected') {
            statusBadge.classList.add('bg-danger', 'text-white');
            approveBtn.style.display = 'inline-block';
            rejectBtn.style.display = 'none';
        } else if (newStatus === 'pending') {
            statusBadge.classList.add('bg-warning', 'text-dark');
            approveBtn.style.display = 'inline-block';
            rejectBtn.style.display = 'inline-block';
        }
    }

    // --- LOGIC UNIVERSAL DELETE (Dibiarkan, meskipun tombol di view sudah dihapus) ---
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const itemName = this.dataset.itemName;
            if (confirm(`Apakah Anda yakin ingin menghapus ${itemName} secara permanen? Aksi ini tidak bisa dibatalkan.`)) {
                this.submit();
            }
        });
    });
});