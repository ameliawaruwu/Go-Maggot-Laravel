const toggle = document.querySelectorAll('.faq-toggle');

toggle.forEach(toggle => {
    toggle.addEventListener('click', () => {
        toggle.parentNode.classList.toggle('active');
    })
})

// Ambil elemen yang diperlukan
const cancelButton = document.querySelector('input[value="Batal"]');
const sendButton = document.querySelector('input[value="Kirim"]');
const questionBoxInput = document.getElementById('box');

// Event listener untuk tombol Batal
cancelButton.addEventListener('click', function() {
    // Kosongkan input
    questionBoxInput.value = '';
});

// Event listener untuk tombol Kirim
sendButton.addEventListener('click', function() {
    const question = questionBoxInput.value.trim(); // Ambil nilai input dan hapus spasi di awal/akhir
    if (question) {
        // Tampilkan pertanyaan di konsol (atau lakukan aksi lain)
        console.log('Pertanyaan yang dikirim:', question);
        // Kosongkan input setelah mengirim
        questionBoxInput.value = '';
    } else {
        alert('Silakan masukkan pertanyaan sebelum mengirim.'); // Peringatan jika input kosong
    }
});