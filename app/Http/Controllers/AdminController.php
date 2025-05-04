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
        return redirect()->route('admin.dashboard');
    }

    public function penjualan()
    {
        $pembayarans = Pembayaran::all();
        return view('admin.penjualan', compact('pembayarans'));
    }

    public function dashboard()
    {
        $pembayarans = Pembayaran::all();
        $barangs = Barang::all();

        $diBawahMinimum = $barangs->filter(fn($item) => $item->stok < (int) $item->min_stok)->count();
        $diAtasMinimum = $barangs->count() - $diBawahMinimum;
        
        return view('admin.dashboard', compact('pembayarans', 'barangs', 'diBawahMinimum', 'diAtasMinimum'));
    }
}