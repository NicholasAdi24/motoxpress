@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-3">Kasir</h1>

    <div class="row">
        <!-- Daftar Barang -->
        <div class="col-md-8">
            <div class="row">
                @foreach($barangs as $barang)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top" alt="{{ $barang->nama_barang }}" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                            <p class="card-text">Harga: Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                            <p class="card-text">Stok: {{ $barang->stok }}</p>
                            <button class="btn btn-primary add-to-cart" data-id="{{ $barang->id }}" data-nama="{{ $barang->nama_barang }}" data-harga="{{ $barang->harga }}">Tambah</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Keranjang -->
        <div class="col-md-4">
            <h4>Keranjang</h4>
            <ul id="cart-list" class="list-group mb-3"></ul>
            <h5>Total: Rp <span id="total-harga">0</span></h5>
        </div>
    </div>
</div>

<script>
    let cart = [];
    
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".add-to-cart").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let nama = this.getAttribute("data-nama");
                let harga = parseInt(this.getAttribute("data-harga"));

                let item = cart.find(i => i.id === id);
                if (item) {
                    item.qty++;
                } else {
                    cart.push({ id, nama, harga, qty: 1 });
                }
                updateCart();
            });
        });
    });

    function updateCart() {
        let cartList = document.getElementById("cart-list");
        cartList.innerHTML = "";
        let total = 0;

        cart.forEach(item => {
            let listItem = document.createElement("li");
            listItem.className = "list-group-item d-flex justify-content-between align-items-center";
            listItem.innerHTML = `
                ${item.nama} (x${item.qty})
                <span>Rp ${new Intl.NumberFormat("id-ID").format(item.harga * item.qty)}</span>
            `;
            cartList.appendChild(listItem);
            total += item.harga * item.qty;
        });

        document.getElementById("total-harga").innerText = new Intl.NumberFormat("id-ID").format(total);
    }
</script>
@endsection
