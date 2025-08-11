@extends('layouts.dashboard')

@section('title', 'Buat Retur Sparepart')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom TomSelect Theme */
        .ts-wrapper {
            --ts-pr-600: #2563eb;
            /* Warna primary */
            --ts-pr-200: #93c5fd;
            --ts-option-radius: 0.375rem;
            padding: 5px !important;
            /* rounded-md */
        }

        .ts-wrapper .item {
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            color: #f3f4f6 !important;
        }

        /* Wrapper dan Control */
        .ts-wrapper.single .ts-control {
            @apply bg-gray-700 border border-gray-600 text-gray-300;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        /* Dropdown */
        .ts-dropdown,
        .ts-dropdown .active {
            background-color: rgb(57 65 81) !important;
            border-color: rgb(57 65 81) !important;
        }



        /* Option */
        .ts-dropdown .option {
            @apply text-gray-300 hover:bg-gray-600;
        }

        /* Selected Option */
        .ts-dropdown .active {
            @apply bg-gray-600 text-white;
        }

        .ts-control,
        .ts-control input {
            background-color: transparent !important;
            border: none !important;
            padding: 2px !important;
            color: white;
        }

        /* Input Search */
        .ts-control input {
            @apply bg-gray-700 text-gray-300 placeholder-gray-400;
        }

        /* Focus State */
        .ts-control.focus {
            @apply ring-2 ring-blue-500 border-blue-500;
        }

        /* Error State */
        .ts-wrapper.error .ts-control {
            @apply border-red-500;
        }

        /* Item Selected */
        .ts-wrapper .item {
            @apply bg-gray-600 text-gray-300 rounded;
        }

        /* Clear Button */
        .ts-wrapper .clear-button {
            @apply text-gray-400 hover:text-gray-300;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Buat Retur Sparepart</h2>
            <a href="{{ route('job-orders.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>


        <form action="{{ route('returns.update', $retur->id) }}" method="POST" id="returnForm">
            @csrf
            @method('PUT')




            @if ($errors->any())
                <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">

                    <div>
                        <span class="font-medium">Error Input Data Retur :</span>
                        <ul class="mt-1.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="p-4 space-y-4">

                <div id="supplyItemsContainer">
                    <h3 class="text-lg font-medium text-white mb-3">Item yang Dikembalikan</h3>
                    <div id="itemsContainer" class="space-y-3">
                        <div class="item-card bg-gray-700 p-4 rounded-lg border border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Produk</label>

                                    <input type="hidden" name="product_id"
                                        class="product-id bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required readonly value="{{ $retur->product->id }}">
                                    <input type="text" name="product_name"
                                        value="{{ old('product_name', $retur->product->name) }}"
                                        class="product-view bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Quantity</label>
                                    <input type="number" name="quantity" value="{{ old('quantity', $retur->quantity) }}"
                                        min="1"
                                        class="quantity-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Harga Satuan</label>
                                    <input type="number" value="{{ old('unit_price', $retur->unit_price) }}"
                                        name="unit_price" min="0" step="0.01"
                                        class="price-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Alasan Retur</label>
                                    <textarea type="text" name="reason"
                                        class="reason-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>{{ old('reason', $retur->reason) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="text-white bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Retur
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Template untuk item retur -->
    <template id="itemTemplate">
        <div class="item-card bg-gray-700 p-4 rounded-lg border border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Produk</label>

                    <input type="hidden" name="items[][product_id]"
                        class="product-id bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required readonly>
                    <input type="text" name="items[][product_name]"
                        class="product-view bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="items[][quantity]" min="1"
                        class="quantity-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Harga Satuan</label>
                    <input type="number" name="items[][unit_price]" min="0" step="0.01"
                        class="price-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Alasan Retur</label>
                    <input type="text" name="items[][reason]"
                        class="reason-input bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required>
                </div>
            </div>
            <button type="button" class="remove-item-btn mt-2 text-red-400 hover:text-red-300 text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus Item
            </button>
        </div>
    </template>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script></script>
@endpush
