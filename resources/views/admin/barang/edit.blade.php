@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Barang</h2>
    <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ $barang->harga }}" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ $barang->stok }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            @if($barang->gambar)
                <br>
                <img src="{{ asset('storage/' . $barang->gambar) }}" width="120" class="mb-2">
            @endif
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>


        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
