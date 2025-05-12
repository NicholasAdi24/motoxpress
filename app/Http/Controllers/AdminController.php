<?php

namespace App\Http\Controllers;
use App\Models\Pembayaran;
use App\Models\Barang;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\MonthlyReport;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Pastikan ada file 'dashboard.blade.php' di folder 'views/admin'
    }

    public function penjualan()
    {
        $pembayarans = Pembayaran::all(); // Mengambil semua data pembayaran
        return view('admin.penjualan', compact('pembayarans'));
    }
    public function dashboard()
    {
        $pembayarans = Pembayaran::all();
        $barangs = Barang::all();
        $bulan = Carbon::now()->format('Y-m');

        $diBawahMinimum = $barangs->filter(fn($item) => $item->stok < (int) $item->min_stok)->count();
        $diAtasMinimum = $barangs->count() - $diBawahMinimum;
        $pengeluaranBulanIni = Expense::where('tanggal', 'like', "$bulan%")->sum('jumlah');
        
        return view('admin.dashboard', compact('pembayarans', 'barangs', 'diBawahMinimum', 'diAtasMinimum', 'pengeluaranBulanIni'));
    }

    
}