@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Tambah Barang" />

    <x-common.component-card title="Form Data Barang Baru">
        <form action="{{ route('barang.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Barang *</label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: BRG-001" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                    @error('kode_barang') <p class="mt-1 text-xs text-error-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Barang *</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Masukkan nama barang" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                    @error('nama_barang') <p class="mt-1 text-xs text-error-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Elektronik / Sembako"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan *</label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'pcs') }}" placeholder="pcs / dus / kg" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli (Rp) *</label>
                    <input type="number" name="harga_beli" value="{{ old('harga_beli', 0) }}" min="0" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual (Rp) *</label>
                    <input type="number" name="harga_jual" value="{{ old('harga_jual', 0) }}" min="0" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Awal *</label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Stok Minimum *</label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 5) }}" min="0" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('barang.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Simpan Barang
                </button>
            </div>
        </form>
    </x-common.component-card>
@endsection