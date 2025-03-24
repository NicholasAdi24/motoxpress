@extends('layouts.riwayat')

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


    <div class="container mt-4">
        <h2 class="my-4">Riwayat Transaksi</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pesanan</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Bukti Pembayaran</th>
                    <th>Waktu Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayarans as $index => $pembayaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <ul>
                            @php
                                $pesanan = json_decode($pembayaran->pesanan, true);
                            @endphp
                            @if (is_array($pesanan))
                                @foreach ($pesanan as $item)
                                    <li>{{ $item['nama'] }} - {{ $item['qty'] }}x</li>
                                @endforeach
                            @else
                                <li>Data tidak valid</li>
                            @endif
                        </ul>
                    </td>
                    <td>Rp {{ number_format($pembayaran->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($pembayaran->metode_pembayaran) }}</td>
                    <td>
                        @if ($pembayaran->status_pembayaran === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif ($pembayaran->status_pembayaran === 'paid')
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </td>
                    <td>
                        @if ($pembayaran->bukti_pembayaran)
                            <a href="{{ asset('storage/bukti_pembayaran/' . $pembayaran->bukti_pembayaran) }}" target="_blank">
                            <img src="{{ asset('storage/bukti_pembayaran/' . $pembayaran->bukti_pembayaran) }}" class="card-img-top">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->waktu_pembayaran)->format('d M Y, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
