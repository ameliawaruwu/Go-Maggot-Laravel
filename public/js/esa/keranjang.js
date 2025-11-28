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

    // 🔐 ambil info login & csrf token dari layout (jaga-jaga kalau undefined)
    const isLoggedIn = typeof window.isLoggedIn !== 'undefined' ? window.isLoggedIn : false;
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

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

    // ✅ Tambah ke keranjang dengan cek login dulu
    if (listProduct) {
        listProduct.addEventListener('click', (event) => {
            if (event.target.classList.contains('add-to-cart-btn')) {
                try {
                    if (!isLoggedIn) {
                        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                        return;
                    }
                    
                    if (window.userRole !== "admin" && window.userRole !== "pelanggan") {
                        alert("Akun Anda tidak memiliki akses untuk menambahkan keranjang.");
                        return;
                    }


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
                    alert("Terjadi kesalahan saat menambahkan produk ke keranjang.");
                }
            }
        });
    }

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

    // ✅ Event listener tombol Checkout yang memanggil sync + cek login
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            // 🔐 CEK LOGIN
            if (!isLoggedIn) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return;
            }

            if (carts.length > 0) {
                // Mengambil URL redirect dari atribut HTML (misal: data-redirect-url="/checkout")
                const redirectUrl = checkoutBtn.dataset.redirectUrl || '/checkout'; 
                
                // Panggil sinkronisasi ke /checkout/sync, lalu fungsi sync akan melakukan redirect
                syncCartToServer('/checkout/sync', redirectUrl); 
            } else {
                alert('Keranjang Anda kosong. Tidak dapat melanjutkan checkout.');
            }
        });
    }

    // --- Functions ---

    // FUNGSI: Sinkronisasi Keranjang ke Server
    function syncCartToServer(syncUrl, redirectUrl) { // Menerima dua parameter URL
        if (carts.length === 0) {
            alert('Keranjang kosong. Tidak dapat melanjutkan.');
            return;
        }

        const originalText = checkoutBtn.innerText;
        
        // Tampilkan status memproses
        checkoutBtn.innerText = 'Memproses...';
        checkoutBtn.setAttribute('disabled', 'true');

        fetch(syncUrl, { // fetch ke /checkout/sync
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ cart: carts }) 
        })
        .then(response => {
            // 🔐 Kalau backend balikin 401 (middleware auth nendang)
            if (response.status === 401) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return null; // hentikan chain
            }

            if (!response.ok) {
                // Tangkap error jika status HTTP bukan 200
                throw new Error('Server returned status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // sudah di-redirect (401)

            if (data.success) {
                window.location.href = redirectUrl; 
            } else {
                // Tampilkan pesan kegagalan dari Controller (jika ada)
                alert('Gagal menyinkronkan pesanan ke server. Coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error saat proses/sinkronisasi:', error);
            alert('Terjadi kesalahan koneksi atau server: ' + error.message);
        })
        .finally(() => {
            // Kembalikan status tombol hanya jika TIDAK terjadi redirect (yaitu, terjadi error)
            checkoutBtn.innerText = originalText;
            updateCheckoutButtonState(); 
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
        if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
            return;
        }
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
                // VALIDASI TAMBAHAN
                if (!item || !item.namaproduk || !item.idproduk) {
                    console.warn("Item korup dilewati:", item);
                    return;
                }

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
                
                // FILTRASI DAN SANITASI
                carts = loadedCarts.filter(item => 
                    item && 
                    item.idproduk && 
                    item.namaproduk && 
                    parseInt(item.jumlah) > 0 
                );

                if (loadedCarts.length !== carts.length) {
                    console.warn(`${loadedCarts.length - carts.length} item keranjang korup telah dibersihkan.`);
                    saveCartToLocalStorage();
                }

            } catch (e) {
                console.error("Error parsing stored cart:", e);
                carts = [];
                localStorage.removeItem('shoppingCart');
            }
        } else {
            carts = [];
        }
        
        renderCart();
        loadingCartMessage.style.display = 'none';
        updateCartQuantityIcon();
        updateCheckoutButtonState();
    }

    // Panggil inisialisasi
    loadCartItemsFromLocalStorage();
});
