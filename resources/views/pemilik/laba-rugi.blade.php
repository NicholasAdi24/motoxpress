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
                <th>HPP</th>
                <th>Beban Operasional</th>
                <th>Laba Kotor</th>
                <th>Laba Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row['bulan'] }}</td>
                    <td>Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['hpp'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['laba_kotor'], 0, ',', '.') }}</td>
                    <td class="{{ $row['laba_bersih'] >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                        Rp {{ number_format($row['laba_bersih'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const labels = @json(collect($data)->reverse()->pluck('bulan'));
    const pendapatan = @json(collect($data)->reverse()->pluck('pendapatan'));
    const hpp = @json(collect($data)->reverse()->pluck('hpp'));
    const pengeluaran = @json(collect($data)->reverse()->pluck('pengeluaran'));
    const labaKotor = @json(collect($data)->reverse()->pluck('laba_kotor'));
    const labaBersih = @json(collect($data)->reverse()->pluck('laba_bersih'));

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
                    label: 'HPP',
                    data: hpp,
                    borderColor: 'orange',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Beban Operasional',
                    data: pengeluaran,
                    borderColor: 'red',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Laba Kotor',
                    data: labaKotor,
                    borderColor: 'purple',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Laba Bersih',
                    data: labaBersih,
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
