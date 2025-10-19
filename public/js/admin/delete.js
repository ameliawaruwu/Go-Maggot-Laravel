// delete 
// public/js/delete.js
// Script ini menangani konfirmasi penghapusan universal untuk semua resource admin.

document.addEventListener('DOMContentLoaded', function () {
    // Target utama: Form yang menggunakan class 'delete-form'
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        
        // Memastikan form delete menggunakan display inline untuk layout horizontal di tabel
        form.style.display = 'inline';

        // Menambahkan event listener ke form itu sendiri untuk menangkap submit
        form.addEventListener('submit', function (event) {
            // Mencegah pengiriman form default saat tombol submit diklik
            event.preventDefault();

            // Ambil nama item dari data-attribute yang diset di Blade.
            // Jika data-item-name tidak ada, gunakan default 'item ini'.
            const itemName = this.dataset.itemName || 'item ini';
            
            // Tampilkan dialog konfirmasi
            // Menggunakan template string untuk pesan yang spesifik
            const isConfirmed = confirm(`Apakah Anda yakin ingin menghapus ${itemName}? Aksi ini tidak dapat dibatalkan.`);

            // Jika user mengklik OK, submit form secara programatik
            if (isConfirmed) {
                this.submit();
            }
            // Jika user mengklik Cancel, tidak terjadi apa-apa
        });
    });
});

// User Management
document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua tombol dengan class 'delete-button'
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        // Tambahkan event listener untuk setiap tombol
        button.addEventListener('click', function (event) {
            // Mencegah pengiriman form default
            event.preventDefault();

            // Ambil ID dari atribut data-id (ini bisa berupa id_pelanggan atau id_galeri)
            const itemId = this.getAttribute('data-id');
            const form = this.closest('form');
            
            // Ambil nama user untuk pesan konfirmasi yang lebih spesifik
            const userName = form.closest('tr').querySelector('td[data-label="Nama User"]') ? form.closest('tr').querySelector('td[data-label="Nama User"]').textContent : 'Data Ini';

            // Tampilkan dialog konfirmasi
            const isConfirmed = confirm(`Apakah Anda yakin ingin menghapus ${userName} (ID: ${itemId})?`);

            // Jika user mengklik OK, submit form
            if (isConfirmed) {
                form.submit();
            }
        });
    });
});