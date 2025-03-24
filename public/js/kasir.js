
document.addEventListener("DOMContentLoaded", function () {
    tutupModal(); // Tutup modal saat halaman dimuat

    let sidebar = document.getElementById("sidebar");
    let overlay = document.getElementById("overlay");
    let toggleButton = document.getElementById("sidebarToggle");

    function toggleSidebar() {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("show");
    }

    if (toggleButton) {
        toggleButton.addEventListener("click", function (event) {
            event.stopPropagation();
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function () {
            toggleSidebar();
        });
    }

    const form = document.getElementById("formPembayaran");
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            let modalSuccess = document.getElementById("modalNotifikasi") ? new bootstrap.Modal(document.getElementById("modalNotifikasi")) : null;
            let modalError = document.getElementById("modalError") ? new bootstrap.Modal(document.getElementById("modalError")) : null;

            let isSuccess = Math.random() > 0.2;

            if (isSuccess) {
                if (modalSuccess) modalSuccess.show();
                form.reset();
                daftarPesanan = [];
                updateDaftarPesanan();
                tutupModal();
            } else {
                if (modalError) modalError.show();
            }
        });
    }
});

// Pastikan Bootstrap tersedia sebelum memanggil modal
document.getElementById('tombolKonfirmasi').addEventListener('click', function () {
    if (typeof bootstrap !== "undefined") {
        var modalLoading = new bootstrap.Modal(document.getElementById('modalLoading'));
        modalLoading.show();
        setTimeout(() => {
            document.getElementById('formPembayaran').submit();
        }, 1500);
    } else {
        console.error("Bootstrap is not defined.");
    }
});

let daftarPesanan = [];

document.getElementById('tombolKonfirmasi').addEventListener('click', function () {
    // Tampilkan modal loading
    var modalLoading = new bootstrap.Modal(document.getElementById('modalLoading'));
    modalLoading.show();

    // Kirim form setelah jeda untuk simulasi loading
    setTimeout(() => {
        document.getElementById('formPembayaran').submit();
    }, 1500);
});


// Fungsi untuk menambahkan pesanan
// Fungsi untuk menambahkan pesanan
function tambahKePesanan(id, nama, harga) {
    let index = daftarPesanan.findIndex(item => item.id === id);
    if (index !== -1) {
        daftarPesanan[index].qty++;
        daftarPesanan[index].total = daftarPesanan[index].qty * harga;
    } else {
        daftarPesanan.push({ id, nama, qty: 1, total: harga });
    }
    updateDaftarPesanan();
}


// Fungsi untuk menghapus pesanan
function hapusPesanan(index) {
    if (daftarPesanan[index].qty > 1) {
        daftarPesanan[index].qty--; // Kurangi jumlah item
        daftarPesanan[index].total -= daftarPesanan[index].total / (daftarPesanan[index].qty + 1);
    } else {
        daftarPesanan.splice(index, 1); // Hapus item jika qty 0
    }
    updateDaftarPesanan();
}

// Fungsi untuk update daftar pesanan di tampilan
// Fungsi untuk update daftar pesanan di tampilan
function updateDaftarPesanan() {
    let daftarPesananEl = document.getElementById('daftarPesanan');
    let totalHarga = 0;
    daftarPesananEl.innerHTML = '';

    daftarPesanan.forEach((item, index) => {
        totalHarga += item.total;
        daftarPesananEl.innerHTML += `
        <tr>
            
            <td>${item.nama}</td>
            <td>
                <button class="qty-btn minus" onclick="kurangiPesanan(${index})">−</button>
                ${item.qty}
                <button class="qty-btn plus" onclick="tambahPesanan(${index})">+</button>
            </td>
            <td>Rp ${item.total.toLocaleString()}</td>
            <td><button class="qty-btn minus" onclick="hapusPesanan(${index})">🗑</button></td>
        </tr>
    `;
    });

    document.getElementById('totalHarga').textContent = totalHarga.toLocaleString();
    document.getElementById('inputPesanan').value = JSON.stringify(daftarPesanan);  // Pastikan id termasuk
    document.getElementById('inputTotalHarga').value = totalHarga;
}

// Fungsi untuk menambah jumlah pesanan dari daftar
function tambahPesanan(index) {
    daftarPesanan[index].qty++;
    daftarPesanan[index].total = daftarPesanan[index].qty * (daftarPesanan[index].total / (daftarPesanan[index].qty - 1));
    updateDaftarPesanan();
}

// Fungsi untuk mengurangi jumlah pesanan dari daftar
function kurangiPesanan(index) {
    if (daftarPesanan[index].qty > 1) {
        daftarPesanan[index].qty--;
        daftarPesanan[index].total -= daftarPesanan[index].total / (daftarPesanan[index].qty + 1);
    } else {
        daftarPesanan.splice(index, 1);
    }
    updateDaftarPesanan();
}

// Fungsi untuk membuka modal pembayaran
function bukaModal() {
    document.getElementById("modalPembayaran").style.display = "block";
}

// Fungsi untuk menutup modal pembayaran
function tutupModal() {
    document.getElementById("modalPembayaran").style.display = "none";
    document.getElementById("modalNotifikasi").style.display = "none";
    document.getElementById("modalLoading").style.display = "none";
}
// Fungsi untuk menampilkan/menghilangkan input bukti pembayaran
function toggleBuktiPembayaran() {
    let metode = document.getElementById("metodePembayaran").value;
    let buktiField = document.getElementById("buktiPembayaranField");
    buktiField.style.display = (metode === "qris") ? "block" : "none";
}
