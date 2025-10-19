// notifikasi
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('status')) {
        const status = urlParams.get('status');
        
        if (status === 'deleted') {
            showToast('Produk berhasil dihapus!', 'success');
        } else if (status === 'updated') {
            showToast('Produk berhasil diperbarui!', 'success');
        } else if (status === 'added') {
            showToast('Produk baru berhasil ditambahkan!', 'success');
        } else if (status === 'error') {
            showToast('Terjadi kesalahan dalam operasi!', 'error');
        }
        
        // Bersihkan parameter status dari URL
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.replaceState({}, '', url);
    }
    
});



// Script untuk menampilkan pratinjau gambar saat dropdown dipilih
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('gambar');
        const previewElement = document.getElementById('image-preview');
        const assetBase = '{{ asset("images/") }}';

        selectElement.addEventListener('change', function() {
            const selectedFileName = this.value;
            if (selectedFileName) {
                previewElement.src = assetBase + '/' + selectedFileName;
            } else {
                // Jika opsi "Pilih File Gambar" dipilih, kembalikan ke default atau sembunyikan
                previewElement.src = '{{ asset("images/default-product.jpg") }}'; 
            }
        });
    });