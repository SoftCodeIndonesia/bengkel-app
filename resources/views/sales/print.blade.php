<x-app-layout>
    @push('styles')
        <style>
            @page {
                size: A4;
                /* Sets the page size to A4 */
                /* margin: 20mm; */
                /* Sets a 20mm margin on all sides */
            }

            body {
                display: flex;
            }
        </style>
    @endpush
    <div class="bg-white shadow overflow-hidden border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <p>{{ $sale->unique_id }}</p>
            <p>{{ $sale->sales_date->format('d M Y H:i') }}</p>
        </div>
        <div class="p-4 rounded-lg border-gray-600 mt-6">
            <div class="overflow-x-auto">
                <div class="flex justify-between mb-2 items-center">
                    <div class="flex-1 space-y-1">
                        <div>
                            <p class="text-sm text-gray-600">Nama Pelanggan</p>
                            <p class="text-black">{{ $sale->customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Telepon</p>
                            <p class="text-black">{{ $sale->customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Alamat</p>
                            <p class="text-black">{{ $sale->customer->address }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="text-black">{{ $sale->notes ?? '-' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="px-1">

            <div class="px-4 rounded-lg border-gray-600 mt-3">
                <div class="flex justify-between items-center">
                    <p>Daftar Item</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-700 border rounded-lg overflow-hidden"
                        style="width: 100%; table-layout: fixed;">
                        <thead class="bg-gray-600 border text-gray-300">
                            <tr>
                                <th class="py-1 text-left">Produk</th>
                                <th class="py-1 px-4 text-right">Harga</th>
                                <th class="py-1 px-4 text-right">Jumlah</th>
                                <th class="py-1 px-4 text-right">Subtotal</th>
                                <th class="py-1 px-4 text-right">Diskon (%)</th>
                                <th class="py-1 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @foreach ($sale->items as $item)
                                <tr>
                                    <td class="py-1 text-white">{{ $item->product->name }}
                                        ({{ $item->product->tipe }})
                                    </td>
                                    <td class="py-1 px-4 text-right text-white">Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="py-1 px-4 text-right text-white">{{ $item->quantity }}</td>
                                    <td class="py-1 px-4 text-right text-white">Rp
                                        {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    <td class="py-1 px-4 text-right text-white">
                                        {{ $item->discount_percentage }}</td>
                                    <td class="py-1 text-right text-white">Rp
                                        {{ number_format($item->price_after_discount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 rounded-lg border-gray-600 mt-6">
                <h3 class="text-lg font-medium text-black mb-4">Rincian Biaya</h3>
                <div class="overflow-x-auto">
                    <div class="flex justify-between mb-2 items-center">
                        <span class="text-gray-600">Subtotal:</span>
                        <span id="subtotal" class="text-gray-600">Rp
                            {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Diskon:</span>
                        <span id="subtotal" class="text-gray-600">Rp
                            {{ number_format($sale->diskon_value, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-medium">
                        <span class="text-gray-600">Total:</span>
                        <span id="total" class="text-black font-bold">Rp
                            {{ number_format($sale->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>


        </div>

        <div class="mt-16 pt-8  border-gray-300" style="margin-top: 64px; padding-top: 32px;">
            <div class="text-center">
                <p class="mb-4">Customer</p>
                <div class="mt-12">
                    <p class="font-medium">(__________________________)</p>
                    <p class="text-gray-600">{{ $sale->customer->name }}</p>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            window.print();
        </script>
    @endpush
</x-app-layout>
