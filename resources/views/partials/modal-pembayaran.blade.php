<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
            <div class="modal-body">
                <form id="formPembayaran" action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pesanan" id="inputPesanan">
                    <input type="hidden" name="total_harga" id="inputTotalHarga">

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-control" name="metode_pembayaran" id="metodePembayaran" required onchange="toggleBuktiPembayaran()">
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <!-- QRIS Preview -->
                    <div class="mb-3" id="qrisPreview" style="display: none;">
                        <label class="form-label">Scan QRIS di bawah ini</label>
                        <img src="{{ asset('storage/images/qris-default.png') }}" alt="QRIS" class="img-fluid rounded border" style="max-width: 100%;">
                    </div>

                    <!-- Bukti Pembayaran Upload -->
                    <div class="mb-3" id="buktiPembayaranField" style="display: none;">
                        <label class="form-label">Unggah Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" class="form-control">
                    </div>

                    <button type="button" class="btn btn-success w-100" id="tombolKonfirmasi">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>
