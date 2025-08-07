@extends('layouts.dashboard')

@section('title', 'Detail Barang Masuk')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #f3f4f6 !important;
        }

        .ts-dropdown {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }

        .ts-dropdown .active {
            background-color: #1f2937 !important;
        }

        .ts-dropdown .selected {
            background-color: #1e40af !important;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Barang Masuk</h2>
            <a href="{{ route('movement-items.index') }}"
                class="text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Basic Information -->
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Pembelian</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nomor Invoice</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $movement_item->purchase_item->purchase->invoice_number ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Supplier</label>
                            <div class="bg-gray-600 text-white p-2 rounded capitalize">
                                {{ $movement_item->purchase_item->purchase->supplier->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Pembelian</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $movement_item->purchase_item->purchase->purchase_date }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                            <div class="bg-gray-600 text-white p-2 rounded capitalize">
                                {{ $movement_item->purchase_item->purchase->status }}</div>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Produk</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Produk</label>
                            <div class="bg-gray-600 text-white p-2 rounded">{{ $movement_item->product->name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Estimasi Qantity</label>
                            <div class="bg-gray-600 text-white p-2 rounded">{{ $movement_item->est_quantity }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Real Quantity</label>
                            <div class="bg-gray-600 text-white p-2 rounded">{{ $movement_item->quantity }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Harga Beli</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ number_format($movement_item->buying_price, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Additional Information -->
            <div class="bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Tambahan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                        <div class="bg-gray-600 text-white p-2 rounded min-h-[100px]">
                            {{ $movement_item->item_description == null || $movement_item->item_description == '' ? 'Tidak Ada Deskripsi' : $movement_item->item_description }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Dibuat Oleh</label>
                        <div class="bg-gray-600 text-white p-2 rounded">{{ $movement_item->creator->name }}</div>

                        <label class="block text-sm font-medium text-gray-300 mb-1 mt-3">Dibuat Pada</label>
                        <div class="bg-gray-600 text-white p-2 rounded">
                            {{ $movement_item->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize supplier select
            new TomSelect('#supplier_id', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            // Initialize product select
            new TomSelect('#product_id', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                render: {
                    option: function(data, escape) {
                        return `
                            <div class="flex justify-between">
                                <div>${escape(data.text)}</div>
                                <div class="text-gray-400">${escape(data.barcode)}</div>
                            </div>
                        `;
                    },
                    item: function(data, escape) {
                        return `
                            <div>${escape(data.text)}</div>
                        `;
                    }
                }
            });
        });
    </script>
@endpush
