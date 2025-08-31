@extends('layouts.dashboard')

@section('title', 'Laporan Barang Keluar')

@section('content')
    <div class="bg-gray-800 shadow overflow-hidden">
        <div class="p-4 flex justify-between items-center border-b border-gray-600">
            <h2 class="sm:text-xl text-sm font-semibold text-white">Laporan Barang Keluar</h2>

            <a href="{{ route('movements.index') }}"
                class="text-gray-300 bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg flex items-center border border-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="p-4">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('movements.report') }}" class="mb-6 bg-gray-700 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="bg-gray-600 border border-gray-500 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Report Table -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Produk</th>
                            <th scope="col" class="px-6 py-3">Stok Saat Ini</th>
                            <th scope="col" class="px-6 py-3">Stok Minimum</th>
                            <th scope="col" class="px-6 py-3">Jumlah Keluar</th>
                            <th scope="col" class="px-6 py-3">Total Nilai</th>
                            <th scope="col" class="px-6 py-3">Jumlah Transaksi</th>
                            <th scope="col" class="px-6 py-3">Status Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stockStatus =
                                    $product['stock'] <= 0
                                        ? 'Habis'
                                        : ($product['stock'] <= $product['min_stock']
                                            ? 'Hampir Habis'
                                            : 'Aman');
                                $statusColor =
                                    $stockStatus == 'Habis'
                                        ? 'red'
                                        : ($stockStatus == 'Hampir Habis'
                                            ? 'yellow'
                                            : 'green');
                            @endphp
                            <tr class="border-b bg-gray-800 border-gray-700 hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                    {{ $product['name'] }}
                                </td>
                                <td class="px-6 py-4">{{ number_format($product['stock']) }}</td>
                                <td class="px-6 py-4">{{ number_format($product['min_stock']) }}</td>
                                <td class="px-6 py-4">{{ number_format($product['total_quantity'], 2) }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($product['total_value'], 2) }}</td>
                                <td class="px-6 py-4">{{ number_format($product['transaction_count']) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                        bg-{{ $statusColor }}-900 text-{{ $statusColor }}-300">
                                        {{ $stockStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                    Tidak ada data laporan barang keluar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
