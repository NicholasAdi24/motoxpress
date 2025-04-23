<?php

namespace App\Http\Controllers;
use App\Models\Pembayaran;
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
    
}
