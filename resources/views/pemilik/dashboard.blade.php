@extends('layouts.pemilik')

@section('content')
<div class="container">


    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan Bulan Ini</h5>
                    <h3 class="text-white">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Pengeluaran Bulan Ini</h5>
                    <h3 class="text-white">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Grafik Pemasukan Per Bulan</h5>
                <canvas id="lineChartPemasukan"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Penjualan Per Tahun</h5>
                <canvas id="barChartTahun"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Penjualan Barang Terbesar</h5>
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($itemStats->take(10) as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>Rp {{ number_format($item['total'] / $item['qty'], 0, ',', '.') }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                            <td>{{ $item['kontribusi'] }}%</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Paling Banyak Terjual</h5>
                <canvas id="pieChartTerjual"></canvas>
            </div>
        </div>
    </div>
</div>


<script>
    const pieLabels = @json($itemPieChart->pluck('label'));
    const pieData = @json($itemPieChart->pluck('value'));

    new Chart(document.getElementById('pieChartTerjual'), {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: [
                    '#3cba9f', '#8e5ea2', '#e8c3b9', '#c45850', '#74b9ff'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Top 5 Item Terjual Bulan Ini'
                }
            }
        }
    });
</script>



<script>
    const bulan = @json($bulanList);
    const data2024 = @json($data2024->values());
    const data2025 = @json($data2025->values());

    const ctx = document.getElementById('lineChartPemasukan').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulan,
            datasets: [
                {
                    label: '2024',
                    data: data2024,
                    borderColor: 'blue',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                },
                {
                    label: '2025',
                    data: data2025,
                    borderColor: 'green',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Perbandingan Pemasukan per Bulan (2024 vs 2025)'
                }
            }
        }
    });
</script>

<script>
const ctxBar = document.getElementById('barChartTahun').getContext('2d');
const labelsTahun = @json($labelsTahun);
const dataTahun = @json($dataTahun);

new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: labelsTahun,
        datasets: [{
            label: 'Total Penjualan',
            data: dataTahun,
            backgroundColor: ['#f38b4a', '#56d798', '#ff8397', '#6970d5'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Perbandingan Total Penjualan per Tahun'
            },
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>

@endsection
