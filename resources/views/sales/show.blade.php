@extends('layouts.dashboard')

@section('title', 'Detail Penjualan')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Detail Penjualan</h2>
                <div class="flex items-center gap-3">
                    <div class="text-gray-300">No. Transaksi: {{ $sale->unique_id }}</div>
                    <a href="{{ route('sales.edit', $sale->id) }}"
                        class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center ">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('print_so', $sale->id) }}"
                        class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </a>
                    <a href="{{ route('sales.index') }}"
                        class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Pelanggan</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-400">Nama:</span>
                            <span class="text-white ml-2">{{ $sale->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Alamat:</span>
                            <span class="text-white ml-2">{{ $sale->customer_address }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-300 mb-3">Informasi Transaksi</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-400">Tanggal:</span>
                            <span class="text-white ml-2">{{ $sale->sales_date->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Status:</span>
                            <span class="text-white ml-2">
                                <span class="px-2 py-1 bg-green-600 text-white rounded-full text-xs">Selesai</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-300 mb-3">Item Penjualan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-600 text-gray-300">
                            <tr>
                                <th class="py-3 px-4 text-left">Produk/Jasa</th>
                                <th class="py-3 px-4 text-right">Harga Satuan</th>
                                <th class="py-3 px-4 text-right">Jumlah</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                                <th class="py-3 px-4 text-right">Diskon (%)</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @foreach ($sale->items as $item)
                                <tr>
                                    <td class="py-3 px-4 text-white">{{ $item->product->name }} ({{ $item->product->tipe }})
                                    </td>
                                    <td class="py-3 px-4 text-right text-white">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-white">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right text-white">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-white">
                                        {{ $item->discount_percentage }}</td>
                                    <td class="py-3 px-4 text-right text-white">Rp
                                        {{ number_format($item->price_after_discount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2"></div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-300">Subtotal:</span>
                        <span class="text-white font-medium">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-300">Diskon:</span>
                        <span id="subtotal" class="text-gray-300">Rp
                            {{ number_format($sale->diskon_value, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-t border-gray-600">
                        <span class="text-gray-300 font-semibold">Total:</span>
                        <span class="text-white font-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('sales.destroy', $sale->id) }}" data-id="{{ $sale->id }}" id="btn-delete"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</a>
                <a href="{{ route('invoices.create-from-sale', $sale) }}"
                    class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                    Buat Invoice
                </a>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#btn-delete', function(e) {
            e.preventDefault();
            const salesId = $(this).data('id');


            Swal.fire({
                title: 'Hapus Penjualan?',
                html: `Anda yakin ingin menghapus Data Penjualan?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form delete secara dinamis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/sales/${salesId}`;

                    // Tambahkan CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');
                    form.appendChild(csrfToken);

                    // Tambahkan method spoofing
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endpush
