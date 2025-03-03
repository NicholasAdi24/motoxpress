<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class KasirController extends Controller
{
    public function index()
    {
        $barangs = Barang::all(); // Ambil semua barang dari database
        return view('kasir.dashboard', compact('barangs'));
    }
}
