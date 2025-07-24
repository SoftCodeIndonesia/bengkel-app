@extends('layouts.dashboard')

@section('title', 'Edit Invoice')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Edit Invoice #{{ $invoice->unique_id }}</h2>
        </div>

        <div class="p-4">
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    @foreach ($errors->all() as $error)
                        <span class="font-medium">{{ $error }}</span>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="edit-invoice-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Tipe Invoice</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipe" value="sales"
                                        {{ $invoice->tipe === 'sales' ? 'checked' : '' }}
                                        class="form-radio text-blue-500 bg-gray-700 border-gray-600" disabled>
                                    <span class="ml-2 text-gray-300">Penjualan</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipe" value="services"
                                        {{ $invoice->tipe === 'services' ? 'checked' : '' }}
                                        class="form-radio text-blue-500 bg-gray-700 border-gray-600" disabled>
                                    <span class="ml-2 text-gray-300">Service</span>
                                </label>
                            </div>
                        </div>


                    </div>


                </div>

                <div class="flex gap-3 mb-6">
                    <div class="flex-1">
                        <label for="reference_id" class="block text-gray-300 mb-2">Referensi</label>
                        <input type="hidden" name="reference_id" value="{{ $reference->id }}">
                        <input type="text" value="{{ $reference->unique_id }}"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>
                    <div class="flex-1">
                        <label for="customer_id" class="block text-gray-300 mb-2">Pelanggan</label>
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="text" value="{{ $customer->name }}"
                            class="w-full bg-gray-700 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-white mb-4">Detail Transaksi</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Item</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                    <th class="px-4 py-3 text-right">Qty</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($invoice->tipe === 'sales')
                                    @foreach ($reference->items as $item)
                                        <tr class="border-b border-gray-700 bg-gray-800 hover:bg-gray-700">
                                            <td class="px-4 py-3">{{ $item->product->name }}</td>
                                            <td class="px-4 py-3 text-right">Rp
                                                {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right">Rp
                                                {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($reference->orderItems as $item)
                                        <tr class="border-b border-gray-700 bg-gray-800 hover:bg-gray-700">
                                            <td class="px-4 py-3">{{ $item->product->name }}</td>
                                            <td class="px-4 py-3 text-right">Rp
                                                {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right">Rp
                                                {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Subtotal</label>
                        <input type="number" name="subtotal" id="subtotal"
                            value="{{ number_format($invoice->subtotal, 0, ',', '.') }}"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>

                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Diskon</label>
                        <div class="flex space-x-2 mb-2">
                            <select name="diskon_unit" id="diskon_unit"
                                class="flex-1 bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="percentage" {{ $invoice->diskon_unit === 'percentage' ? 'selected' : '' }}>
                                    Persentase (%)</option>
                                <option value="nominal" {{ $invoice->diskon_unit === 'nominal' ? 'selected' : '' }}>Nominal
                                    (Rp)</option>
                            </select>
                        </div>
                        <input type="number" name="diskon_value" id="diskon_value" value="{{ $invoice->diskon_value }}"
                            min="0"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Total</label>
                        <input type="number" name="total" id="total"
                            value="{{ number_format($invoice->total, 0, ',', '.') }}"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-700 p-4 rounded-md">
                        <label class="block text-gray-300 mb-2">Status Invoice</label>
                        <select name="status"
                            class="w-full bg-gray-800 border border-gray-600 rounded-md text-white px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ $invoice->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('invoices.show', $invoice->id) }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const diskonUnit = document.getElementById('diskon_unit');
            const diskonValue = document.getElementById('diskon_value');
            const subtotal = document.getElementById('subtotal');
            const total = document.getElementById('total');

            function calculateTotal() {
                console.log(subtotal.value);
                let subtotalValue = parseFloat(subtotal.value.replace('.', '')) || 0;
                let diskon = parseFloat(diskonValue.value) || 0;

                if (diskonUnit.value === 'percentage') {
                    diskon = subtotalValue * (diskon / 100);
                }

                // Pastikan diskon tidak melebihi subtotal
                diskon = Math.min(diskon, subtotalValue);

                total.value = formatNumber((subtotalValue - diskon))
            }

            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            diskonUnit.addEventListener('change', calculateTotal);
            diskonValue.addEventListener('input', calculateTotal);

            $('#edit-invoice-form').submit(function(e) {
                $('input[name="subtotal"]').val($('input[name="subtotal"]').val().replace(/[^0-9]/g, ''));
                $('input[name="total"]').val($('input[name="total"]').val().replace(/[^0-9]/g, ''));
            });
        });
    </script>
@endpush
