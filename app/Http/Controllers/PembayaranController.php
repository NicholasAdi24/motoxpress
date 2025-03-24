<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{

    public function index()
    {
        $pembayarans = Pembayaran::all(); // Mengambil semua data pembayaran
        return view('kasir.riwayat-transaksi', compact('pembayarans'));
    }
    

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'pesanan' => 'required|string', // Pastikan pesanan dikirim sebagai JSON string
            'metode_pembayaran' => 'required|in:cash,qris',
            'total_harga' => 'required|numeric',
            'bukti_pembayaran' => $request->metode_pembayaran === 'qris' 
                ? 'required|image|mimes:jpeg,png,jpg|max:2048' 
                : 'nullable',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Decode JSON pesanan menjadi array
            $pesanan = json_decode($request->pesanan, true);

            if (!is_array($pesanan)) {
                return back()->with('error', 'Format pesanan tidak valid.');
            }

            // Simpan pembayaran ke database
            $pembayaran = new Pembayaran();
            $pembayaran->pesanan = json_encode($pesanan); // Simpan sebagai JSON
            $pembayaran->total_harga = $request->total_harga;
            $pembayaran->metode_pembayaran = $request->metode_pembayaran;
            $pembayaran->status_pembayaran = 'paid';
            $pembayaran->waktu_pembayaran = now();

            // Simpan bukti pembayaran jika metode adalah QRIS
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('bukti_pembayaran', $filename, 'public');
                $pembayaran->bukti_pembayaran = $filename;
            }

            $pembayaran->save();

            // Kurangi stok barang berdasarkan pesanan
            foreach ($pesanan as $item) {
                $barang = Barang::find($item['id']);
                if ($barang && $barang->stok >= $item['qty']) {
                    $barang->stok -= $item['qty'];
                    $barang->save();
                } else {
                    // Rollback transaksi jika stok tidak cukup
                    DB::rollBack();
                    return back()->with('error', "Stok barang '{$item['nama']}' tidak mencukupi.");
                }
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return redirect()->route('kasir.dashboard')->with('success', 'Pembayaran berhasil dan stok telah diperbarui.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }
}
