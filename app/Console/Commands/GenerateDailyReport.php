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
        
        $carbonToday = Carbon::now('Asia/Jakarta');
        $today = $carbonToday->toDateString(); // '2025-05-07'
        $bulan = $carbonToday->startOfMonth()->format('Y-m-d');


        // ===== Transaksi Hari Ini =====
        $transaksiHariIni = Pembayaran::whereDate('waktu_pembayaran', $today)
            ->where('status_pembayaran', 'paid')
            ->get();

        $totalPemasukanHariIni = $transaksiHariIni->sum('total_harga');
        $jumlahTransaksi = $transaksiHariIni->count();
        $totalHppHariIni = 0;
        $itemTerjual = [];

        foreach ($transaksiHariIni as $transaksi) {
            $items = json_decode($transaksi->pesanan, true);
            foreach ($items as $item) {
                if (!isset($itemTerjual[$item['nama']])) {
                    $itemTerjual[$item['nama']] = 0;
                }
                $itemTerjual[$item['nama']] += $item['qty'];

                // Tambah ke total HPP
                if (isset($item['hpp'])) {
                    $totalHppHariIni += $item['hpp'] * $item['qty'];
                }
            }
        }

        $report = DailyReport::updateOrCreate(
            ['tanggal' => $today],
            [
                'jumlah_transaksi' => $jumlahTransaksi,
                'pemasukan_total' => $totalPemasukanHariIni,
                'item_terjual' => json_encode($itemTerjual),
                'total_hpp' => $totalHppHariIni,
            ]
        );
        
        \Log::info('Daily report disimpan:', $report->toArray());
        \Log::info('Tanggal laporan:', ['tanggal' => $today]);

        

        $this->info("Transaksi hari ini: $jumlahTransaksi");
$this->info("Total pemasukan: $totalPemasukanHariIni");
$this->info("Total HPP: $totalHppHariIni");
$this->info("Item terjual: " . json_encode($itemTerjual));


        // ===== Transaksi Bulan Ini =====
        $transaksiBulan = Pembayaran::whereMonth('waktu_pembayaran', $carbonToday->month)
    ->whereYear('waktu_pembayaran', $carbonToday->year)
    ->where('status_pembayaran', 'paid')
    ->get();


        $totalBulan = $transaksiBulan->sum('total_harga');
        $jumlahBulan = $transaksiBulan->count();
        $totalHppBulan = 0;

        foreach ($transaksiBulan as $transaksi) {
            $items = json_decode($transaksi->pesanan, true);
            foreach ($items as $item) {
                if (isset($item['hpp'])) {
                    $totalHppBulan += $item['hpp'] * $item['qty'];
                }
            }
        }

        MonthlyReport::updateOrCreate(
            ['bulan' => $bulan],
            [
                'jumlah_transaksi' => $jumlahBulan,
                'pemasukan_total' => $totalBulan,
                'total_hpp' => $totalHppBulan,
            ]
        );

        $this->info('Daily and Monthly Reports Generated!');
    }
}
