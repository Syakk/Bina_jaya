@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Riwayat Transaksi" />

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/15 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <x-common.component-card title="Daftar Transaksi Keluar">
        <div class="mb-5 flex justify-end">
            <a href="{{ route('transaksi.create') }}"
                class="inline-flex h-10 items-center justify-center rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600">
                + Catat Transaksi Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="py-3.5 px-4">Kode Transaksi</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Barang Terjual</th>
                        <th class="py-3.5 px-4">Total Harga</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                    @forelse ($transaksis as $trx)
                        <tr>
                            <td class="py-3.5 px-4 font-medium text-gray-900 dark:text-white">{{ $trx->kode_transaksi }}</td>
                            <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">{{ $trx->tanggal->format('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300">
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($trx->details as $detail)
                                        <li>{{ $detail->barang->nama_barang }} ({{ $detail->jumlah }} {{ $detail->barang->satuan }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-500 dark:text-gray-400">{{ $trx->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>
    </x-common.component-card>
@endsection