@extends('layouts.admin')

@section('content')
@php
    use Carbon\Carbon;

    $bulanTahunList = $pembayarans->map(function($item) {
        return Carbon::parse($item->waktu_pembayaran)->format('F Y');
    })->unique()->sortByDesc(function($bulanTahun) {
        return Carbon::createFromFormat('F Y', $bulanTahun);
    });


    $bulanDipilih = request('bulan') ?? Carbon::now()->format('F Y');

    $bulanIni = Carbon::now()->format('F Y');
    $tanggalHariIni = Carbon::now()->format('Y-m-d');

    $totalPemasukanBulanIni = $pembayarans->filter(function ($item) {
        return Carbon::parse($item->waktu_pembayaran)->format('F Y') === Carbon::now()->format('F Y')
            && $item->status_pembayaran === 'paid';
    })->sum('total_harga');

    $totalTransaksiHariIni = $pembayarans->filter(function ($item) {
        return Carbon::parse($item->waktu_pembayaran)->format('Y-m-d') === Carbon::now()->format('Y-m-d')
            && $item->status_pembayaran === 'paid';
    })->count();

    $totalTransaksiBulanIni = $pembayarans->filter(function ($item) {
        return Carbon::parse($item->waktu_pembayaran)->format('F Y') === Carbon::now()->format('F Y')
            && $item->status_pembayaran === 'paid';
    })->count();

    
@endphp

@php

    $today = Carbon::now()->format('Y-m-d');

    $pembayaranHariIni = $pembayarans->filter(function($p) use ($today) {
        return \Carbon\Carbon::parse($p->waktu_pembayaran)->format('Y-m-d') === $today
            && $p->status_pembayaran === 'paid';
    });

    $itemTerjualHariIni = [];

    foreach ($pembayaranHariIni as $p) {
        $pesanan = json_decode($p->pesanan, true);
        foreach ($pesanan as $item) {
            $nama = $item['nama'];
            $qty = $item['qty'];

            if (!isset($itemTerjualHariIni[$nama])) {
                $itemTerjualHariIni[$nama] = 0;
            }

            $itemTerjualHariIni[$nama] += $qty;
        }
    }

    $totalItemTerjual = array_sum($itemTerjualHariIni);
@endphp




    <div class="container mt-4">
        <h2 class="mb-4">Riwayat Transaksi</h2>

        <div class="rounded p-4 mb-4" style="background-color: #28a745; color: white;">
        <h5 class="mb-2"><i class="fas fa-money-bill-wave me-2"></i>Pemasukan {{ $bulanIni }}</h5>

            <h3 class="fw-bold">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</h3>
            <hr class="border-light">
            <p class="mb-1">✅ Transaksi Selesai Hari Ini: <strong>{{ $totalTransaksiHariIni }}</strong></p>
            <p class="mb-0">📅 Transaksi Selesai Bulan Ini: <strong>{{ $totalTransaksiBulanIni }}</strong></p>
        </div>

        @php


    $tanggalHariIni = Carbon::now()->format('Y-m-d');

    $totalTransaksiHariIni = $pembayarans->filter(function ($item) use ($tanggalHariIni) {
        return Carbon::parse($item->waktu_pembayaran)->format('Y-m-d') === $tanggalHariIni && $item->status_pembayaran === 'paid';
    });

    $totalItemHariIni = $totalTransaksiHariIni->sum(function ($item) {
        $pesanan = json_decode($item->pesanan, true);
        return collect($pesanan)->sum('qty');
    });

    $totalPemasukanHariIni = $totalTransaksiHariIni->sum('total_harga');
@endphp

<div class="row mb-4">
    <div class="col-md-6">
    <div class="card shadow-sm bg-primary text-white cursor-pointer"
    data-bs-toggle="modal" data-bs-target="#modalItemTerjual">
            <div class="card-body">
            

                <h3 class="card-title">{{ number_format($totalItemHariIni, 0, ',', '.') }}</h3>
                <p class="card-text">Total Item Terjual Hari Ini 
                    <i class="fas fa-shopping-bag fa-5x opacity-50 position-absolute end-0 bottom-0 m-3"></i>
                </p>
                
                <div class="d-flex justify-content-between align-items-center">
                    
                    <small>{{ now()->year }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm text-white bg-success">
            <div class="card-body">
                <h3 class="card-title">Rp {{ number_format($totalPemasukanHariIni, 0, ',', '.') }}</h3>
                <p class="card-text">Total Pemasukan Hari Ini</p>
                <div class="d-flex justify-content-between align-items-center">
                   
                    <small>{{ now()->year }}</small>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- Dropdown bulan dan tahun -->
        <!-- <form method="GET" class="d-flex mb-3">
            <select name="bulan" class="form-select w-auto me-2">
                @foreach($bulanTahunList as $bulanTahun)
                    <option value="{{ $bulanTahun }}" {{ $bulanTahun == $bulanDipilih ? 'selected' : '' }}>
                        {{ $bulanTahun }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Sortir</button>
        </form> -->

        <!-- Expand/Collapse per bulan -->
        @foreach($bulanTahunList as $bulanTahun)
            @php
                $bulanKey = str_replace(' ', '-', $bulanTahun);
                $filtered = $pembayarans->filter(function($item) use ($bulanTahun) {
                    return Carbon::parse($item->waktu_pembayaran)->format('F Y') === $bulanTahun;
                })->sortByDesc('waktu_pembayaran');

                $groupedByDate = $filtered->groupBy(function($item) {
                    return Carbon::parse($item->waktu_pembayaran)->format('d F Y');
                });
            @endphp

            <div class="month-section mb-4">
                <button class="btn toggle-month w-100 text-start" data-target="#month-{{ $bulanKey }}">
                    <span class="month-icon me-2">▶️</span>{{ $bulanTahun }}
                </button>

                <div id="month-{{ $bulanKey }}" class="month-content mt-2 d-none">
                    @forelse($groupedByDate as $tanggal => $transaksis)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-primary text-white fw-bold">
                                {{ $tanggal }}
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach($transaksis as $pembayaran)
                                    <li class="list-group-item">
                                        <strong>Waktu Pembayaran:</strong> 
                                        {{ Carbon::parse($pembayaran->waktu_pembayaran)->format('d-m-Y H:i') }}<br>

                                        <strong>Metode:</strong> {{ ucfirst($pembayaran->metode_pembayaran) }} |
                                        <strong>Total:</strong> Rp {{ number_format($pembayaran->total_harga, 0, ',', '.') }} |
                                        <strong>Status:</strong>
                                        @if ($pembayaran->status_pembayaran === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($pembayaran->status_pembayaran === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                        <br>

                                        <strong>Pesanan:</strong>
                                        <ul class="mb-2">
                                            @php $pesanan = json_decode($pembayaran->pesanan, true); @endphp
                                            @foreach ($pesanan as $item)
                                                <li>{{ $item['nama'] }} - {{ $item['qty'] }}x</li>
                                            @endforeach
                                        </ul>

                                        <strong>Bukti Pembayaran:</strong><br>
                                        @if ($pembayaran->bukti_pembayaran)
                                            <a href="{{ asset('storage/bukti_pembayaran/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada bukti pembayaran.</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p>Tidak ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal: Daftar Item Terjual Hari Ini -->
<div class="modal fade" id="modalItemTerjual" tabindex="-1" aria-labelledby="modalItemTerjualLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalItemTerjualLabel">🛒 Daftar Item Terjual Hari Ini</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        @if(count($itemTerjualHariIni) > 0)
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Nama Item</th>
                        <th>Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemTerjualHariIni as $nama => $qty)
                        <tr>
                            <td>{{ $nama }}</td>
                            <td>{{ $qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Belum ada item terjual hari ini.</p>
        @endif
      </div>
    </div>
  </div>
</div>


<!-- Script toggle bulan -->
@push('scripts')
<script>
    document.querySelectorAll('.toggle-month').forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.dataset.target);
            const icon = button.querySelector('.month-icon');
            target.classList.toggle('d-none');
            icon.textContent = target.classList.contains('d-none') ? '▶️' : '🔽';
        });
    });
</script>
@endpush

@endsection
