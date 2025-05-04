@extends('layouts.pemilik')

@section('content')
<div class="container">
    <h3 class="mb-4">📊 Laporan Laba / Rugi Bulanan</h3>

    
    {{-- Grafik --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Grafik Laba / Rugi</h5>
            <canvas id="chartLabaRugi" height="100"></canvas>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-secondary">
            <tr>
                <th>Bulan</th>
                <th>Pendapatan</th>
                <th>Pengeluaran</th>
                <th>Laba / Rugi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['bulan'] }}</td>
                    <td>Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                    <td class="{{ $row['laba_rugi'] >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($row['laba_rugi'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const labels = @json(collect($data)->pluck('bulan'));
    const pendapatan = @json(collect($data)->pluck('pendapatan'));
    const pengeluaran = @json(collect($data)->pluck('pengeluaran'));
    const labaRugi = @json(collect($data)->pluck('laba_rugi'));

    const ctx = document.getElementById('chartLabaRugi').getContext('2d');
    const chartLabaRugi = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: pendapatan,
                    borderColor: 'blue',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    borderColor: 'red',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Laba / Rugi',
                    data: labaRugi,
                    borderColor: 'green',
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Laba / Rugi per Bulan'
                }
            }
        }
    });
</script>
@endsection
