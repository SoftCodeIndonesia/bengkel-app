@extends('layouts.dashboard')

@section('title', 'Detail Paket Service')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-600">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Detail Paket Service</h2>
            <div class="flex space-x-2">
                <a href="{{ route('service-packages.edit', $servicePackage->id) }}"
                    class="text-yellow-500 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('service-packages.index') }}"
                    class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6 bg-gray-800">
            <!-- Informasi Utama Paket -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Informasi Paket</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-300">Nama Paket</p>
                            <p class="text-white font-medium">{{ $servicePackage->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-300">Deskripsi</p>
                            <p class="text-white font-medium">{{ $servicePackage->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                    <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Diskon Paket</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-300">Total Diskon</p>
                            <p class="text-white font-medium">
                                {{ number_format($servicePackage->total_discount, 0, ',', '.') }}
                                {{ $servicePackage->discount_unit === 'percentage' ? '%' : 'Rp' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-300">Jumlah Item</p>
                            <p class="text-white font-medium">{{ $servicePackage->items->count() }} item</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Sparepart -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white border-b border-gray-600 pb-2">Daftar Sparepart</h3>
                    <span class="text-gray-400 text-sm">
                        Total: {{ $servicePackage->items->where('product.tipe', '!=', 'jasa')->count() }} item
                    </span>
                </div>

                @if ($servicePackage->items->where('product.tipe', '!=', 'jasa')->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm">
                            <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Nama Sparepart</th>
                                    <th class="px-4 py-3 text-center">QTY</th>
                                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                    <th class="px-4 py-3 text-right">Diskon</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach ($servicePackage->items->where('product.tipe', '!=', 'jasa') as $item)
                                    <tr class="hover:bg-gray-600">
                                        <td class="px-4 py-3">{{ $item->product->name }}</td>
                                        <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-right">
                                            Rp{{ number_format($item->product->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            {{ $item->discount }} {{ $item->discount_unit === 'percentage' ? '%' : 'Rp' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">

                                            Rp{{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 text-center text-gray-400">
                        Tidak ada sparepart dalam paket ini
                    </div>
                @endif
            </div>

            <!-- Daftar Jasa -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white border-b border-gray-600 pb-2">Daftar Jasa</h3>
                    <span class="text-gray-400 text-sm">
                        Total: {{ $servicePackage->items->where('product.tipe', 'jasa')->count() }} item
                    </span>
                </div>

                @if ($servicePackage->items->where('product.tipe', 'jasa')->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600 bg-gray-700 text-white text-sm">
                            <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Nama Jasa</th>
                                    <th class="px-4 py-3 text-center">FRT</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                    <th class="px-4 py-3 text-right">Diskon</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach ($servicePackage->items->where('product.tipe', 'jasa') as $item)
                                    <tr class="hover:bg-gray-600">
                                        <td class="px-4 py-3">{{ $item->product->name }}</td>
                                        <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>

                                        <td class="px-4 py-3 text-right">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            {{ $item->discount }} {{ $item->discount_unit === 'percentage' ? '%' : 'Rp' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            Rp{{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-700 p-4 rounded-lg border border-gray-600 text-center text-gray-400">
                        Tidak ada jasa dalam paket ini
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div class="bg-gray-700 p-4 rounded-lg border border-gray-600">
                <h3 class="text-lg font-medium text-white mb-4 border-b border-gray-600 pb-2">Ringkasan Paket</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-600">
                        <p class="text-sm text-gray-300">Total Sparepart</p>
                        <p class="text-white font-medium text-xl">
                            Rp{{ number_format(
                                $servicePackage->items->where('product.tipe', '!=', 'jasa')->sum(function ($item) {
                                    $subtotal = $item->product->unit_price * $item->quantity;
                                    // $discount = $item->discount_unit === 'percentage' ? $subtotal * ($item->discount / 100) : $item->discount;
                                    return $subtotal;
                                }),
                                0,
                                ',',
                                '.',
                            ) }}
                        </p>
                    </div>
                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-600">
                        <p class="text-sm text-gray-300">Total Jasa</p>
                        <p class="text-white font-medium text-xl">
                            Rp{{ number_format(
                                $servicePackage->items->where('product.tipe', 'jasa')->sum(function ($item) {
                                    $subtotal = 100000 * $item->quantity;
                                    // $discount = $item->discount_unit === 'percentage' ? $subtotal * ($item->discount / 100) : $item->discount;
                                    return $subtotal;
                                }),
                                0,
                                ',',
                                '.',
                            ) }}
                        </p>
                    </div>
                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-600">
                        <p class="text-sm text-gray-300">Total Discount</p>
                        <p class="text-white font-medium text-xl">
                            Rp{{ number_format($servicePackage->total_discount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-800 p-3 rounded-lg border border-gray-600">
                        <p class="text-sm text-gray-300">Grand Total</p>
                        <p class="text-blue-400 font-medium text-xl">
                            Rp{{ number_format($servicePackage->total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
