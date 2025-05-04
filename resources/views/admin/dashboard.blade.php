@extends('layouts.admin')
@section('content')

@php
use Carbon\Carbon;

$bulanTahunList = $pembayarans->map(fn($item) =>
    Carbon::parse($item->waktu_pembayaran)->format('F Y')
)->unique();

$bulanDipilih = request('bulan') ?? Carbon::now()->format('F Y');

$totalPemasukanBulanIni = $pembayarans->filter(fn($item) =>
    Carbon::parse($item->waktu_pembayaran)->format('F Y') === Carbon::now()->format('F Y') &&
    $item->status_pembayaran === 'paid'
)->sum('total_harga');

$totalTransaksiHariIni = $pembayarans->filter(fn($item) =>
    Carbon::parse($item->waktu_pembayaran)->format('Y-m-d') === Carbon::now()->format('Y-m-d') &&
    $item->status_pembayaran === 'paid'
)->count();

$totalStokBarang = $barangs->sum('stok');
@endphp

<h2><strong>Selamat datang, {{ Auth::user()->name }}!</strong></h2>

<div class="d-flex flex-wrap gap-3 mb-4">
    <!-- Kartu Info -->
    <div class="card p-3" style="min-width: 200px;">
        <div>
            <h5><strong>IDR. {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</strong></h5>
            <small>In Come</small>
        </div>
        <div class="text-end">
            <i class="fas fa-wallet fa-lg"></i>
        </div>
    </div>

    <div class="card p-3" style="min-width: 200px;">
        <div>
            <h5><strong>IDR. 0</strong></h5>
            <small>Out Come</small>
        </div>
        <div class="text-end">
            <i class="fas fa-wallet fa-lg"></i>
        </div>
    </div>

    <div class="card p-3" style="min-width: 200px;">
        <div>
            <h5><strong>{{ $totalTransaksiHariIni }}</strong></h5>
            <small>Cashier</small>
        </div>
        <div class="text-end">
            <i class="fas fa-yen-sign fa-lg"></i>
        </div>
    </div>

    <div class="card p-3 text-white" style="min-width: 200px; background-color: #36a2eb;">
        <div>
            <h5><strong>{{ $totalStokBarang }}</strong></h5>
            <small>Stock Item</small>
        </div>
        <div class="text-end">
            <i class="fas fa-bag-shopping fa-lg"></i>
        </div>
    </div>
</div>

@php
$stok_kurang_20 = $barangs->where('stok', '<', 20)->count();
$stok_lebih_20 = $barangs->where('stok', '>=', 20)->count();
@endphp

<!-- Diagram Garis Penjualan -->
<div class="card mb-4" style="width: 100%; height: 450px;">
        <div class="card-header">
            <strong style="color: #f6f6f6">Penjualan Perbulan</strong>
        </div>
        <div class="card-body">
            <canvas id="penjualanChart"></canvas>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-4 mt-400">
        <!-- Diagram Pie -->
        <div class="card" style="background-color: white; flex: none; width: 300px; max-width: 300px; min-width: 150px; height: 400px;">
            <div class="card-header" style="color: #f6f6f6;">
                <strong>Komposisi Barang</strong>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px; background-color: white;">
                <canvas id="stokChart"></canvas>
            </div>
        </div>

        <!-- Tabel Stok -->
        <div class="card" style="flex: 1; color: black; height: 100%;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title" style="color: #f6f6f6;"><strong>Stok Barang</strong></h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" id="btnNormalOrder">Urutan Biasa</button>
                    <button class="btn btn-warning" id="btnStokPriority">Prioritaskan Stok < 20</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-stok" class="table display table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Stok Min</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th style="display:none;">Prioritas Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                            <tr style="background-color: {{ $barang->stok < $barang->min_stok ? '#ffebee' : 'transparent' }};">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->min_stok }}</td>
                                <td class="text-end">
                                    @if($barang->stok < $barang->min_stok)
                                        <div class="text-danger">{{ $barang->stok }}</div>
                                    @else
                                        <div>{{ $barang->stok }}</div>
                                    @endif
                                </td>
                                <td class="text-end">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                                <td style="display:none;">{{ $barang->stok < 20 ? 1 : 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    #tabel-stok {
        min-width: 600px;
    }
    .d-flex.flex-wrap {
        align-items: stretch;
    }
    body, table, input, select, button {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .table thead th {
        font-weight: bold;
        background-color: #409dfd;
        color: white;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #f6f6f6;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
    }
    .btn-success:hover, .btn-danger:hover {
        opacity: 0.8;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .dataTables_info,
    .dataTables_paginate {
        background-color: transparent !important;
    }
    .table-responsive {
        flex-grow: 1;
    }
    .card {
        width: 100%;
        height: 100%;
    }
    #tabel-stok th:nth-child(2),
    #tabel-stok td:nth-child(2) {
        width: auto !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    // Chart Pie Barang
    const ctx = document.getElementById('stokChart').getContext('2d');
    const stokChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Stok < 20', 'Stok ≥ 20'],
            datasets: [{
                data: [{{ $stok_kurang_20 }}, {{ $stok_lebih_20 }}],
                backgroundColor: ['#007bff', '#28a745'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });

    // Chart Garis Penjualan
    const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');
    const penjualanChart = new Chart(ctxPenjualan, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                {
                    label: '2024',
                    data: [172000000, 150000000, 163000000, 160000000, 170000000, 159000000, 180000000, 255000000, 240000000, 248000000, 247000000, 242000000],
                    borderColor: '#409dfd',
                    backgroundColor: '#409dfd',
                    tension: 0.3,
                    fill: false,
                    pointRadius: 5
                },
                {
                    label: '2025',
                    data: [155000000, 157000000, 170000000, 155000000, 180000000, 158000000, 175000000, 254000000, 223000000, 245000000, 268000000, 259000000],
                    borderColor: '#fcb415',
                    backgroundColor: '#fcb415',
                    tension: 0.3,
                    fill: false,
                    pointRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                        }
                    }
                }
            }
        }
    });

    // DataTables Init
    $(document).ready(function() {
        var table = $('#tabel-stok').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 5,
            "dom": '<"top d-flex justify-content-end"f>rt<"bottom d-flex justify-content-between"lip><"clear">',
            "order": [],
            "columnDefs": [
                { "targets": 5, "visible": false }
            ],
            "language": {
                "search": "Cari:  ",
                "lengthMenu": "Tampilkan _MENU_ entry",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>'
                }
            },
            "pagingType": "simple"
        });

        $('#btnNormalOrder').click(function() {
            table.search('').columns().search('').draw();
        });

        $('#btnStokPriority').click(function() {
            table.columns(5).search('1').draw();
        });
    });
</script>
@endsection