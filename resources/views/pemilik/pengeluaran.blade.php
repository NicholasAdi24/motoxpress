@extends('layouts.pemilik')

@section('content')
<div class="container">
    <h3 class="mb-4">💸 Daftar Pengeluaran</h3>

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <h5>Total Pengeluaran Hari Ini</h5>
                    <h3>Rp {{ number_format($totalHarian, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <h5>Total Pengeluaran Bulan Ini</h5>
                    <h3>Rp {{ number_format($totalBulanan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

        <h5>📈 Grafik Pengeluaran Bulanan</h5>
        <canvas id="pengeluaranChart" height="100"></canvas>
        </div>
    </div>

    {{-- Tombol tambah --}}
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
        ➕ Tambah Pengeluaran
    </button>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-danger">
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengeluaran as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit{{ $item->id }}">Edit</button>

                            <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('pengeluaran.update', $item->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header"><h5>Edit Pengeluaran</h5></div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $item->tanggal }}" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Deskripsi</label>
                                            <input type="text" name="deskripsi" value="{{ $item->deskripsi }}" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Jumlah</label>
                                            <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-success">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengeluaran.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5>Tambah Pengeluaran</h5></div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <input type="text" name="deskripsi" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
const ctx = document.getElementById('pengeluaranChart').getContext('2d');
const labels = @json($bulanList);
const data = @json($jumlahList); // ✅ Sudah diformat float dari controller


const pengeluaranChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Pengeluaran',
            data: data,
            tension: 0.3,
            borderWidth: 3,
            fill: false,
            segment: {
                borderColor: ctx => {
                    const i = ctx.p0DataIndex;
                    if (i === 0) return 'gray'; // default for first segment
                    return data[i] > data[i - 1] ? 'red' : 'green';
                }
            },
            pointBackgroundColor: 'black',
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
            legend: { display: true },
            title: {
                display: true,
                text: 'Perubahan Pengeluaran Bulanan (Hijau = Turun, Merah = Naik)'
            }
        }
    }
});
</script>
@endsection
