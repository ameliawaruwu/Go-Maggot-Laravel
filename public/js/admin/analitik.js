 // CHART SALES
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Untuk menyesuaikan tinggi
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
    
    // CHART VISITORS
    const visitorsCtx = document.getElementById('visitorsChart');
    if (visitorsCtx) {
        new Chart(visitorsCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Visitors',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false // Untuk menyesuaikan tinggi
            }
        });
    }

    // CHART PRODUK PALING LAKU (Pie Chart)
    const topProductsCtx = document.getElementById('topProductsChart');
    if (topProductsCtx) {
        new Chart(topProductsCtx, {
            type: 'pie',
            data: {
                labels: ['Kandang Maggot', 'Paket Bundling', 'Kompos Maggot', 'Bibit Maggot'],
                datasets: [{
                    label: 'Penjualan Produk',
                    data: [40, 30, 15, 15], 
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false, // Untuk menyesuaikan tinggi
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false }
                }
            }
        });
    }

    // CHART STATUS ORDER USER (Doughnut Chart)
    const userOrderStatusCtx = document.getElementById('userOrderStatusChart');
    if (userOrderStatusCtx) {
        new Chart(userOrderStatusCtx, {
            type: 'doughnut',
            data: {
                // Label disesuaikan dengan status baru
                labels: ['Processing', 'Completed', 'Pending', 'Shipped'], 
                datasets: [{
                    label: 'Jumlah Order',
                    // Data disesuaikan (harap ganti dengan data aktual)
                    data: [25, 35, 15, 25], 
                    // Warna disesuaikan dengan status baru
                    backgroundColor: [
                        'rgba(23, 162, 184, 0.7)',  // Processing (Cyan/Info)
                        'rgba(40, 167, 69, 0.7)',   // Completed (Hijau/Success)
                        'rgba(255, 193, 7, 0.7)',   // Pending (Kuning/Warning)
                        'rgba(0, 123, 255, 0.7)'    // Shipped (Biru/Primary)
                    ],
                    hoverOffset: 4
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false, // Untuk menyesuaikan tinggi
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    }