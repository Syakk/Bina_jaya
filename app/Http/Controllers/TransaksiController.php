<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('details.barang')->latest()->paginate(10);
        return view('pages.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('pages.transaksi.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalHarga = 0;

                $transaksi = Transaksi::create([
                    'kode_transaksi' => 'TRX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'total_harga' => 0,
                ]);

                foreach ($request->items as $item) {
                    // Kunci baris barang selama transaksi berlangsung untuk mencegah stok minus
                    $barang = Barang::where('id', $item['barang_id'])->lockForUpdate()->firstOrFail();

                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi (Sisa: {$barang->stok} {$barang->satuan}).");
                    }

                    $subtotal = $barang->harga_jual * $item['jumlah'];
                    $totalHarga += $subtotal;

                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $barang->harga_jual,
                        'subtotal' => $subtotal,
                    ]);

                    // Pemotongan stok otomatis
                    $barang->decrement('stok', $item['jumlah']);
                }

                $transaksi->update(['total_harga' => $totalHarga]);
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan dan stok otomatis terpotong.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}