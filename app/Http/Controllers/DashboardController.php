<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $barangMenipis = Barang::whereColumn('stok', '<=', 'stok_minimum')->orderBy('stok')->get();
        $stokMenipisCount = $barangMenipis->count();
        $totalTransaksi = Transaksi::count();

        return view('pages.dashboard.ecommerce', compact(
            'totalBarang',
            'totalStok',
            'barangMenipis',
            'stokMenipisCount',
            'totalTransaksi'
        ));
    }
}