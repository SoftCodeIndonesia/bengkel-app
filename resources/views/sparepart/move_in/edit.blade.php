@extends('layouts.dashboard')

@section('title', 'Verifikasi Barang Masuk')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Verifikasi Barang Masuk</h2>
        </div>

        <div class="p-4">
            <div class="mb-4">
                <p class="text-gray-400">Produk: <span class="text-white">{{ $incomingItem->item_name }}</span></p>
                <p class="text-gray-400">Qty Order: <span class="text-white">{{ $incomingItem->est_quantity }}</span></p>
            </div>

            <form action="{{ route('inventory.incoming-items.update', $incomingItem->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="quantity" class="block text-gray-400 mb-2">Qty Diterima</label>
                    <input type="number" name="quantity" id="quantity"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full
                              focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('quantity', $incomingItem->est_quantity) }}" min="0"
                        max="{{ $incomingItem->est_quantity }}" required>
                </div>

                <div class="mb-4">
                    <label for="note" class="block text-gray-400 mb-2">Catatan</label>
                    <textarea name="note" id="note" rows="3"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 w-full
                                 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('note', $incomingItem->note) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
                        Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
