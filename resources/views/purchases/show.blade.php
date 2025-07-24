@extends('layouts.dashboard')
@php
    use Carbon\Carbon;
@endphp
@section('title', 'Detail Pembelian')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Pembelian #{{ $purchase->invoice_number }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('purchases.edit', $purchase->id) }}"
                    class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank"
                    class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak
                </a>
                <a href="{{ route('purchases.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-700 p-4 rounded-md">
                    <h3 class="text-lg font-medium text-white mb-2">Informasi Pembelian</h3>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-gray-300 text-sm">No. Invoice</label>
                            <p class="text-white">{{ $purchase->invoice_number }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm">Tanggal</label>
                            <p class="text-white">{{ Carbon::parse($purchase->date)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-md">
                    <h3 class="text-lg font-medium text-white mb-2">Informasi Supplier</h3>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-gray-300 text-sm">Nama</label>
                            <p class="text-white">{{ $purchase->supplier->name }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm">Telepon</label>
                            <p class="text-white">{{ $purchase->supplier->phone }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-md">
                    <h3 class="text-lg font-medium text-white mb-2">Informasi Pembayaran</h3>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-gray-300 text-sm">Total</label>
                            <p class="text-white">Rp {{ number_format($purchase->total, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm">Status</label>
                            @if ($purchase->status == 'draft')
                                <span
                                    class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">DRAFT</span>
                            @elseif ($purchase->status == 'unpaid')
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-yellow-900 dark:text-yellow-300">Belum
                                    Lunas</span>
                            @elseif ($purchase->status == 'paid')
                                <span
                                    class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">Lunas</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-white mb-4">Detail Pembelian</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Harga Satuan</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->items as $item)
                                <tr class="border-b border-gray-700 bg-gray-800 hover:bg-gray-700">
                                    <td class="px-4 py-3">{{ $item->product->name }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white mb-4">Lampiran</h3>

                @if ($purchase->source_documents)
                    <div class="flex w-1/2">


                        <div class="flex items-center gap-3">
                            <p class="text-white">{{ $purchase->original_filename }}</p>
                            <a href="{{ route('purchase.download', $purchase->id) }}" target="__blank">
                                <svg class="w-6 h-6 text-gray-800 dark:text-blue-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 13V4M7 14H5a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2m-1-5-4 5-4-5m9 8h.01" />
                                </svg>
                            </a>
                            <form id="delete-file-form" action="{{ route('purchase.deleteFile', $purchase->id) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn-file" type="submit" id="btn-delete-file"
                                    data-id="{{ $purchase->id }}">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-red-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                    </svg>

                                </button>
                            </form>


                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('btn-delete-file').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus file ini?',
                text: "File yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-file-form').submit();
                }
            });
        });
    </script>
@endpush
