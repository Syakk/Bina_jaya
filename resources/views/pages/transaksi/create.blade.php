@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Catat Transaksi" />

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700 dark:border-error-500/30 dark:bg-error-500/15 dark:text-error-400">
            {{ session('error') }}
        </div>
    @endif

    <x-common.component-card title="Form Transaksi (Otomatis Potong Stok)">
        <form action="{{ route('transaksi.store') }}" method="POST"
            x-data="{
                items: [{ barang_id: '', jumlah: 1 }],
                addItem() { this.items.push({ barang_id: '', jumlah: 1 }); },
                removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); }
            }"
            class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Transaksi *</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan / Pembeli</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Penjualan Tunai / Nama Pelanggan"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Daftar Item Barang</h4>
                    <button type="button" @click="addItem()"
                        class="rounded-lg bg-brand-500/10 px-3 py-1.5 text-xs font-medium text-brand-600 hover:bg-brand-500/20 dark:text-brand-400">
                        + Tambah Baris Barang
                    </button>
                </div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-12 items-center rounded-xl border border-gray-200 p-3 dark:border-gray-800">
                        <div class="sm:col-span-8">
                            <select :name="'items[' + index + '][barang_id]'" x-model="item.barang_id" required
                                class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barangs as $b)
                                    <option value="{{ $b->id }}">
                                        {{ $b->kode_barang }} - {{ $b->nama_barang }} (Stok: {{ $b->stok }} {{ $b->satuan }} | Rp {{ number_format($b->harga_jual, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <input type="number" :name="'items[' + index + '][jumlah]'" x-model="item.jumlah" min="1" placeholder="Qty" required
                                class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                        </div>

                        <div class="sm:col-span-1 text-right">
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="rounded-lg bg-error-500/10 px-2.5 py-2 text-xs font-medium text-error-600 hover:bg-error-500/20">
                                Hapus
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('transaksi.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Simpan Transaksi & Potong Stok
                </button>
            </div>
        </form>
    </x-common.component-card>
@endsection