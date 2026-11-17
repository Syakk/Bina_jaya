@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Manajemen Stok" />

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/15 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700 dark:border-error-500/30 dark:bg-error-500/15 dark:text-error-400">
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{
        stokModalOpen: false,
        selectedBarang: { id: null, nama: '', stok: 0, satuan: '' },
        openStokModal(id, nama, stok, satuan) {
            this.selectedBarang = { id, nama, stok, satuan };
            this.stokModalOpen = true;
        }
    }">
        <x-common.component-card title="Daftar Data Barang & Stok">
            <!-- Toolbar Pencarian & Tombol Tambah -->
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <form action="{{ route('barang.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode / nama barang..."
                        class="h-10 rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white/90" />
                    <button type="submit"
                        class="h-10 rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">
                        Cari
                    </button>
                </form>

                <a href="{{ route('barang.create') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600">
                    + Tambah Barang
                </a>
            </div>

            <!-- Tabel Barang -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-800 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <th class="py-3.5 px-4">Kode</th>
                            <th class="py-3.5 px-4">Nama Barang</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Harga Jual</th>
                            <th class="py-3.5 px-4">Stok</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                        @forelse ($barangs as $barang)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="py-3.5 px-4 font-medium text-gray-900 dark:text-white">{{ $barang->kode_barang }}</td>
                                <td class="py-3.5 px-4 text-gray-800 dark:text-gray-200">{{ $barang->nama_barang }}</td>
                                <td class="py-3.5 px-4 text-gray-500 dark:text-gray-400">{{ $barang->kategori ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-gray-800 dark:text-gray-200">
                                    Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $barang->stok }} {{ $barang->satuan }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($barang->stok == 0)
                                        <span class="inline-flex rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">
                                            Habis
                                        </span>
                                    @elseif ($barang->stok <= $barang->stok_minimum)
                                        <span class="inline-flex rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-400">
                                            Menipis
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                                            Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button type="button"
                                            @click="openStokModal({{ $barang->id }}, '{{ addslashes($barang->nama_barang) }}', {{ $barang->stok }}, '{{ $barang->satuan }}')"
                                            class="rounded-lg bg-success-500/10 px-2.5 py-1.5 text-xs font-medium text-success-600 hover:bg-success-500/20 dark:text-success-400">
                                            + Stok
                                        </button>
                                        <a href="{{ route('barang.edit', $barang) }}"
                                            class="rounded-lg bg-brand-500/10 px-2.5 py-1.5 text-xs font-medium text-brand-600 hover:bg-brand-500/20 dark:text-brand-400">
                                            Edit
                                        </a>
                                        <form action="{{ route('barang.destroy', $barang) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg bg-error-500/10 px-2.5 py-1.5 text-xs font-medium text-error-600 hover:bg-error-500/20 dark:text-error-400">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data barang. Klik <strong>+ Tambah Barang</strong> untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $barangs->links() }}
            </div>
        </x-common.component-card>

        <!-- Modal Input / Update Stok -->
        <div x-show="stokModalOpen" x-cloak
            class="fixed inset-0 z-999 flex items-center justify-center bg-gray-900/60 p-4">
            <div @click.outside="stokModalOpen = false"
                class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Stok Barang</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Barang: <span class="font-medium text-gray-800 dark:text-gray-200" x-text="selectedBarang.nama"></span>
                    (Stok saat ini: <span x-text="selectedBarang.stok + ' ' + selectedBarang.satuan"></span>)
                </p>

                <form :action="'/barang/' + selectedBarang.id + '/stok'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Update</label>
                        <select name="tipe"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="tambah">Tambah Stok Masuk (+)</option>
                            <option value="set">Atur Ulang Jumlah Stok (Opname)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                        <input type="number" name="jumlah" min="0" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-3.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white/90" />
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="stokModalOpen = false"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                            Simpan Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection