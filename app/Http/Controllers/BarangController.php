<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        return view('pages.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('pages.barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('pages.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    // Input stok masuk / penyesuaian stok langsung
    public function updateStok(Request $request, Barang $barang)
    {
        $request->validate([
            'tipe' => 'required|in:tambah,set',
            'jumlah' => 'required|integer|min:0',
        ]);

        if ($request->tipe === 'tambah') {
            $barang->increment('stok', $request->jumlah);
            $pesan = "Stok {$barang->nama_barang} berhasil ditambah sebanyak {$request->jumlah} {$barang->satuan}.";
        } else {
            $barang->update(['stok' => $request->jumlah]);
            $pesan = "Stok {$barang->nama_barang} berhasil diatur menjadi {$request->jumlah} {$barang->satuan}.";
        }

        return redirect()->route('barang.index')->with('success', $pesan);
    }

    public function destroy(Barang $barang)
    {
        if ($barang->transaksiDetails()->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena sudah memiliki riwayat transaksi.');
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }
}