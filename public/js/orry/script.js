// SHOW MENU
let line = document.querySelector('.navbar .ri-menu-line');
let menu = document.querySelector('.navbar ul');

if (line) { // Pastikan elemen ditemukan sebelum menambahkan event listener
    line.addEventListener('click', () => {
        menu.classList.toggle('showmenu');
    });
} else {
    console.warn("Elemen '.navbar .ri-menu-line' tidak ditemukan.");
}

window.addEventListener('scroll', () => {
    let nav = document.querySelector('.navbar');
    if (nav) { // Pastikan elemen ditemukan
        if (window.scrollY > 50) {
            nav.classList.add('navbarSticky');
        } else {
            nav.classList.remove('navbarSticky');
        }
    }
});

// BUTTON (Pastikan ID tombol unik dan sesuai di HTML)
document.addEventListener("DOMContentLoaded", function () {
    const setupButton = (id, url) => {
        const button = document.getElementById(id);
        if (button) {
            button.addEventListener('click', () => {
                window.location.href = url;
            });
        } else {
            // console.warn(`Tombol dengan ID '${id}' tidak ditemukan.`); // Uncomment untuk debugging
        }
    };

    setupButton('myButton', 'https://youtu.be/FPALstZU7fI?si=i_tNPEYZpE1yItQh');
    setupButton('myButtonn', '/contact');
    setupButton('myButtonnn', '../gallery.gallery');
    setupButton('myButonnnn', '/galeri');
});


// Fungsi untuk mengatur bintang yang dipilih dan menyimpan nilai ke input tersembunyi
function handleStarRating(starContainerId, hiddenInputId) {
    const starsContainer = document.getElementById(starContainerId);
    if (!starsContainer) {
        console.error(`Elemen kontainer bintang dengan ID '${starContainerId}' tidak ditemukan.`);
        return;
    }

    const stars = starsContainer.querySelectorAll(".star");
    const hiddenInput = document.getElementById(hiddenInputId);
    if (!hiddenInput) {
        console.warn(`Input tersembunyi dengan ID '${hiddenInputId}' tidak ditemukan untuk ${starContainerId}. Rating tidak akan disimpan.`);
        // Biarkan proses berlanjut, tapi rating tidak akan tersimpan jika input tidak ada
    }

    // Fungsi untuk menyorot bintang
    function highlightStars(starsToHighlight, value) {
        starsToHighlight.forEach((star) => {
            const starValue = parseInt(star.getAttribute("data-value"));
            if (starValue <= value) {
                star.classList.add("selected");
            } else {
                star.classList.remove("selected");
            }
        });
    }

    stars.forEach((star) => {
        // Event listener untuk efek hover
        star.addEventListener("mouseover", () => {
            const value = parseInt(star.getAttribute("data-value"));
            highlightStars(stars, value);
        });

        // Event listener untuk mereset efek hover
        starsContainer.addEventListener("mouseleave", () => {
            const selectedValue = starsContainer.getAttribute("data-selected-value");
            highlightStars(stars, parseInt(selectedValue || 0)); // Gunakan 0 jika belum ada selectedValue
        });

        // Event listener untuk klik bintang
        star.addEventListener("click", () => {
            const value = parseInt(star.getAttribute("data-value"));
            starsContainer.setAttribute("data-selected-value", value); // Simpan nilai di atribut data
            highlightStars(stars, value);
            if (hiddenInput) {
                hiddenInput.value = value; // Simpan rating yang dipilih ke input tersembunyi
            }
        });
    });

    // Inisialisasi tampilan bintang berdasarkan nilai yang sudah tersimpan (jika ada)
    // Misalnya jika nilai sudah ada dari PHP atau refresh halaman
    const initialSelectedValue = parseInt(starsContainer.getAttribute("data-selected-value") || (hiddenInput ? hiddenInput.value : 0) || 0);
    highlightStars(stars, initialSelectedValue);
}

// --- FILE UPLOAD FEEDBACK ---
// Fungsi untuk memperbarui tampilan tombol setelah file dipilih
function updateMediaButtonFeedback(inputElement, buttonElement, iconElement, type) {
    inputElement.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const fileName = this.files[0].name;
            console.log(`File ${type} Terpilih: ${fileName}`);

            // Cari atau buat span untuk nama file
            let fileNameDisplay = buttonElement.querySelector('.file-name-display');
            if (!fileNameDisplay) {
                fileNameDisplay = document.createElement('span');
                fileNameDisplay.className = 'file-name-display';
                buttonElement.appendChild(fileNameDisplay);
            }
            fileNameDisplay.textContent = ' ' + fileName; // Tambahkan spasi di depan

            // Ubah ikon dan warna untuk indikasi berhasil dipilih
            if (iconElement) {
                iconElement.className = 'ri-check-circle-line'; // Ikon centang
                iconElement.style.color = 'green';
            }

            // Tambahkan class untuk styling CSS kustom
            buttonElement.classList.add('has-file');

        } else {
            // Jika tidak ada file yang dipilih (misal user membatalkan dialog)
            console.log(`Tidak ada file ${type} yang dipilih.`);
            let fileNameDisplay = buttonElement.querySelector('.file-name-display');
            if (fileNameDisplay) {
                fileNameDisplay.remove(); // Hapus nama file
            }
            if (iconElement) {
                if (type === 'photo') {
                    iconElement.className = 'ri-image-line'; // Kembali ke ikon asli
                } else if (type === 'video') {
                    iconElement.className = 'ri-video-line'; // Kembali ke ikon asli
                }
                iconElement.style.color = ''; // Reset warna
            }
            buttonElement.classList.remove('has-file');
        }
    });
}

// --- DOMContentLoaded Event Listener Utama ---
document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi rating bintang
    handleStarRating("product-rating", "rating_produk");
    handleStarRating("seller-service-rating", "rating_seller");
    // handleStarRating("delivery-speed-rating", "rating_delivery"); // Aktifkan jika Anda punya rating jasa kirim

    // Hubungkan tombol custom dengan input file tersembunyi
    const addPhotoButton = document.getElementById('addPhotoButton');
    const photoInput = document.getElementById('photoInput');
    const photoIcon = addPhotoButton ? addPhotoButton.querySelector('i') : null;

    if (addPhotoButton && photoInput) {
        addPhotoButton.addEventListener('click', () => {
            photoInput.click(); // Memicu klik pada input file asli
        });
        updateMediaButtonFeedback(photoInput, addPhotoButton, photoIcon, 'photo'); // Tambahkan feedback visual
    }

    const addVideoButton = document.getElementById('addVideoButton');
    const videoInput = document.getElementById('videoInput');
    const videoIcon = addVideoButton ? addVideoButton.querySelector('i') : null;

    if (addVideoButton && videoInput) {
        addVideoButton.addEventListener('click', () => {
            videoInput.click(); // Memicu klik pada input file asli
        });
        updateMediaButtonFeedback(videoInput, addVideoButton, videoIcon, 'video'); // Tambahkan feedback visual
    }

    console.log("Halaman Pusat Bantuan telah dimuat.");
});

// --- SHOW HIDE PASSWORD ---
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password ion-icon');

    if (passwordInput && toggleIcon) { // Pastikan elemen ada
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.setAttribute('name', 'eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.setAttribute('name', 'eye-off');
        }
    } else {
        console.warn("Elemen input password atau toggle icon tidak ditemukan.");
    }
}

// --- PROFILE DROPDOWN ---
function toggleDropdown() {
    var dropdown = document.getElementById("profileDropdown");
    if (dropdown) { // Pastikan elemen ada
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    } else {
        console.warn("Elemen 'profileDropdown' tidak ditemukan.");
    }
}