@extends('layouts.dashboard')

@section('title', 'Buat Permintaan Supply')
@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Permintaan Supply</h2>
            <p class="text-gray-400 text-sm">Work Order: #{{ $jobOrder->unique_id }}</p>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('supplies.store') }}" method="POST">
            @csrf
            <input type="hidden" name="job_order_id" value="{{ $jobOrder->id }}">

            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-white mb-4">Informasi Work Order</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Nomor Order</label>
                                <div class="bg-gray-600 text-white p-2 rounded">
                                    {{ $jobOrder->unique_id }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Tanggal</label>
                                <div class="bg-gray-600 text-white p-2 rounded">
                                    {{ $jobOrder->service_at->format('d-m-Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-white mb-4">Informasi Pelanggan</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Nama</label>
                                <div class="bg-gray-600 text-white p-2 rounded">
                                    {{ $jobOrder->customerVehicle->customer->name }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Kendaraan</label>
                                <div class="bg-gray-600 text-white p-2 rounded">
                                    {{ $jobOrder->customerVehicle->vehicle->merk }}
                                    {{ $jobOrder->customerVehicle->vehicle->tipe }}
                                    ({{ $jobOrder->customerVehicle->vehicle->no_pol }})
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Item yang Dibutuhkan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead>
                                <tr>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Stok Tersedia</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Jumlah Dibutuhkan</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Harga Satuan</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach ($jobOrder->sparepart as $item)
                                    <tr>
                                        <td class="px-3 py-4">
                                            <input type="hidden" name="items[{{ $loop->index }}][item_id]"
                                                value="{{ $item->id }}">
                                            <div class="text-white">{{ $item->product->name }}</div>
                                            <div class="text-gray-400 text-sm">{{ $item->product->barcode }}</div>
                                        </td>
                                        <td class="px-3 py-4 text-white">
                                            {{ $item->product->stok }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <input type="number" name="items[{{ $loop->index }}][quantity_requested]"
                                                value="{{ $item->quantity }}" min="1"
                                                class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-20">
                                        </td>
                                        <td class="px-3 py-4">
                                            <input type="number" name="items[{{ $loop->index }}][unit_price]"
                                                value="{{ number_format($item->unit_price, 0, ',', '.') }}"
                                                class="unit_price bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-24">
                                        </td>
                                        <td class="px-3 py-4 text-white">
                                            <span
                                                class="item-total">{{ number_format($item->total_price, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-gray-300 text-sm mb-1">Catatan</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">

                        Simpan Permintaan Supply
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Calculate totals when quantity or price changes
            $('input[name^="items"]').on('input', function() {
                const row = $(this).closest('tr');
                const quantity = row.find('input[name*="quantity_requested"]').val();
                const price = row.find('input[name*="unit_price"]').val().replace('Rp ', '').replace(/\./g,
                    '');
                const total = quantity * price;
                row.find('.item-total').text(formatRupiah(total));
            });


            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // e.preventDefault();
                document.querySelectorAll('.unit_price').forEach(row => {
                    // const priceInput = row.querySelector('.unit-price');
                    // console.log(priceInput);
                    // priceInput.value = originalNumber(priceInput.value);
                    row.value = originalNumber(row.value);
                });


            });
        });
    </script>
@endpush
