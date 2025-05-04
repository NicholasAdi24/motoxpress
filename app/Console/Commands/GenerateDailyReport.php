<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Models\DailyReport;
use App\Models\MonthlyReport;
use Carbon\Carbon;

class GenerateDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Generate daily and monthly reports at 23:00';

    public function handle()
{
    $today = Carbon::today();
    $bulan = $today->startOfMonth()->format('Y-m-d'); // ✅ perbaikan disini

    // --- sisanya tetap ---
    $transaksiHariIni = Pembayaran::whereDate('waktu_pembayaran', $today)
        ->where('status_pembayaran', 'paid')
        ->get();

    $totalPemasukanHariIni = $transaksiHariIni->sum('total_harga');
    $jumlahTransaksi = $transaksiHariIni->count();

    $itemTerjual = [];
    foreach ($transaksiHariIni as $transaksi) {
        $items = json_decode($transaksi->pesanan, true);
        foreach ($items as $item) {
            if (!isset($itemTerjual[$item['nama']])) {
                $itemTerjual[$item['nama']] = 0;
            }
            $itemTerjual[$item['nama']] += $item['qty'];
        }
    }

    // Update atau buat daily report
    DailyReport::updateOrCreate(
        ['tanggal' => $today],
        [
            'jumlah_transaksi' => $jumlahTransaksi,
            'pemasukan_total' => $totalPemasukanHariIni,
            'item_terjual' => json_encode($itemTerjual),
        ]
    );

    // Update atau buat monthly report
    $transaksiBulan = Pembayaran::whereMonth('waktu_pembayaran', $today->month)
        ->whereYear('waktu_pembayaran', $today->year)
        ->where('status_pembayaran', 'paid')
        ->get();

    $totalBulan = $transaksiBulan->sum('total_harga');
    $jumlahBulan = $transaksiBulan->count();

    MonthlyReport::updateOrCreate(
        ['bulan' => $bulan], // ✅ pakai format Y-m-d
        [
            'jumlah_transaksi' => $jumlahBulan,
            'pemasukan_total' => $totalBulan,
        ]
    );

    $this->info('Daily and Monthly Reports Generated!');
}

}
