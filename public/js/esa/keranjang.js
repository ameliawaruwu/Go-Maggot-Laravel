
// Dijalankan setelah DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    const listProduct = document.querySelector('.listProduct');
    const listCart = document.querySelector('.ListCart');
    const body = document.querySelector('body');
    const closeCartBtn = document.querySelector('.close');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const cartIcon = document.querySelector('.icon-cart');
    const cartQuantitySpan = document.querySelector('.icon-cart span');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const loadingCartMessage = document.getElementById('loadingCartMessage');

    // 🔐 ambil info login & csrf token dari layout
    const isLoggedIn = typeof window.isLoggedIn !== 'undefined' ? window.isLoggedIn : false;
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    let carts = []; 
    let products = [];

    // 🔍 DEBUG: Log status login dan role saat halaman dimuat
    console.log('=== STATUS USER SAAT LOAD ===');
    console.log('Login Status:', isLoggedIn);
    console.log('User Role (raw):', window.userRole);
    console.log('==============================');

    // --- Event Listeners ---

    cartIcon.addEventListener('click', () => {
        body.classList.toggle('showCart');
        loadCartItemsFromLocalStorage(); 
    });

    closeCartBtn.addEventListener('click', () => {
        body.classList.remove('showCart');
    });

    // ✅ PERBAIKAN: Tambah ke keranjang dengan validasi lengkap
    listProduct.addEventListener('click', (event) => {
        if (event.target.classList.contains('add-to-cart-btn')) {
            try {
                console.log('=== TOMBOL ADD TO CART DIKLIK ===');
                console.log('isLoggedIn:', isLoggedIn);
                console.log('window.userRole (raw):', window.userRole);

                // 1. CEK LOGIN
                if (!isLoggedIn) {
                    alert("Silakan login terlebih dahulu untuk berbelanja.");
                    window.location.href = '/login'; 
                    return;
                }
                
                // 2. CEK ROLE (DIPERBAIKI)
                // Ambil role, pastikan lowercase dan trim whitespace
                const currentRole = window.userRole ? 
                    window.userRole.toString().toLowerCase().trim() : 
                    'guest';
                
                // Daftar role yang boleh belanja (sesuaikan dengan database Anda)
                const allowedRoles = ["admin", "pelanggan", "customer"];
                
                console.log('Current Role (processed):', currentRole);
                console.log('Allowed Roles:', allowedRoles);
                console.log('Is Role Allowed?:', allowedRoles.includes(currentRole));

                // Validasi role
                if (!allowedRoles.includes(currentRole)) {
                    alert(`Akun Anda tidak memiliki akses untuk berbelanja.\n\nRole Anda: ${currentRole}\nRole yang diizinkan: ${allowedRoles.join(', ')}`);
                    console.log('❌ AKSES DITOLAK - Role tidak diizinkan');
                    return;
                }

                console.log('✅ VALIDASI ROLE BERHASIL');

                // 3. AMBIL DATA PRODUK dari data attributes
                const productData = {
                    idproduk: event.target.dataset.id,
                    namaproduk: event.target.dataset.nama,
                    harga: event.target.dataset.harga,
                    gambar: event.target.dataset.gambar
                };

                console.log('Product Data:', productData);

                // Validasi data produk lengkap
                if (!productData.idproduk || !productData.namaproduk || !productData.harga || !productData.gambar) {
                    console.error('❌ Data produk tidak lengkap:', productData);
                    alert('Terjadi kesalahan: Data produk tidak lengkap. Silakan refresh halaman.');
                    return;
                }

                // 4. TAMBAHKAN KE KERANJANG
                addToCartLocal(productData);
                
                // Feedback sukses
                alert(`${productData.namaproduk} berhasil ditambahkan ke keranjang!`);
                console.log(' BERHASIL DITAMBAHKAN KE KERANJANG');

            } catch (e) {
                console.error('❌ Error saat menambahkan ke keranjang:', e);
                alert('Terjadi kesalahan: ' + e.message);
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

    // ✅ Event listener tombol Checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (!isLoggedIn) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return;
            }

            if (carts.length > 0) {
                const redirectUrl = checkoutBtn.dataset.redirectUrl || '/checkout'; 
                syncCartToServer('/checkout/sync', redirectUrl); 
            } else {
                alert('Keranjang Anda kosong. Tidak dapat melanjutkan checkout.');
            }
        });
    }

    // --- Functions ---

    function syncCartToServer(syncUrl, redirectUrl) {
        if (carts.length === 0) {
            alert('Keranjang kosong. Tidak dapat melanjutkan.');
            return;
        }

        const originalText = checkoutBtn.innerText;
        
        checkoutBtn.innerText = 'Memproses...';
        checkoutBtn.setAttribute('disabled', 'true');

        fetch(syncUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ cart: carts }) 
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return null;
            }

            if (!response.ok) {
                throw new Error('Server returned status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;

            if (data.success) {
                window.location.href = redirectUrl; 
            } else {
                alert('Gagal menyinkronkan pesanan ke server. Coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error saat proses/sinkronisasi:', error);
            alert('Terjadi kesalahan koneksi atau server: ' + error.message);
        })
        .finally(() => {
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

    function loadCartItemsFromLocalStorage() {
        const storedCart = localStorage.getItem('shoppingCart');
        let loadedCarts = [];
        
        if (storedCart) {
            try {
                loadedCarts = JSON.parse(storedCart);
                
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