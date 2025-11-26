document.addEventListener("DOMContentLoaded", function () {
    // Ambil data dari variabel global yang dikirim dari Blade
    const data = window.analyticsData;

    // 1. Sales Chart (Line Chart)
    const ctxSales = document.getElementById("salesChart").getContext("2d");
    new Chart(ctxSales, {
        type: "line",
        data: {
            labels: data.dates,
            datasets: [{
                label: "Pendapatan (Rp)",
                data: data.sales,
                borderColor: "#4e73df",
                backgroundColor: "rgba(78, 115, 223, 0.1)",
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (value) => 'Rp ' + value.toLocaleString('id-ID') } }
            }
        }
    });

    // 2. Visitor Chart (Bar Chart - New Users)
    const ctxVisitor = document.getElementById("visitorsChart").getContext("2d");
    new Chart(ctxVisitor, {
        type: "bar",
        data: {
            labels: data.dates,
            datasets: [{
                label: "User Baru",
                data: data.visitors,
                backgroundColor: "#1cc88a",
                hoverBackgroundColor: "#17a673",
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 3. Top Products Chart (Horizontal Bar - Stock Level)
    const ctxProducts = document.getElementById("topProductsChart").getContext("2d");
    new Chart(ctxProducts, {
        type: "bar",
        data: {
            labels: data.products,
            datasets: [{
                label: "Sisa Stok",
                data: data.stocks,
                backgroundColor: "#36b9cc",
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal Bar
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // 4. Order Status Chart (Doughnut Chart)
    const ctxStatus = document.getElementById("userOrderStatusChart").getContext("2d");
    new Chart(ctxStatus, {
        type: "doughnut",
        data: {
            labels: data.statusLabels,
            datasets: [{
                data: data.statusCounts,
                backgroundColor: [
                    "#f6c23e", // Kuning (Menunggu)
                    "#36b9cc", // Biru Muda (Diproses)
                    "#4e73df", // Biru (Dikirim)
                    "#1cc88a", // Hijau (Selesai)
                    "#e74a3b"  // Merah (Batal)
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});