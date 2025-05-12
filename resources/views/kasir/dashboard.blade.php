@extends('layouts.kasir')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
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

    <!-- Waktu -->
    <div class="bg-white shadow-sm rounded p-3 m-3" style="border-radius: 10px;">
        <h6 class="mb-0" id="tanggalSekarang">Memuat waktu...</h6>
    </div>

    <!-- Filter Kategori
    <div class="d-flex overflow-auto bg-white px-3 py-2">
        <button class="btn btn-danger me-2">Semua</button>
        <button class="btn btn-light me-2">Oli</button>
        <button class="btn btn-light me-2">Ban</button>
        <button class="btn btn-light me-2">Spare Part</button>
        <button class="btn btn-light me-2">Lain-lain</button>
    </div> -->

    <!-- Konten Utama -->
    <div class="row m-0 mt-3">
        <!-- Katalog Produk -->
        <div class="col-md-8 pe-md-4">
            <div class="row">
                @foreach ($barangs as $barang)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card product-card shadow-sm h-100 
                                {{ $barang->stok == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        @if ($barang->stok > 0)
                        onclick="tambahKePesanan('{{ $barang->id }}','{{ $barang->nama_barang }}', {{ $barang->harga }}, {{ $barang->harga_modal }})"
                        @endif
                    >
                        <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top" style="height: 120px; object-fit: contain;">
                        <div class="card-body text-center p-2">
                            <h6 class="card-title mb-1">{{ $barang->nama_barang }}</h6>

                            <small>
                                <span class="badge px-3 py-1 rounded-pill {{ $barang->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                    Stok: {{ $barang->stok }}
                                </span>
                            </small>

                            <p class="card-text fw-bold mt-1 mb-0">
                                <span class="badge bg-primary px-3 py-2 rounded-pill text-white">
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

       <!-- Panel Pesanan -->
<div class="col-md-4">
    <div class="card shadow-sm p-3 d-flex flex-column" style="border-radius: 12px; height: 80vh;">
        <div class="card-body d-flex flex-column text-start" style="overflow: hidden;">
            <h5 class="card-title fw-semibold mb-3">Daftar Pesanan</h5>

            <!-- Scrollable table area -->
            <div class="table-responsive flex-grow-1 overflow-auto mb-3" style="max-height: 40vh;">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="daftarPesanan"></tbody>
                </table>
            </div>

            <!-- Total and Pay Button pinned at bottom -->
            <hr class="my-2">
            <h5 class="text-end">Total: <span id="totalHarga">Rp 0</span></h5>
            <button class="btn btn-success w-100 mt-2" onclick="bukaModal()">Bayar</button>
        </div>
    </div>
</div>


@include('partials.modal-pembayaran')
@include('partials.modal-notifikasi')
@include('partials.modal-loading')
<audio id="soundBeep" src="{{ asset('sounds/beep.mp3') }}"></audio>

@endsection
@include('partials.template-nota')
