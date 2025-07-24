{{-- resources/views/supplies/create-from-selected.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Buat Supply dari Job Order')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Buat Supply untuk Job Order #{{ $jobOrder->unique_id }}</h2>
            <a href="{{ route('supplies.select-job-order') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center">
                Kembali
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Job Order</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-400">Pelanggan</p>
                            <p class="text-white">{{ $jobOrder->customerVehicle->customer->name }}
                                ({{ $jobOrder->customerVehicle->customer->phone }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Kendaraan</p>
                            <p class="text-white">
                                {{ $jobOrder->customerVehicle->vehicle->merk }}
                                {{ $jobOrder->customerVehicle->vehicle->tipe }}
                                ({{ $jobOrder->customerVehicle->vehicle->no_pol }})
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Kilometer</p>
                            <p class="text-white">{{ number_format($jobOrder->km, 0, ',', '.') }} km</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Supply</h3>
                    <form action="{{ route('supplies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="job_order_id" value="{{ $jobOrder->id }}">

                        <div class="space-y-4">
                            <div>
                                <label for="supplier_id"
                                    class="block text-sm font-medium text-gray-300 mb-1">Supplier</label>
                                <select name="supplier_id" id="supplier_id"
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }} -
                                            {{ $supplier->phone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-white mb-3">Item yang Dibutuhkan</h3>
                            <div class="space-y-4">
                                @foreach ($jobOrder->movementItems as $item)
                                    @if ($item->product && $item->product->tipe == 'barang')
                                        <div class="flex items-center space-x-4 bg-gray-600 p-3 rounded">
                                            <input type="checkbox" name="items[{{ $item->id }}][include]"
                                                id="item_{{ $item->id }}" checked
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <label for="item_{{ $item->id }}" class="flex-1">
                                                <p class="text-white">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-400">Stok: {{ $item->product->stok }} | Butuh:
                                                    {{ $item->est_quantity }}</p>
                                            </label>
                                            <div class="w-24">
                                                <input type="number" name="items[{{ $item->id }}][quantity]"
                                                    value="{{ $item->est_quantity }}" min="1"
                                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            </div>
                                            <div class="w-32">
                                                <input type="number" name="items[{{ $item->id }}][unit_price]"
                                                    value="{{ $item->product->unit_price }}" min="0" step="100"
                                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            </div>
                                            <input type="hidden" name="items[{{ $item->id }}][product_id]"
                                                value="{{ $item->product->id }}">
                                            <input type="hidden" name="items[{{ $item->id }}][movement_item_id]"
                                                value="{{ $item->id }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Buat Permintaan Supply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
