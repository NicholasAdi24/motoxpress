@extends('layouts.kasir')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center custom-bg text-white p-3">
        <div class="d-flex align-items-center">
            <button class="btn text-white" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h5 class="ms-2">motoxpress</h5>
            <span class="badge bg-white text-danger ms-2">Kasir</span>
        </div>
        <div class="flex-grow-1 mx-3">
            <input type="text" class="form-control" placeholder="Cari Produk">
        </div>
        <button class="btn text-white"><i class="fas fa-th"></i></button>
        <button class="btn text-white"><i class="fas fa-calendar-alt"></i></button>
    </div>

    <div class="d-flex overflow-auto bg-white p-2">
        <button class="btn btn-danger me-2">Semua</button>
        <button class="btn btn-light me-2">Oli</button>
        <button class="btn btn-light me-2">Ban</button>
        <button class="btn btn-light me-2">Spare Part</button>
        <button class="btn btn-light me-2">Lain-lain</button>
    </div>

    <div class="row mt-3">
        <div class="col-md-7">
            <div class="row">
                @foreach ($barangs as $barang)
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card product-card" onclick="tambahKePesanan('{{ $barang->id }}','{{ $barang->nama_barang }}', {{ $barang->harga }})">
                            <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top">
                            <div class="card-body text-center p-2">
                                <h6 class="card-title">{{ $barang->nama_barang }}</h6>
                                <p class="card-text">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                <p class="card-text">Jumlah Stok :  {{ $barang->stok}}</p>
                                <p class="card-text">id :  {{ $barang->id}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <h5>Daftar Pesanan</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th> Qty </th>
                        <th>Total</th>
                        <th> Aksi </th>
                    </tr>
                </thead>
                <tbody id="daftarPesanan"></tbody>
            </table>
            <h4 class="text-end">Total: Rp <span id="totalHarga">0</span></h4>
            <button class="btn btn-success w-100" onclick="bukaModal()">Bayar</button>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal" id="modalPembayaran">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" onclick="tutupModal()"></button>
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

<!-- Modal Notifikasi -->
<div class="modal fade" id="modalNotifikasi" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pesanan telah berhasil diproses!</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade" id="modalLoading" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Memproses pembayaran...</p>
            </div>
        </div>
    </div>
</div>

@endsection
