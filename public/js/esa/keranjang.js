// Dijalankan setelah DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    const listProduct = document.querySelector('.listProduct');
    const listCart = document.querySelector('.ListCart');
    const body = document.querySelector('body');
    const closeCartBtn = document.querySelector('.close');
    const checkoutBtn = document.getElementById('checkoutBtn'); // Menggunakan ID yang benar
    const cartIcon = document.querySelector('.icon-cart');
    const cartQuantitySpan = document.querySelector('.icon-cart span');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const loadingCartMessage = document.getElementById('loadingCartMessage');

    let carts = []; 
    let products = [];

    // --- Event Listeners ---

    cartIcon.addEventListener('click', () => {
        body.classList.toggle('showCart');
        loadCartItemsFromLocalStorage(); 
    });

    closeCartBtn.addEventListener('click', () => {
        body.classList.remove('showCart');
    });

    listProduct.addEventListener('click', (event) => {
        if (event.target.classList.contains('add-to-cart-btn')) {
            if (event.target.dataset.id) {
                try {
                    // Mengambil data dari atribut data-* yang terpisah
                    const productData = {
                        idproduk: event.target.dataset.id,
                        namaproduk: event.target.dataset.nama,
                        harga: parseFloat(event.target.dataset.harga),
                        gambar: event.target.dataset.gambar,
                    };
                    addToCartLocal(productData);
                } catch (e) {
                    console.error("Error adding product to cart:", e);
                    // alert("Produk berhasil ditambahkan."); // Dihapus: Hindari alert()
                }
            }
        }
    });

    listCart.addEventListener('click', (event) => {
        let positionClick = event.target;
        if (positionClick.classList.contains('minus')) {
            let idProduk = positionClick.closest('.item').dataset.id;
            changeQuantityLocal(idProduk, 'minus');
        } else if (positionClick.classList.contains('plus')) {
            let idProduk = positionClick.closest('.item').dataset.id;
            changeQuantityLocal(idProduk, 'plus');
        } else if (positionClick.classList.contains('remove-item-btn')) {
            let idProdukToRemove = positionClick.dataset.idProduk;
            removeFromCartLocal(idProdukToRemove);
        }
    });

    // Event listener tombol Checkout
if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
        if (carts.length > 0) {
            instantCheckout('/checkout/instant-process'); // Ganti dengan rute yang benar
        } else {
            // Seharusnya menggunakan modal kustom, bukan alert
            console.warn('Keranjang Anda kosong. Tidak dapat melanjutkan checkout.');
        }
    });
}

// --- FUNGSI BARU/REVISI: INSTANT CHECKOUT ---
function instantCheckout(instantProcessUrl) {
    if (carts.length === 0) {
        console.warn('Keranjang kosong. Tidak dapat melanjutkan.');
        return;
    }

    const originalText = checkoutBtn.innerText;
    
    // Tampilkan status memproses
    checkoutBtn.innerText = 'Memproses Pesanan...';
    checkoutBtn.setAttribute('disabled', 'true');

    fetch(instantProcessUrl, { // fetch ke /checkout/instant-process
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        },
        // Mengirim data keranjang langsung sebagai payload
        body: JSON.stringify({ cart: carts }) 
    })
    .then(response => {
        // --- DEBUGGING CRITICAL: Tangkap respons NON-JSON/NON-2XX ---
        if (!response.ok) {
            // Jika status bukan 2xx (misalnya 419, 500), coba baca sebagai teks (HTML)
            return response.text().then(text => {
                const status = response.status;
                
                if (text.startsWith('<!DOCTYPE html>')) {
                    console.error(`ERROR ${status}: Server merespons dengan HTML (Redirect/CSRF/Error 500).`);
                    console.error('SERVER RESPONSE (HTML/REDIRECT):', text);
                    throw new Error(`Server Error ${status}. Cek Console untuk detail HTML.`);
                }
                
                try {
                    // Coba parse sebagai JSON meskipun status non-ok (jika server mengirim error JSON)
                    const errorJson = JSON.parse(text);
                    throw new Error(errorJson.message || `Error ${status} tidak terdefinisi.`);
                } catch (e) {
                    // Jika gagal parsing, artinya respons non-JSON dan non-HTML murni
                    throw new Error(`Error ${status}: Respons server tidak terduga.`);
                }
            });
        }
        // Jika respons OK (2xx), lanjutkan membaca sebagai JSON
        return response.json();
    })
    .then(data => {
        if (data.success) { 
            // Hapus keranjang lokal setelah sukses
            localStorage.removeItem('shoppingCart');
            carts = []; 
            renderCart(); // Render ulang keranjang kosong
            // Redirect ke halaman sukses sesuai respons dari Controller
            window.location.href = data.redirect_url; 
        } else {
            // Alert diganti console.error untuk kepatuhan, harusnya pakai modal kustom
            console.error('Gagal Checkout Instan:', data.message);
            // Tambahkan alert sementara agar pengguna tahu, namun ini melanggar pedoman platform
            // alert('Gagal Checkout Instan: ' + data.message); 
        }
    })
    .catch(error => {
        console.error('Fatal Error saat Instant Checkout:', error);
        // Alert diganti console.error untuk kepatuhan, harusnya pakai modal kustom
        // alert('Terjadi kesalahan koneksi atau server: ' + error.message);
    })
    .finally(() => {
        // Hanya hapus disabled jika tombol ada
        if (checkoutBtn) {
            checkoutBtn.innerText = originalText;
            updateCheckoutButtonState(); 
        }
    });
}

    
    function addToCartLocal(product) {
        let positionInCart = carts.findIndex((value) => value.idproduk == product.idproduk);
        if (positionInCart < 0) {
            carts.push({
                idproduk: product.idproduk,
                namaproduk: product.namaproduk,
                harga: product.harga,
                gambar: product.gambar,
                jumlah: 1
            });
        } else {
            carts[positionInCart].jumlah++;
        }
        saveCartToLocalStorage();
        renderCart();
        updateCartQuantityIcon();
        // alert('Produk berhasil ditambahkan ke keranjang!');
    }

    function changeQuantityLocal(idProduk, type) {
        let positionInCart = carts.findIndex((value) => value.idproduk == idProduk);
        if (positionInCart >= 0) {
            if (type === 'plus') {
                carts[positionInCart].jumlah++;
            } else if (type === 'minus') {
                carts[positionInCart].jumlah--;
                if (carts[positionInCart].jumlah <= 0) {
                    carts.splice(positionInCart, 1);
                }
            }
        }
        saveCartToLocalStorage();
        renderCart();
        updateCartQuantityIcon();
        updateCheckoutButtonState();
    }

    function removeFromCartLocal(idProduk) {
        // PERHATIAN: Dialog confirm/alert dihapus. Gunakan modal UI kustom untuk konfirmasi.
        carts = carts.filter(item => item.idproduk != idProduk);
        saveCartToLocalStorage();
        renderCart();
        updateCartQuantityIcon();
        updateCheckoutButtonState();
    }

    function saveCartToLocalStorage() {
        localStorage.setItem('shoppingCart', JSON.stringify(carts));
    }

    function renderCart() {
        listCart.innerHTML = ''; 
        let totalQuantity = 0;
        let totalPrice = 0;

        if (carts.length > 0) {
            emptyCartMessage.style.display = 'none';
            carts.forEach(item => {
                // --- VALIDASI TAMBAHAN BARU ---
                if (!item || !item.namaproduk || !item.idproduk) {
                    console.warn("Item korup dilewati:", item);
                    return; // Skip item ini
                }
                // -----------------------------

                const quantity = parseInt(item.jumlah || 0);
                const price = parseFloat(item.harga || 0);
                const subtotal = quantity * price;

                totalQuantity += quantity;
                totalPrice += subtotal;

                let newDiv = document.createElement('div');
                newDiv.classList.add('item');
                newDiv.dataset.id = item.idproduk;

                newDiv.innerHTML = `
                    <div class="image">
                        <img src="${item.gambar}" alt="${item.namaproduk}">
                    </div>
                    <div class="name">${item.namaproduk}</div>
                    <div class="totalPrice">Rp.${(subtotal).toLocaleString('id-ID')}</div>
                    <div class="quantity">
                        <span class="minus">-</span>
                        <span>${quantity}</span>
                        <span class="plus">+</span>
                    </div>
                    <button class="remove-item-btn" data-id-produk="${item.idproduk}">Hapus</button>
                `;
                listCart.appendChild(newDiv);
            });
        } else {
            emptyCartMessage.style.display = 'block';
        }

        // Pastikan total harga dan kuantitas di-update setelah loop
        totalPriceDisplay.innerText = `Rp.${totalPrice.toLocaleString('id-ID')}`;
        cartQuantitySpan.innerText = totalQuantity;
        updateCheckoutButtonState();
    }

    function updateCartQuantityIcon() {
        let total = 0;
        carts.forEach(item => {
            total += parseInt(item.jumlah || 0);
        });
        cartQuantitySpan.innerText = total;
    }

    function updateCheckoutButtonState() {
        if (checkoutBtn) {
            if (carts.length > 0) {
                checkoutBtn.removeAttribute('disabled');
                checkoutBtn.style.opacity = '1';
                checkoutBtn.style.cursor = 'pointer';
            } else {
                checkoutBtn.setAttribute('disabled', 'true');
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.cursor = 'not-allowed';
            }
        }
    }

    // Inisialisasi awal
    function loadCartItemsFromLocalStorage() {
        const storedCart = localStorage.getItem('shoppingCart');
        let loadedCarts = [];
        
        if (storedCart) {
            try {
                loadedCarts = JSON.parse(storedCart);
                
                // --- FILTRASI DAN SANITASI BARU ---
                // Hanya simpan item yang memiliki ID dan Nama Produk yang valid
                carts = loadedCarts.filter(item => 
                    item && 
                    item.idproduk && 
                    item.namaproduk && 
                    parseInt(item.jumlah) > 0 
                );

                // Jika ada data yang tidak valid, simpan versi yang sudah bersih ke LocalStorage
                if (loadedCarts.length !== carts.length) {
                    console.warn(`${loadedCarts.length - carts.length} item keranjang korup telah dibersihkan.`);
                    saveCartToLocalStorage();
                }

            } catch (e) {
                console.error("Error parsing stored cart:", e);
                // Jika parsing gagal total, reset keranjang
                carts = [];
                localStorage.removeItem('shoppingCart');
            }
        } else {
            carts = [];
        }
        
        renderCart(); // Render keranjang yang sudah bersih
        loadingCartMessage.style.display = 'none';
        updateCartQuantityIcon();
        updateCheckoutButtonState();
    }

    // Panggil inisialisasi
    loadCartItemsFromLocalStorage();
});