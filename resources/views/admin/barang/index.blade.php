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

    <div class="row">
    @php
        $barangs = $barangs->sortBy(function($barang) {
        return $barang->stok < 20 ? 0 : 1;
        });
    @endphp

        @foreach ($barangs as $barang)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6 class="card-title"><strong>{{ $barang->nama_barang }}</h6></strong>
                        <p class="card-text">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="{{ ($barang->stok < 20 || $barang->stok < $barang->min_stok) ? 'text-danger' : 'text-muted' }}">
                            Stok: {{ $barang->stok }}
                        </p>
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
    .card-title {
        font-family:  sans-serif;
    }
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
        font-weight: ;
        padding: 5px 10px;
    }
</style>
@endsection
