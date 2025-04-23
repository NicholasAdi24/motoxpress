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
        $bulan = $today->format('F Y');

        // Filter transaksi paid hari ini
        $transaksiHariIni = Pembayaran::whereDate('waktu_pembayaran', $today)
            ->where('status_pembayaran', 'paid')
            ->get();

        $totalPemasukanHariIni = $transaksiHariIni->sum('total_harga');
        $jumlahTransaksi = $transaksiHariIni->count();

        // Item terjual
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

        // Simpan ke daily_reports
        DailyReport::updateOrCreate(
            ['tanggal' => $today],
            [
                'jumlah_transaksi' => $jumlahTransaksi,
                'pemasukan_total' => $totalPemasukanHariIni ?? 0, // <= pastikan selalu ada nilai
                'item_terjual' => json_encode($itemTerjual)
            ]
        );

        // Hitung ulang bulan ini
        $transaksiBulan = Pembayaran::whereMonth('waktu_pembayaran', $today->month)
            ->whereYear('waktu_pembayaran', $today->year)
            ->where('status_pembayaran', 'paid')
            ->get();

        $totalBulan = $transaksiBulan->sum('total_harga');
        $jumlahBulan = $transaksiBulan->count();

        // Simpan ke monthly_reports
        MonthlyReport::updateOrCreate(
            ['bulan' => $bulan],
            [
                'jumlah_transaksi' => $jumlahBulan,
                'pemasukan_total' => $totalBulan ?? 0 // <= ini kunci!
            ]
        );

        $this->info('Daily and Monthly Reports Generated!');
    }
}
