document.addEventListener("DOMContentLoaded", function () {
    tutupModal(); // Tutup semua modal saat halaman dimuat

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const toggleButton = document.getElementById("sidebarToggle");

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

    // Tanggal real-time
    setInterval(updateTanggal, 1000);
    updateTanggal();


});



function updateTanggal() {
    const now = new Date();
    const hari = now.toLocaleDateString('id-ID', { weekday: 'long' });
    const tanggal = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    const waktu = now.toLocaleTimeString('id-ID');
    document.getElementById('tanggalSekarang').innerText = `${hari}, ${tanggal} | ${waktu}`;
}

// ======================= PESANAN ======================

let daftarPesanan = [];
let salinanStruk = []; 

function tambahKePesanan(id, nama, harga) {
    const sound = document.getElementById('soundBeep');
    if (sound) sound.play();
    const index = daftarPesanan.findIndex(item => item.id === id);
    if (index !== -1) {
        daftarPesanan[index].qty++;
        daftarPesanan[index].total = daftarPesanan[index].qty * harga;
    } else {
        daftarPesanan.push({ id, nama, qty: 1, total: harga });
    }
    updateDaftarPesanan();
}

function hapusPesanan(index) {
    if (daftarPesanan[index].qty > 1) {
        daftarPesanan[index].qty--;
        daftarPesanan[index].total -= daftarPesanan[index].total / (daftarPesanan[index].qty + 1);
    } else {
        daftarPesanan.splice(index, 1);
    }
    updateDaftarPesanan();
}

function tambahPesanan(index) {
    daftarPesanan[index].qty++;
    daftarPesanan[index].total = daftarPesanan[index].qty * (daftarPesanan[index].total / (daftarPesanan[index].qty - 1));
    updateDaftarPesanan();
}

function kurangiPesanan(index) {
    if (daftarPesanan[index].qty > 1) {
        daftarPesanan[index].qty--;
        daftarPesanan[index].total -= daftarPesanan[index].total / (daftarPesanan[index].qty + 1);
    } else {
        daftarPesanan.splice(index, 1);
    }
    updateDaftarPesanan();
}

function updateDaftarPesanan() {
    const daftarPesananEl = document.getElementById('daftarPesanan');
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
        </tr>`;
    });

    document.getElementById('totalHarga').textContent = totalHarga.toLocaleString();
    document.getElementById('inputPesanan').value = JSON.stringify(daftarPesanan);
    document.getElementById('inputTotalHarga').value = totalHarga;
}

// ======================= MODAL ======================

function bukaModal() {
    // Pastikan modal loading & notifikasi ditutup
    const modalLoadingEl = document.getElementById('modalLoading');
    const modalNotifikasiEl = document.getElementById('modalNotifikasi');

    if (bootstrap.Modal.getInstance(modalLoadingEl)) bootstrap.Modal.getInstance(modalLoadingEl).hide();
    if (bootstrap.Modal.getInstance(modalNotifikasiEl)) bootstrap.Modal.getInstance(modalNotifikasiEl).hide();

    // Tampilkan modal pembayaran
    const modalPembayaranEl = document.getElementById('modalPembayaran');
    const modalPembayaran = bootstrap.Modal.getOrCreateInstance(modalPembayaranEl);
    modalPembayaran.show();
}



function tutupModal() {
    const modalIds = ['modalPembayaran', 'modalNotifikasi', 'modalLoading'];
    modalIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = "none";
    });
}

function toggleBuktiPembayaran() {
    let metode = document.getElementById("metodePembayaran").value;
    let buktiField = document.getElementById("buktiPembayaranField");
    let qrisPreview = document.getElementById("qrisPreview");

    if (metode === "qris") {
        buktiField.style.display = "block";
        qrisPreview.style.display = "block";
    } else {
        buktiField.style.display = "none";
        qrisPreview.style.display = "none";
    }
}
document.getElementById('tombolKonfirmasi').addEventListener('click', async function () {
    const form = document.getElementById('formPembayaran');
    const formData = new FormData(form);

    const modalLoadingEl = document.getElementById('modalLoading');
    const modalPembayaranEl = document.getElementById('modalPembayaran');
    const modalNotifikasiEl = document.getElementById('modalNotifikasi');
    modalNotifikasiEl.addEventListener('hidden.bs.modal', function () {
        location.reload();
    });
    // Inisialisasi semua modal
    const modalLoading = new bootstrap.Modal(modalLoadingEl, { backdrop: 'static' });
    const modalPembayaran = bootstrap.Modal.getInstance(modalPembayaranEl) || new bootstrap.Modal(modalPembayaranEl);
    const modalNotifikasi = new bootstrap.Modal(modalNotifikasiEl);

    // Tampilkan loading
    modalLoading.show();

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            salinanStruk = [...daftarPesanan.map(item => ({ ...item }))];

            // Reset form dan daftar pesanan
            form.reset();
            daftarPesanan = [];
            updateDaftarPesanan();

            // Step 1: Tutup modal pembayaran
            modalPembayaran.hide();

            // Step 2: Tunggu sampai modal pembayaran benar-benar tertutup
            modalPembayaranEl.addEventListener('hidden.bs.modal', () => {
                // Step 3: Tutup modal loading
                modalLoading.hide();

                // Step 4: Tampilkan modal notifikasi
                modalNotifikasi.show();
            }, { once: true });

        } else {
            modalLoading.hide();
            alert(result.message || 'Gagal melakukan pembayaran.');
        }
    } catch (error) {
        modalLoading.hide();
        alert('Terjadi kesalahan koneksi.');
        console.error(error);
    }
});

//-------NOTA--------
function printNota() {
    const strukContainer = document.getElementById('isiStruk');
    strukContainer.innerHTML = '';

    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'numeric',
        year: 'numeric'
    }) + ' ' + now.toLocaleTimeString('id-ID');

    // Header
    strukContainer.innerHTML += `
        <div style="text-align: center;">
            <strong style="font-size: 13px;">MOTOXPRESS</strong><br>
            Jl. Contoh Alamat No. 123<br>
            -------------------------------<br>
            TANGGAL : ${tanggal}<br>
            -------------------------------<br>
        </div>
    `;

    // Isi struk dari salinan
    salinanStruk.forEach(item => {
        const hargaSatuan = (item.total / item.qty).toLocaleString();
        const totalItem = item.total.toLocaleString();

        strukContainer.innerHTML += `
            ${item.nama.toUpperCase()}<br>
            ${item.qty} x Rp ${hargaSatuan} = Rp ${totalItem}<br>
        `;
    });

    const total = salinanStruk.reduce((acc, item) => acc + item.total, 0);

    strukContainer.innerHTML += `
        -------------------------------<br>
        <strong>TOTAL : Rp ${total.toLocaleString()}</strong><br>
        -------------------------------<br>
        <div style="text-align: center; margin-top: 10px;">
            TERIMA KASIH 🙏<br>
        </div>
    `;

    // Pilihan cetak atau unduh
    Swal.fire({
        title: "Cetak atau Unduh?",
        showDenyButton: true,
        confirmButtonText: "🖨 Cetak",
        denyButtonText: `⬇ Unduh PDF`
    }).then((result) => {
        if (result.isConfirmed) {
            const newWindow = window.open('', '', 'width=280,height=600');
            newWindow.document.write(`
                <html>
                <head><title>Struk</title></head>
                <body style="font-family: 'Courier New', monospace; font-size: 11px;">
                    ${document.getElementById('areaStruk').innerHTML}
                </body>
                </html>
            `);
            newWindow.document.close();
            newWindow.focus();
            newWindow.print();
            newWindow.close();
        } else if (result.isDenied) {
            html2pdf().from(document.getElementById('areaStruk')).set({
                margin: 5,
                filename: 'struk-pembayaran.pdf',
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: [80, 150 + salinanStruk.length * 10], orientation: 'portrait' }
            }).save();
        }
    });
}

