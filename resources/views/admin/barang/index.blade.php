@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mt-4">Stok Barang</h2>
    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> New Stock
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.barang.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg"></i> Reset
                </a>
            @endif
        </div>
    </form>



    <div class="row">
        @foreach ($barangs as $barang)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><strong>{{ $barang->nama_barang }} </strong></h5>
                        <p class="card-text">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                        
                        <p class="text-muted">Harga Beli Rp{{ number_format($barang->harga_modal, 0, ',', '.') }}</p>
                        <p class="text-muted">Stok: {{ $barang->stok }}</p>
                        <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }
    .card-img-top {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .card-body {
        padding: 10px;
    }
    .btn-sm {
        font-size: 12px;
        padding: 5px 10px;
    }
</style>
@endsection
