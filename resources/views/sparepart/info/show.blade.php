@extends('layouts.dashboard')

@section('title', 'Detail Stok Barang')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Stok : {{ $product->name }}</h2>
            <div class="flex space-x-2">

                <a href="{{ route('products.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Detail Part</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-400">Nama Part</p>
                            <p class="text-white">{{ $product->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Part Number</p>
                            <p class="text-white">{{ $product->part_number ?? 'No Part Number' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Tipe</p>
                            <p class="text-white">{{ $product->tipe }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Deskripsi</p>
                            <p class="text-white">{{ $product->description }}</p>
                        </div>

                    </div>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Detail Stok & Harga</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-400">Stok Sekarang</p>
                            <p class="text-white">{{ $product->stok }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Harga Beli</p>
                            <p class="text-white">{{ $product->buying_price }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Harga Jual</p>
                            <p class="text-white">{{ $product->unit_price }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Margin (%)</p>
                            <p class="text-white">{{ $product->margin . '%' }}</p>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
