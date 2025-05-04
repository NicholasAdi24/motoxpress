<?php

namespace App\Http\Controllers;
use App\Models\Pembayaran;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $barang = Barang::all();

        $diBawahMinimum = $barang->filter(function ($item) {
            return $item->stok < (int) $item->min_stok;
        })->count();

        $diAtasMinimum = $barang->count() - $diBawahMinimum;

        return view('admin.dashboard', compact('diBawahMinimum', 'diAtasMinimum'));
    }
}
