@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('content')
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-600">
            <h2 class="text-xl font-semibold text-white">Dashboard</h2>
        </div>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card: Today's Job Orders -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Job Order Hari Ini</p>
                        <h3 class="text-white text-2xl font-bold">{{ $todayJobOrders }}</h3>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('job-orders.index') }}" class="text-blue-400 text-sm hover:underline">Lihat semua</a>
                </div>
            </div>

            <!-- Card: Today's Sales -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Penjualan Hari Ini</p>
                        <h3 class="text-white text-2xl font-bold">{{ $todaySales }}</h3>
                    </div>
                    <div class="bg-green-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('sales.index') }}" class="text-blue-400 text-sm hover:underline">Lihat semua</a>
                </div>
            </div>

            <!-- Card: Today's Job Order Income -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Job Order</p>
                        <h3 class="text-white text-2xl font-bold">Rp {{ number_format($todayJobOrderIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="bg-yellow-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('invoices.index') }}" class="text-blue-400 text-sm hover:underline">Lihat invoice</a>
                </div>
            </div>

            <!-- Card: Today's Sales Income -->
            <div class="bg-gray-700 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Penjualan</p>
                        <h3 class="text-white text-2xl font-bold">Rp {{ number_format($todaySalesIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('invoices.index') }}" class="text-blue-400 text-sm hover:underline">Lihat invoice</a>
                </div>
            </div>
        </div>

        <!-- Fast & Slow Moving Products -->
        <div class="p-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Fast Moving Products -->
            <div class="bg-gray-700 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Fast Moving Products</h3>
                    <p class="text-gray-400 text-sm">Produk dengan penjualan tertinggi bulan ini</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-600">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Terjual</th>
                                <th class="px-4 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fastMovingProducts as $product)
                                <tr class="border-b border-gray-600 hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-white">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->total_sold }}</td>
                                    <td class="px-4 py-3 text-right">Rp
                                        {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Slow Moving Products -->
            <div class="bg-gray-700 rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b border-gray-600">
                    <h3 class="text-lg font-semibold text-white">Slow Moving Products</h3>
                    <p class="text-gray-400 text-sm">Produk dengan penjualan terendah bulan ini</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-600">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-right">Stok</th>
                                <th class="px-4 py-3 text-right">Min. Stok</th>
                                <th class="px-4 py-3 text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slowMovingProducts as $product)
                                <tr class="border-b border-gray-600 hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-white">{{ $product->name }}</td>
                                    <td
                                        class="px-4 py-3 text-right {{ $product->stok < $product->min_stock ? 'text-red-400' : '' }}">
                                        {{ $product->stok }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->min_stock }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
