@extends('layouts.dashboard')

@section('title', 'Detail Permintaan Supply')
@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-white">Detail Permintaan Supply</h2>
                <p class="text-gray-400 text-sm">Job Order: #{{ $supply->jobOrder->unique_id }}</p>
            </div>
            <div class="flex space-x-2">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-500 text-white',
                        'processed' => 'bg-blue-500 text-white',
                        'completed' => 'bg-green-500 text-white',
                        'cancelled' => 'bg-red-500 text-white',
                    ];
                @endphp
                <button class="px-3 py-2 rounded-md text-sm {{ $statusClasses[$supply->status] }}">
                    {{ ucfirst($supply->status) }}
                </button>
                <a href="{{ route('supplies.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>

            </div>

        </div>

        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Nama</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $supply->jobOrder->customerVehicle->customer->name }}</div>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Telepon</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $supply->jobOrder->customerVehicle->customer->phone }}</div>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Kendaraan</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $supply->jobOrder->customerVehicle->vehicle->merk }}
                                {{ $supply->jobOrder->customerVehicle->vehicle->tipe }}
                                ({{ $supply->jobOrder->customerVehicle->vehicle->no_pol }})
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Informasi Work Order</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Nomor Work Order</label>
                            <div class="bg-gray-600 text-gray-300 p-2 rounded">{{ $supply->jobOrder->unique_id }}</div>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Tanggal</label>
                            <div class="bg-gray-600 text-white p-2 rounded">
                                {{ $supply->jobOrder->service_at->format('d-m-Y H:i') }}</div>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Catatan</label>
                            <div class="bg-gray-600 text-white p-2 rounded">{{ $supply->jobOrder->notes ?? '-' }}</div>
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
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Jumlah Diminta</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Jumlah Dipenuhi</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Harga Satuan</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-600">
                            @foreach ($supply->items as $item)
                                <tr>
                                    <td class="px-3 py-4">
                                        <div class="text-white">{{ $item->product->name }}</div>
                                        <div class="text-gray-400 text-sm">{{ $item->product->barcode }}</div>
                                    </td>
                                    <td class="px-3 py-4 text-white">{{ $item->quantity_requested }}</td>
                                    <td class="px-3 py-4 text-white">{{ $item->quantity_fulfilled }}</td>
                                    <td class="px-3 py-4 text-white">{{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-4 text-white">{{ number_format($item->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-4">
                                        @php
                                            $itemStatusClasses = [
                                                'pending' => 'bg-yellow-500 text-white',
                                                'partial' => 'bg-blue-500 text-white',
                                                'fulfilled' => 'bg-green-500 text-white',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 py-1 rounded-full text-xs {{ $itemStatusClasses[$item->status] }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end">
                @if ($supply->status == 'pending' || $supply->status == 'processed')
                    <a href="{{ route('supplies.fulfill', $supply->id) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Penuhi Permintaan
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
