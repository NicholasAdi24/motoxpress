<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class PembayaranController extends Controller
{

    public function index()
    {
        $pembayarans = Pembayaran::all(); // Mengambil semua data pembayaran
        return view('kasir.riwayat-transaksi', compact('pembayarans'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'pesanan' => 'required|string',
            'metode_pembayaran' => 'required|in:cash,qris',
            'total_harga' => 'required|numeric',
            'bukti_pembayaran' => $request->metode_pembayaran === 'qris' 
                ? 'required|image|mimes:jpeg,png,jpg|max:2048' 
                : 'nullable',
        ]);
    
        DB::beginTransaction();
    
        try {
            $pesanan = json_decode($request->pesanan, true);
    
            if (!is_array($pesanan)) {
                return response()->json(['message' => 'Format pesanan tidak valid.'], 422);
            }
    
            $pembayaran = new Pembayaran();
            $pembayaran->pesanan = json_encode($pesanan);
            $pembayaran->total_harga = $request->total_harga;
            $pembayaran->metode_pembayaran = $request->metode_pembayaran;
            $pembayaran->status_pembayaran = 'paid';
            $pembayaran->waktu_pembayaran = now();
    
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('bukti_pembayaran', $filename, 'public');
                $pembayaran->bukti_pembayaran = $filename;
            }
    
            $pembayaran->save();
    
            foreach ($pesanan as $item) {
                $barang = Barang::find($item['id']);
                if ($barang && $barang->stok >= $item['qty']) {
                    $barang->stok -= $item['qty'];
                    $barang->save();
                } else {
                    DB::rollBack();
                    return response()->json(['message' => "Stok barang '{$item['nama']}' tidak mencukupi."], 422);
                }
            }
    
                        // Commit transaksi jika semua berhasil
            DB::commit();

            // Respons JSON jika permintaan via JavaScript/fetch
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Pembayaran berhasil.']);
            }

            // Jika bukan dari fetch() (fallback untuk form biasa)
            return redirect()->route('kasir.dashboard')->with('success', 'Pembayaran berhasil dan stok telah diperbarui.');

    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pembayaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
