@extends('layouts.pemilik')

@section('content')
<div class="container">
    <h3 class="mb-4">📈 Laporan Pendapatan Bulanan</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
        <h5>📈 Grafik Pendapatan Kotor Bulanan</h5>
        <canvas id="chartPendapatan" height="100"></canvas>
        </div>
    </div>


    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Bulan</th>
                <th>Total Pemasukan</th>
                <th>Jumlah Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->bulan)->translatedFormat('F Y') }}</td>
                    <td>Rp {{ number_format($row->pemasukan_total, 0, ',', '.') }}</td>
                    <td>{{ $row->jumlah_transaksi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const labels = @json($laporan->pluck('bulan')->map(function($bulan) {
        return \Carbon\Carbon::parse($bulan)->translatedFormat('F Y');
    }));

    const dataPemasukan = @json($laporan->pluck('pemasukan_total')->map(fn($v) => (float) $v));

    const ctx = document.getElementById('chartPendapatan').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pemasukan',
                data: dataPemasukan,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.3,
                fill: false,
                pointBackgroundColor: 'black'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Pemasukan Bulanan'
                },
                legend: {
                    display: true
                }
            }
        }
    });
</script>
@endsection
