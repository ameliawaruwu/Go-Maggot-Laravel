// Dijalankan setelah DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    const listProduct = document.querySelector('.listProduct');
    const listCart = document.querySelector('.ListCart');
    const cartTab = document.querySelector('.cartTab');
    const body = document.querySelector('body');
    const closeCartBtn = document.querySelector('.close');
    const checkOutBtn = document.querySelector('.checkOut');
    const cartIcon = document.querySelector('.icon-cart');
    const cartQuantitySpan = document.querySelector('.icon-cart span');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const loadingCartMessage = document.getElementById('loadingCartMessage');

    let carts = []; 
    let products = [];

    // 🔥 Tambahan 1: refresh badge ketika halaman selesai load
    updateCartQuantityIcon();

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
            try {
                const productData = JSON.parse(event.target.dataset.productData);
                addToCartLocal(productData);
            } catch (e) {
                console.error("Error parsing product data:", e);
                alert("Produk berhasil ditambahkan.");
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

    checkOutBtn.addEventListener('click', () => {
        if (carts.length > 0) {
            window.location.href = "{{ route('checkout') }}";
        } else {
            alert('Keranjang Anda kosong. Tidak dapat melanjutkan checkout.');
        }
    });

    // --- Functions ---

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
        alert('Produk berhasil ditambahkan ke keranjang!');
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
    }

    function removeFromCartLocal(idProduk) {
        if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
            return;
        }
        carts = carts.filter(item => item.idproduk != idProduk);
        saveCartToLocalStorage();
        renderCart();
        updateCartQuantityIcon();
        alert('Produk berhasil dihapus dari keranjang.');
    }

    function saveCartToLocalStorage() {
        localStorage.setItem('shoppingCart', JSON.stringify(carts));
    }

    function loadCartItemsFromLocalStorage() {
        const storedCart = localStorage.getItem('shoppingCart');
        if (storedCart) {
            carts = JSON.parse(storedCart);
        } else {
            carts = [];
        }
        renderCart();
        updateCartQuantityIcon();
        updateCheckoutButtonState();
        loadingCartMessage.style.display = 'none';
    }

    function renderCart() {
        listCart.innerHTML = ''; 
        let totalQuantity = 0;
        let totalPrice = 0;

        if (carts.length > 0) {
            emptyCartMessage.style.display = 'none';
            carts.forEach(item => {
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
        if (carts.length > 0) {
            checkOutBtn.removeAttribute('disabled');
            checkOutBtn.style.opacity = '1';
            checkOutBtn.style.cursor = 'pointer';
        } else {
            checkOutBtn.setAttribute('disabled', 'true');
            checkOutBtn.style.opacity = '0.5';
            checkOutBtn.style.cursor = 'not-allowed';
        }
    }

    function processCheckout() {
    }

    // Inisialisasi awal
    loadCartItemsFromLocalStorage();

    // 🔥 Tambahan 2: refresh badge setelah load cart
    updateCartQuantityIcon();
});
