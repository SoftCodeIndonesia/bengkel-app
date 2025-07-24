@extends('layouts.dashboard')

@section('title', 'Penuhi Permintaan Supply')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Penuhi Permintaan Supply</h2>
            <p class="text-gray-400 text-sm">Job Order: #{{ $supply->jobOrder->unique_id }}</p>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="font-medium">{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('supplies.fulfill', $supply->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-4 space-y-4">


                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Item yang Dipenuhi</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead>
                                <tr>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Stok Saat Ini</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Diminta</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Telah Dipenuhi</th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Jumlah Tambahan</th>


                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach ($supply->items as $item)
                                    <tr>
                                        <td class="px-3 py-4">
                                            <input type="hidden" name="items[{{ $loop->index }}][id]"
                                                value="{{ $item->id }}">
                                            <div class="text-white">{{ $item->product->name }}</div>
                                            <div class="text-gray-400 text-sm">{{ $item->product->barcode }}</div>
                                        </td>
                                        <td class="px-3 py-4 text-white">{{ $item->product->stok }}</td>
                                        <td class="px-3 py-4 text-white">{{ $item->quantity_requested }}</td>
                                        <td class="px-3 py-4 text-white">{{ $item->quantity_fulfilled }}</td>
                                        <td class="px-3 py-4">
                                            <div class="flex">

                                                <div class="relative w-full">
                                                    <input type="number"
                                                        name="items[{{ $loop->index }}][quantity_fulfilled]"
                                                        id="search-dropdown"
                                                        class="block item-quantity w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg rounded-s-gray-100 rounded-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                                        value="{{ old('items[' . $loop->index . '][quantity_fulfilled]', 0) }}"
                                                        min="0"
                                                        max="{{ $item->quantity_requested - $item->quantity_fulfilled }}"
                                                        required />
                                                    <button type="button" data-index="{{ $loop->index }}"
                                                        class="absolute fill_all top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        <svg class="w-4 h-4 text-gray-800 dark:text-white"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd"
                                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('supplies.show', $supply->id) }}"
                        class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pemenuhan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.fill_all').click(function(e) {

                e.preventDefault();
                const row = $(this).closest('tr');
                const quantityInput = row.find('.item-quantity');
                const maxQuantity = parseInt(quantityInput.attr('max'));

                // Set nilai ke maksimum yang diperbolehkan
                quantityInput.val(maxQuantity);
            });
        });
    </script>
@endpush
