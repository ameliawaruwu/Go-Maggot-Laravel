/**
 * File: public/js/checkout.js
 * Bertanggung jawab untuk logika sisi klien di halaman keranjang dan checkout.
 */

// URL endpoint
const INSTANT_PROCESS_URL = '/checkout/instant-process';
const CHECKOUT_INDEX_URL = '/checkout';

// Pastikan semua fungsi dijalankan setelah DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    
    // ===============================================
    // 1. Event Handler Tombol Checkout di Cart
    // ===============================================
    const checkoutButton = document.getElementById('checkoutBtn'); // <--- FIXED

    if (checkoutButton) {
        checkoutButton.addEventListener('click', handleInstantCheckout);
    }

    // ===============================================
    // 2. Logging Form Submit di Halaman Checkout
    // ===============================================
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const draftOrderIdInput = checkoutForm.querySelector('input[name="id_pesanan"]');
            
            if (draftOrderIdInput) {
                console.log("FORM SUBMIT: Data dikirim ke /checkout/process.");
                console.log("DRAFT ORDER ID:", draftOrderIdInput.value || 'Kosong');
            } else {
                console.warn("FORM SUBMIT: Input 'id_pesanan' tidak ditemukan.");
            }
        });
    }

    // Display cart (opsional)
    try {
        if (typeof displayCart === 'function') {
            displayCart();
        }
    } catch (e) {
        console.error("Error saat menjalankan displayCart:", e.message);
    }
});


/**
 * Handle checkout instan dari halaman keranjang
 */
function handleInstantCheckout(e) {
    e.preventDefault();
    const cart = getCartFromStorage();

    if (cart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }

    // Tampilkan loading
    const checkoutButton = document.getElementById('checkoutBtn'); // <--- FIXED
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
        if (checkoutButton) {
            checkoutButton.disabled = false;
            checkoutButton.textContent = 'Checkout';
        }
    });
}


// Fungsi pendukung
function getCartFromStorage() {
    try {
        const cartJson = localStorage.getItem('cart');
        return cartJson ? JSON.parse(cartJson) : [];
    } catch (e) {
        console.error("Gagal mengambil data keranjang dari localStorage:", e);
        return [];
    }
}

function displayCart() {
    const totalQtyElement = document.getElementById('totalProdukDisplay');
    const totalHargaElement = document.getElementById('totalHargaFormDisplay');

    if (!totalQtyElement || !totalHargaElement) {
        console.warn("Elemen total tidak ditemukan.");
    }
}
