/**
 * File: public/js/checkout.js
 * Bertanggung jawab untuk logika sisi klien di halaman keranjang dan checkout.
 * * CATATAN PENTING: Logika displayCart() yang mengambil data dari URL telah dihapus
 * karena halaman checkout.index.blade.php sudah menerima data keranjang ($cartItems) 
 * langsung dari controller (Session Laravel).
 */

// URL endpoint
const INSTANT_PROCESS_URL = '/checkout/instant-process';
const CHECKOUT_INDEX_URL = '/checkout';

// Pastikan semua fungsi dijalankan setelah DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    
    // ===============================================
    // 1. Logika Event Handler Tombol Checkout (di Halaman Cart/Keranjang)
    //    Tombol ini biasanya ada di halaman keranjang (bukan checkout)
    // ===============================================

    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) {
        // Hanya tambahkan event listener jika tombol ditemukan
        checkoutButton.addEventListener('click', handleInstantCheckout);
    }
    
    // ===============================================
    // 2. Logging saat Form Checkout Index disubmit
    // ===============================================
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const draftOrderIdInput = checkoutForm.querySelector('input[name="id_pesanan"]');
            
            if (draftOrderIdInput) {
                console.log("FORM SUBMIT: Data dikirim ke /checkout/process.");
                console.log("DRAFT ORDER ID: ", draftOrderIdInput.value || 'Kosong');
            } else {
                console.warn("FORM SUBMIT: Input 'id_pesanan' tidak ditemukan.");
            }

            // Jika form submission diblok oleh validasi browser atau JS lain,
            // maka redirect tidak akan terjadi.
        });
    }

    // Pengecekan Fungsi Display Cart (Dibiarkan kosong untuk mencegah error 'Cannot set properties of null')
    try {
        if (typeof displayCart === 'function') {
            displayCart(); 
        }
    } catch (e) {
        console.error("Error saat menjalankan displayCart:", e.message);
    }
});

/**
 * Mengirim data keranjang (cart) ke endpoint instantProcess (AJAX)
 * untuk membuat Pesanan Draft. (Ini dipanggil dari halaman KERANJANG)
 */
function handleInstantCheckout(e) {
    e.preventDefault();
    const cart = getCartFromStorage(); // Asumsi: mengambil data dari localStorage/session JS
    
    if (cart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }

    // Tampilkan loading (jika ada)
    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) {
        checkoutButton.disabled = true;
        checkoutButton.textContent = 'Memproses...';
    }

    fetch(INSTANT_PROCESS_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ cart: cart })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // REDIRECT BERHASIL KE HALAMAN CHECKOUT (checkout.index)
            console.log(data.message);
            window.location.href = data.redirect_url; 
        } else {
            alert('Gagal memproses pesanan draft: ' + data.message);
            console.error('AJAX Error:', data);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan jaringan atau server. Cek koneksi Anda.');
        console.error('Fetch Error:', error);
    })
    .finally(() => {
        // Kembalikan tombol ke kondisi awal
        if (checkoutButton) {
            checkoutButton.disabled = false;
            checkoutButton.textContent = 'CHECKOUT';
        }
    });
}

// --- FUNGSI DUMMY / PENDUKUNG ---

/**
 * Mengambil data keranjang dari sumber sisi klien (misalnya localStorage).
 * Implementasi ini HARUS sesuai dengan cara Anda menyimpan keranjang di sisi klien.
 */
function getCartFromStorage() {
    // Implementasi yang Anda berikan di query tidak menggunakan ini,
    // tetapi ini adalah praktik yang baik jika tombol CHECKOUT dipanggil
    // dari halaman keranjang yang menyimpan data di localStorage.
    try {
        const cartJson = localStorage.getItem('cart');
        return cartJson ? JSON.parse(cartJson) : [];
    } catch (e) {
        console.error("Gagal mengambil data keranjang dari localStorage:", e);
        return [];
    }
}

/**
 * Fungsi ini dibiarkan KOSONG untuk menghindari error. 
 * Logika penampilan keranjang sudah ditangani oleh Laravel Blade.
 */
function displayCart() {
    // Logika penampilan keranjang sudah di handle oleh Blade (@include('components.cart-summary'))
    // Jika Anda ingin mengupdate total di bagian bawah form, gunakan querySelector yang tepat:

    const totalQtyElement = document.getElementById('totalProdukDisplay');
    const totalHargaElement = document.getElementById('totalHargaFormDisplay');
    
    // Contoh untuk memastikan elemen-elemen ini ada
    if (!totalQtyElement || !totalHargaElement) {
        console.warn("Elemen total (totalProdukDisplay atau totalHargaFormDisplay) tidak ditemukan di form.");
    }
}