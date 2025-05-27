<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Pembayaran;


class PemilikController extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->year;
        $tahunLalu = $tahunIni - 1;
        $bulan = Carbon::now()->format('Y-m');

        // Ambil semua report per bulan
        $reports2025 = MonthlyReport::whereYear('bulan', $tahunIni)->orderBy('bulan')->get();
        $reports2024 = MonthlyReport::whereYear('bulan', $tahunLalu)->orderBy('bulan')->get();

        $bulanList = collect(range(1, 12))->map(fn($b) => Carbon::create(null, $b, 1)->translatedFormat('F'));

        $data2025 = collect();
        $data2024 = collect();

        foreach ($bulanList as $i => $bulanNama) {
            $bulanNumber = $i + 1;

            $data2025->push(optional($reports2025->first(fn($r) => Carbon::parse($r->bulan)->month === $bulanNumber))->pemasukan_total ?? 0);
            $data2024->push(optional($reports2024->first(fn($r) => Carbon::parse($r->bulan)->month === $bulanNumber))->pemasukan_total ?? 0);
        }

        // Per tahun
        $pemasukanPerTahun = MonthlyReport::selectRaw('YEAR(bulan) as tahun, SUM(pemasukan_total) as total')
            ->groupBy(DB::raw('YEAR(bulan)'))
            ->orderBy('tahun')
            ->get();

        $labelsTahun = $pemasukanPerTahun->pluck('tahun');
        $dataTahun = $pemasukanPerTahun->pluck('total');

        // Data ringkasan bulan ini
        $bulanIni = Carbon::now()->startOfMonth();
        $pemasukanBulanIni = MonthlyReport::where('bulan', $bulanIni)->sum('pemasukan_total');
        $pengeluaranBulanIni = Expense::where('tanggal', 'like', "$bulan%")->sum('jumlah');

        // ✅ Ambil semua transaksi paid bulan ini
        $pembayaranBulanIni = Pembayaran::whereMonth('waktu_pembayaran', $bulanIni->month)
            ->whereYear('waktu_pembayaran', $bulanIni->year)
            ->where('status_pembayaran', 'paid')
            ->get();

        $itemStats = [];

        foreach ($pembayaranBulanIni as $pembayaran) {
            $pesanan = json_decode($pembayaran->pesanan, true);
            foreach ($pesanan as $item) {
                $nama = $item['nama'];
                $qty = $item['qty'];
                $total = $item['total']; // langsung gunakan total
        
                if (!isset($itemStats[$nama])) {
                    $itemStats[$nama] = [
                        'qty' => 0,
                        'total' => 0,
                    ];
                }
        
                $itemStats[$nama]['qty'] += $qty;
                $itemStats[$nama]['total'] += $total;
            }
        }

        // 🔢 Konversi dan urutkan berdasarkan total
        $itemStats = collect($itemStats)->sortByDesc('total');
        $totalSemua = $itemStats->sum('total');

        // 🔢 Hitung kontribusi dan siapkan data chart
        $itemStats = $itemStats->map(function ($item, $nama) use ($totalSemua) {
            $item['nama'] = $nama;
            $item['kontribusi'] = round(($item['total'] / $totalSemua) * 100);
            return $item;
        });

        $itemPieChart = $itemStats->take(5)->map(function ($item, $nama) {
            return [
                'label' => $nama,
                'value' => $item['qty'],
            ];
        });

        return view('pemilik.dashboard', [
            'bulanList' => $bulanList,
            'data2024' => $data2024,
            'data2025' => $data2025,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'itemStats' => $itemStats,
            'itemPieChart' => $itemPieChart,
            'labelsTahun' => $labelsTahun,
            'dataTahun' => $dataTahun,
        ]);
    }

    public function pendapatan()
{
    $laporan = MonthlyReport::orderBy('bulan', 'desc')->get();
    return view('pemilik.pendapatan', compact('laporan'));
}

public function pengeluaran()
{
    $pengeluaran = Expense::orderBy('tanggal', 'desc')->get();

    $today = Carbon::today();
    $bulan = Carbon::now()->format('Y-m');

    $totalHarian = Expense::whereDate('tanggal', $today)->sum('jumlah');
    $totalBulanan = Expense::where('tanggal', 'like', "$bulan%")->sum('jumlah');

    // 🔹 Ambil data untuk grafik (total per bulan)
    $chartData = Expense::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(jumlah) as total')
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();

    $bulanList = $chartData->pluck('bulan');
    $jumlahList = $chartData->pluck('total')->map(function ($v) {
        return (float) $v;
    });
    

    return view('pemilik.pengeluaran', compact(
        'pengeluaran', 'totalHarian', 'totalBulanan',
        'bulanList', 'jumlahList' // untuk grafik
    ));
}


public function storePengeluaran(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'deskripsi' => 'required|string|max:255',
        'jumlah' => 'required|numeric|min:0',
    ]);

    Expense::create($request->all());

    return redirect()->route('pemilik.pengeluaran')->with('success', 'Pengeluaran ditambahkan.');
}

public function updatePengeluaran(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'deskripsi' => 'required|string|max:255',
        'jumlah' => 'required|numeric|min:0',
    ]);

    $expense = Expense::findOrFail($id);
    $expense->update($request->all());

    return redirect()->route('pemilik.pengeluaran')->with('success', 'Pengeluaran diperbarui.');
}

public function destroyPengeluaran($id)
{
    Expense::findOrFail($id)->delete();
    return redirect()->route('pemilik.pengeluaran')->with('success', 'Pengeluaran dihapus.');
}
public function labaRugi()
{
    $monthlyReports = MonthlyReport::orderBy('bulan', 'desc')->get(); // urut terbaru ke terlama

    // Total pengeluaran per bulan
    $expenses = Expense::selectRaw('DATE_FORMAT(tanggal, "%Y-%m-01") as bulan, SUM(jumlah) as total')
        ->groupBy('bulan')
        ->pluck('total', 'bulan');

    $data = $monthlyReports->map(function ($laporan) use ($expenses) {
        $bulanKey = Carbon::parse($laporan->bulan)->format('Y-m-01');
        $pendapatan = $laporan->pemasukan_total;
        $hpp = $laporan->total_hpp ?? 0;
        $laba_kotor = $pendapatan - $hpp;
        $pengeluaran = $expenses[$bulanKey] ?? 0;
        $laba_bersih = $laba_kotor - $pengeluaran;

        return [
            'bulan' => Carbon::parse($laporan->bulan)->translatedFormat('F Y'),
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'laba_kotor' => $laba_kotor,
            'pengeluaran' => $pengeluaran,
            'laba_bersih' => $laba_bersih
        ];
    });

    return view('pemilik.laba-rugi', compact('data'));
}


}
