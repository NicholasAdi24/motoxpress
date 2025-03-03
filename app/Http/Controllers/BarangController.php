<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('gambar_barang', 'public');
        }

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $gambarPath
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
{
    $request->validate([
        'nama_barang' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    if ($request->hasFile('gambar')) {
        // Hapus gambar lama jika ada
        if ($barang->gambar) {
            Storage::delete('public/' . $barang->gambar);
        }
        // Simpan gambar baru
        $gambarPath = $request->file('gambar')->store('gambar_barang', 'public');
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $gambarPath = $barang->gambar;
    }

    $barang->update([
        'nama_barang' => $request->nama_barang,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'gambar' => $gambarPath
    ]);

    return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui!');
}


    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::delete('public/' . $barang->gambar);
        }

        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
