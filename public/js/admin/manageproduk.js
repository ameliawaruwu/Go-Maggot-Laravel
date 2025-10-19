document.getElementById('searchProduct').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('.table-data table tbody tr');
    
    tableRows.forEach(row => {
        const productId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const productName = row.querySelector('td:nth-child(2) p').textContent.toLowerCase();
        const productCategory = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const productDescription = row.querySelector('td:nth-child(9)').textContent.toLowerCase();
        
        if (productId.includes(searchTerm) || 
            productName.includes(searchTerm) || 
            productCategory.includes(searchTerm) ||
            productDescription.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Category filter
document.getElementById('categoryFilter').addEventListener('change', function() {
    const selectedCategory = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('.table-data table tbody tr');
    
    tableRows.forEach(row => {
        const productCategory = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (selectedCategory === '' || productCategory === selectedCategory) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Function to show toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    
    // Set message
    toastMessage.textContent = message;
    
    // Set toast type
    toast.className = 'toast-notification';
    if (type === 'success') {
        toast.classList.add('toast-success');
        toastIcon.className = 'bx bx-check-circle toast-icon';
    } else if (type === 'error') {
        toast.classList.add('toast-error');
        toastIcon.className = 'bx bx-error-circle toast-icon';
    }
    
    // Show toast
    toast.style.display = 'flex';
    
    // Auto hide after 5 seconds
    setTimeout(function() {
        closeToast();
    }, 5000);
}

// Function to close toast
function closeToast() {
    document.getElementById('toast-notification').style.display = 'none';
}

// Check for success parameter in URL
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
        
        // Remove the status parameter from URL
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.replaceState({}, '', url);
    }

    // Add tooltips to description cells to show full text on hover
    const descriptionCells = document.querySelectorAll('.product-description');
    descriptionCells.forEach(cell => {
        cell.setAttribute('title', cell.textContent);
    });
});