@extends('layouts.admin')

@section('content')
<div class="container">
    <h2><p>Selamat datang, {{ Auth::user()->name }}!</p></h2>

    @php
        // Ambil data stok
        $stok_kurang_20 = DB::table('barangs')->where('stok', '<', 20)->count();
        $stok_lebih_20 = DB::table('barangs')->where('stok', '>=', 20)->count();
    @endphp

    <div class="d-flex flex-wrap gap-4 mt-4">
        <!-- Diagram Pie -->
        <div class="card" style="flex: 1; min-width: 300px;">
            <div class="card-header">
                Komposisi Barang
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px; background-color: #f9f9f9;">
                <canvas id="stokChart"></canvas>
            </div>
        </div>

        <!-- Tabel Stok -->
        <div class="table-responsive" style="flex: 2; min-width: 300px;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok Min</th>
                        <th>Stok</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->min_stok }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>Rp{{ $barang->harga }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    table.table-bordered {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: 1px solid #ccc;
    }

    table.table-bordered thead th {
        background-color: #007bff !important; /* Pakai !important */
        color: white !important;              /* Pastikan warnanya putih */
        font-weight: normal;
        border-bottom: 2px solid #ccc;
    }

    table.table-bordered th,
    table.table-bordered td {
        border-bottom: 1px solid #b0bec5;
        padding: 12px 15px;
        text-align: left;
    }

    table.table-bordered tbody tr:nth-child(even) {
        background-color: #eceff1;
    }

    table.table-bordered tbody tr:hover {
        background-color: #cfd8dc;
        cursor: pointer;
    }

    table.table-bordered th:first-child,
    table.table-bordered td:first-child {
        text-align: center;
    }

    table.table-bordered th:last-child,
    table.table-bordered td:last-child {
        text-align: right;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
</script>
@endsection
